<?php
namespace Sasedev\Doctrine\Behavior\References\Mapping\Event\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use ProxyManager\Proxy\GhostObjectInterface as Proxy;
use Sasedev\Doctrine\Behavior\Mapping\Event\Adapter\ORM as BaseAdapterORM;
use Sasedev\Doctrine\Behavior\References\Mapping\Event\ReferencesAdapter;

/**
 * Doctrine event adapter for ORM references behavior
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @author Bulat Shakirzyanov <mallluhuct@gmail.com>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class ORM extends BaseAdapterORM implements ReferencesAdapter
{

    /**
     *
     * @inheritDoc
     */
    public function getIdentifier(EntityManagerInterface $om, $object, $single = true)
    {

        return $this->extractIdentifier($om, $object, $single);

    }

    /**
     *
     * @inheritDoc
     */
    public function getSingleReference(EntityManagerInterface $om, $class, $identifier)
    {

        return $om->getReference($class, $identifier);

    }

    /**
     *
     * @inheritDoc
     */
    public function extractIdentifier(EntityManagerInterface $om, $object, $single = true)
    {

        if ($object instanceof Proxy)
        {
            $id = $om->getUnitOfWork()
                ->getEntityIdentifier($object);
        }
        else
        {
            $meta = $om->getClassMetadata(get_class($object));
            $id = [];
            foreach ($meta->identifier as $name)
            {
                $id[$name] = $meta->getReflectionProperty($name)
                    ->getValue($object);
                // return null if one of identifiers is missing
                if (! $id[$name])
                {
                    return null;
                }
            }
        }

        if ($single)
        {
            $id = current($id);
        }

        return $id;

    }

}
