<?php
namespace Sasedev\Doctrine\Behavior\References\Mapping\Event\Adapter;

use Doctrine\ODM\MongoDB\DocumentManager;
use ProxyManager\Proxy\GhostObjectInterface as Proxy;
use Sasedev\Doctrine\Behavior\Mapping\Event\Adapter\ODM as BaseAdapterODM;
use Sasedev\Doctrine\Behavior\References\Mapping\Event\ReferencesAdapter;

/**
 * Doctrine event adapter for ODM references behavior
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @author Bulat Shakirzyanov <mallluhuct@gmail.com>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class ODM extends BaseAdapterODM implements ReferencesAdapter
{

    /**
     *
     * @inheritDoc
     */
    public function getIdentifier(DocumentManager $om, $object, $single = true)
    {

        return $this->extractIdentifier($om, $object, $single);

    }

    /**
     *
     * @inheritDoc
     */
    public function getSingleReference(DocumentManager $om, $class, $identifier)
    {

        $meta = $om->getClassMetadata($class);

        if (! $meta->isInheritanceTypeNone())
        {
            return $om->find($class, $identifier);
        }

        return $om->getReference($class, $identifier);

    }

    /**
     *
     * @inheritDoc
     */
    public function extractIdentifier(DocumentManager $om, $object, $single = true)
    {

        $meta = $om->getClassMetadata(get_class($object));
        if ($object instanceof Proxy)
        {
            $id = $om->getUnitOfWork()
                ->getDocumentIdentifier($object);
        }
        else
        {
            $id = $meta->getReflectionProperty($meta->identifier)
                ->getValue($object);
        }

        if ($single || ! $id)
        {
            return $id;
        }
        else
        {
            return [
                $meta->identifier => $id
            ];
        }

    }

}
