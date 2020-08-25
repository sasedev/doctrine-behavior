<?php
namespace Sasedev\Doctrine\Behavior\Loggable\Mapping\Event\Adapter;

use Sasedev\Doctrine\Behavior\Mapping\Event\Adapter\ODM as BaseAdapterODM;
use Sasedev\Doctrine\Behavior\Loggable\Mapping\Event\LoggableAdapter;

/**
 * Doctrine event adapter for ODM adapted
 * for Loggable behavior
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class ODM extends BaseAdapterODM implements LoggableAdapter
{

    /**
     *
     * {@inheritdoc}
     */
    public function getDefaultLogEntryClass()
    {

        return 'Sasedev\\Doctrine\\Behavior\\Loggable\\Document\\LogEntry';

    }

    /**
     *
     * {@inheritdoc}
     */
    public function isPostInsertGenerator($meta)
    {

        return false;

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getNewVersion($meta, $object)
    {

        $dm = $this->getObjectManager();
        $objectMeta = $dm->getClassMetadata(get_class($object));
        $identifierField = $this->getSingleIdentifierFieldName($objectMeta);
        $objectId = $objectMeta->getReflectionProperty($identifierField)
            ->getValue($object);

        $qb = $dm->createQueryBuilder($meta->name);
        $qb->select('version');
        $qb->field('objectId')
            ->equals($objectId);
        $qb->field('objectClass')
            ->equals($objectMeta->name);
        $qb->sort('version', 'DESC');
        $qb->limit(1);
        $q = $qb->getQuery();
        $q->setHydrate(false);

        $result = $q->getSingleResult();
        if ($result)
        {
            $result = $result['version'] + 1;
        }

        return $result;

    }

}
