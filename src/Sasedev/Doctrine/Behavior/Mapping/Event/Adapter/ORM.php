<?php
namespace Sasedev\Doctrine\Behavior\Mapping\Event\Adapter;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Sasedev\Doctrine\Behavior\Mapping\Event\AdapterInterface;
use Sasedev\Doctrine\Behavior\Exception\RuntimeException;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Doctrine event adapter for ORM specific
 * event arguments
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class ORM implements AdapterInterface
{

    /**
     *
     * @var EventArgs
     */
    private $args;

    /**
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     *
     * {@inheritdoc}
     */
    public function setEventArgs(EventArgs $args)
    {

        $this->args = $args;

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getDomainObjectName()
    {

        return 'Entity';

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getManagerName()
    {

        return 'ORM';

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getRootObjectClass($meta)
    {

        return $meta->rootEntityName;

    }

    /**
     *
     * {@inheritdoc}
     */
    public function __call($method, $args)
    {

        if (is_null($this->args))
        {
            throw new RuntimeException("Event args must be set before calling its methods");
        }
        $method = str_replace('Object', $this->getDomainObjectName(), $method);

        return call_user_func_array([
            $this->args,
            $method
        ], $args);

    }

    /**
     * Set the entity manager
     *
     * @param EntityManagerInterface $em
     */
    public function setEntityManager(EntityManagerInterface $em)
    {

        $this->em = $em;

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getObjectManager()
    {

        if (! is_null($this->em))
        {
            return $this->em;
        }

        return $this->__call('getEntityManager', []);

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getObjectState($uow, $object)
    {

        return $uow->getEntityState($object);

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getObjectChangeSet($uow, $object)
    {

        return $uow->getEntityChangeSet($object);

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getSingleIdentifierFieldName($meta)
    {

        return $meta->getSingleIdentifierFieldName();

    }

    /**
     *
     * {@inheritdoc}
     */
    public function recomputeSingleObjectChangeSet($uow, $meta, $object)
    {

        $uow->recomputeSingleEntityChangeSet($meta, $object);

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getScheduledObjectUpdates($uow)
    {

        return $uow->getScheduledEntityUpdates();

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getScheduledObjectInsertions($uow)
    {

        return $uow->getScheduledEntityInsertions();

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getScheduledObjectDeletions($uow)
    {

        return $uow->getScheduledEntityDeletions();

    }

    /**
     *
     * {@inheritdoc}
     */
    public function setOriginalObjectProperty($uow, $oid, $property, $value)
    {

        $uow->setOriginalEntityProperty($oid, $property, $value);

    }

    /**
     *
     * {@inheritdoc}
     */
    public function clearObjectChangeSet($uow, $oid)
    {

        $uow->clearEntityChangeSet($oid);

    }

    /**
     * Creates a ORM specific LifecycleEventArgs.
     *
     * @param object $document
     * @param DocumentManager $documentManager
     *
     * @return LifecycleEventArgs
     */
    public function createLifecycleEventArgsInstance($document, $documentManager)
    {

        return new LifecycleEventArgs($document, $documentManager);

    }

}
