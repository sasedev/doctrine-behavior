<?php
namespace Sasedev\Doctrine\Behavior\Translatable\Mapping\Event\Adapter;

use Doctrine\ODM\MongoDB\Iterator\Iterator;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Types\Type;
use Sasedev\Doctrine\Behavior\Exception\RuntimeException;
use Sasedev\Doctrine\Behavior\Mapping\Event\Adapter\ODM as BaseAdapterODM;
use Sasedev\Doctrine\Behavior\Tool\Wrapper\AbstractWrapper;
use Sasedev\Doctrine\Behavior\Translatable\Mapping\Event\TranslatableAdapter;

/**
 * Doctrine event adapter for ODM adapted
 * for Translatable behavior
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class ODM extends BaseAdapterODM implements TranslatableAdapter
{

    /**
     *
     * {@inheritdoc}
     */
    public function usesPersonalTranslation($translationClassName)
    {

        return $this->getObjectManager()
            ->getClassMetadata($translationClassName)
            ->getReflectionClass()
            ->isSubclassOf('Sasedev\Doctrine\Behavior\Translatable\Document\MappedSuperclass\AbstractPersonalTranslation');

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getDefaultTranslationClass()
    {

        return 'Sasedev\\Doctrine\\Behavior\\Translatable\\Document\\Translation';

    }

    /**
     *
     * {@inheritdoc}
     */
    public function loadTranslations($object, $translationClass, $locale, $objectClass)
    {

        $dm = $this->getObjectManager();
        $wrapped = AbstractWrapper::wrap($object, $dm);
        $result = [];

        if ($this->usesPersonalTranslation($translationClass))
        {
            // first try to load it using collection
            foreach ($wrapped->getMetadata()->fieldMappings as $mapping)
            {
                $isRightCollection = isset($mapping['association']) && $mapping['association'] === ClassMetadata::REFERENCE_MANY && $mapping['targetDocument'] === $translationClass && $mapping['mappedBy'] === 'object';
                if ($isRightCollection)
                {
                    $collection = $wrapped->getPropertyValue($mapping['fieldName']);
                    foreach ($collection as $trans)
                    {
                        if ($trans->getLocale() === $locale)
                        {
                            $result[] = [
                                'field' => $trans->getField(),
                                'content' => $trans->getContent()
                            ];
                        }
                    }

                    return $result;
                }
            }
            $q = $dm->createQueryBuilder($translationClass)
                ->field('object.$id')
                ->equals($wrapped->getIdentifier())
                ->field('locale')
                ->equals($locale)
                ->getQuery();
        }
        else
        {
            // load translated content for all translatable fields
            // construct query
            $q = $dm->createQueryBuilder($translationClass)
                ->field('foreignKey')
                ->equals($wrapped->getIdentifier())
                ->field('locale')
                ->equals($locale)
                ->field('objectClass')
                ->equals($objectClass)
                ->getQuery();
        }
        $q->setHydrate(false);
        $result = $q->execute();
        if ($result instanceof Iterator)
        {
            $result = $result->toArray();
        }

        return $result;

    }

    /**
     *
     * {@inheritdoc}
     */
    public function findTranslation(AbstractWrapper $wrapped, $locale, $field, $translationClass, $objectClass)
    {

        $dm = $this->getObjectManager();
        $qb = $dm->createQueryBuilder($translationClass)
            ->field('locale')
            ->equals($locale)
            ->field('field')
            ->equals($field)
            ->limit(1);
        if ($this->usesPersonalTranslation($translationClass))
        {
            $qb->field('object.$id')
                ->equals($wrapped->getIdentifier());
        }
        else
        {
            $qb->field('foreignKey')
                ->equals($wrapped->getIdentifier());
            $qb->field('objectClass')
                ->equals($objectClass);
        }
        $q = $qb->getQuery();
        $result = $q->execute();
        if ($result instanceof Iterator)
        {
            $result = current($result->toArray());
        }

        return $result;

    }

    /**
     *
     * {@inheritdoc}
     */
    public function removeAssociatedTranslations(AbstractWrapper $wrapped, $transClass, $objectClass)
    {

        $dm = $this->getObjectManager();
        $qb = $dm->createQueryBuilder($transClass)
            ->remove();
        if ($this->usesPersonalTranslation($transClass))
        {
            $qb->field('object.$id')
                ->equals($wrapped->getIdentifier());
        }
        else
        {
            $qb->field('foreignKey')
                ->equals($wrapped->getIdentifier());
            $qb->field('objectClass')
                ->equals($objectClass);
        }
        $q = $qb->getQuery();

        return $q->execute();

    }

    /**
     *
     * {@inheritdoc}
     */
    public function insertTranslationRecord($translation)
    {

        $dm = $this->getObjectManager();
        $meta = $dm->getClassMetadata(get_class($translation));
        $collection = $dm->getDocumentCollection($meta->name);
        $data = [];

        foreach ($meta->getReflectionProperties() as $fieldName => $reflProp)
        {
            if (! $meta->isIdentifier($fieldName))
            {
                $data[$meta->fieldMappings[$fieldName]['name']] = $reflProp->getValue($translation);
            }
        }

        if (! $collection->insert($data))
        {
            throw new RuntimeException('Failed to insert new Translation record');
        }

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getTranslationValue($object, $field, $value = false)
    {

        $dm = $this->getObjectManager();
        $wrapped = AbstractWrapper::wrap($object, $dm);
        $meta = $wrapped->getMetadata();
        $mapping = $meta->getFieldMapping($field);
        $type = $this->getType($mapping['type']);
        if ($value === false)
        {
            $value = $wrapped->getPropertyValue($field);
        }

        return $type->convertToDatabaseValue($value);

    }

    /**
     *
     * {@inheritdoc}
     */
    public function setTranslationValue($object, $field, $value)
    {

        $dm = $this->getObjectManager();
        $wrapped = AbstractWrapper::wrap($object, $dm);
        $meta = $wrapped->getMetadata();
        $mapping = $meta->getFieldMapping($field);
        $type = $this->getType($mapping['type']);

        $value = $type->convertToPHPValue($value);
        $wrapped->setPropertyValue($field, $value);

    }

    private function getType($type)
    {

        return Type::getType($type);

    }

}
