<?php
namespace Sasedev\Doctrine\Behavior\Mapping\Event\Adapter;

use Sasedev\Doctrine\Behavior\Mapping\Event\AdapterInterface;
use Sasedev\Doctrine\Behavior\Exception\RuntimeException;
use Doctrine\Common\EventArgs;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;

/**
 * Doctrine event adapter for ODM specific
 * event arguments
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class ODM implements AdapterInterface
{

    /**
     *
     * @var EventArgs
     */
    private $args;

    /**
     *
     * @var DocumentManager
     */
    private $dm;

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

        return 'Document';

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getManagerName()
    {

        return 'ODM';

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getRootObjectClass($meta)
    {

        return $meta->rootDocumentName;

    }

    /**
     * Set the document manager
     *
     * @param DocumentManager $dm
     */
    public function setDocumentManager(DocumentManager $dm)
    {

        $this->dm = $dm;

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getObjectManager()
    {

        if (! is_null($this->dm))
        {
            return $this->dm;
        }

        return $this->__call('getDocumentManager', []);

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getObjectState($uow, $object)
    {

        return $uow->getDocumentState($object);

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
     *
     * {@inheritdoc}
     */
    public function getObjectChangeSet($uow, $object)
    {

        return $uow->getDocumentChangeSet($object);

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getSingleIdentifierFieldName($meta)
    {

        return $meta->identifier;

    }

    /**
     *
     * {@inheritdoc}
     */
    public function recomputeSingleObjectChangeSet($uow, $meta, $object)
    {

        $uow->recomputeSingleDocumentChangeSet($meta, $object);

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getScheduledObjectUpdates($uow)
    {

        $updates = $uow->getScheduledDocumentUpdates();
        $upserts = $uow->getScheduledDocumentUpserts();

        return array_merge($updates, $upserts);

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getScheduledObjectInsertions($uow)
    {

        return $uow->getScheduledDocumentInsertions();

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getScheduledObjectDeletions($uow)
    {

        return $uow->getScheduledDocumentDeletions();

    }

    /**
     *
     * {@inheritdoc}
     */
    public function setOriginalObjectProperty($uow, $oid, $property, $value)
    {

        $uow->setOriginalDocumentProperty($oid, $property, $value);

    }

    /**
     *
     * {@inheritdoc}
     */
    public function clearObjectChangeSet($uow, $oid)
    {

        $uow->clearDocumentChangeSet($oid);

    }

    /**
     * Creates a ODM specific LifecycleEventArgs.
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
