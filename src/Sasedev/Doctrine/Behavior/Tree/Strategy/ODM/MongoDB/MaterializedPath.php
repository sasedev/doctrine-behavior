<?php
namespace Sasedev\Doctrine\Behavior\Tree\Strategy\ODM\MongoDB;

use Doctrine\Persistence\ObjectManager;
use Sasedev\Doctrine\Behavior\Tree\Strategy\AbstractMaterializedPath;
use Sasedev\Doctrine\Behavior\Mapping\Event\AdapterInterface;
use Sasedev\Doctrine\Behavior\Tool\Wrapper\AbstractWrapper;

/**
 * This strategy makes tree using materialized path strategy
 *
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class MaterializedPath extends AbstractMaterializedPath
{

    /**
     *
     * {@inheritdoc}
     */
    public function removeNode($om, $meta, $config, $node)
    {

        $uow = $om->getUnitOfWork();
        $wrapped = AbstractWrapper::wrap($node, $om);

        // Remove node's children
        $results = $om->createQueryBuilder()
            ->find($meta->name)
            ->field($config['path'])
            ->equals(new \MongoDB\BSON\Regex('/^' . preg_quote($wrapped->getPropertyValue($config['path'])) . '.?+/'))
            ->getQuery()
            ->execute();

        foreach ($results as $node)
        {
            $uow->scheduleForDelete($node);
        }

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getChildren($om, $meta, $config, $originalPath)
    {

        return $om->createQueryBuilder()
            ->find($meta->name)
            ->field($config['path'])
            ->equals(new \MongoDB\BSON\Regex('/^' . preg_quote($originalPath) . '.+/'))
            ->sort($config['path'], 'asc')
            ->
        // This may save some calls to updateNode
        getQuery()
            ->execute();

    }

    /**
     *
     * {@inheritdoc}
     */
    protected function lockTrees(ObjectManager $om, AdapterInterface $ea)
    {

        $uow = $om->getUnitOfWork();

        // foreach ($this->rootsOfTreesWhichNeedsLocking as $oid => $root) {
        foreach ($this->rootsOfTreesWhichNeedsLocking as $root)
        {
            $meta = $om->getClassMetadata(get_class($root));
            $config = $this->listener->getConfiguration($om, $meta->name);
            $lockTimeProp = $meta->getReflectionProperty($config['lock_time']);
            $lockTimeProp->setAccessible(true);
            $lockTimeValue = new \MongoDB\BSON\UTCDatetime();
            $lockTimeProp->setValue($root, $lockTimeValue);
            /*
             * $changes = [
             * $config['lock_time'] => [
             * null,
             * $lockTimeValue
             * ]
             * ];
             */

            $ea->recomputeSingleObjectChangeSet($uow, $meta, $root);
        }

    }

    /**
     *
     * {@inheritdoc}
     */
    protected function releaseTreeLocks(ObjectManager $om, AdapterInterface $ea)
    {

        $uow = $om->getUnitOfWork();

        foreach ($this->rootsOfTreesWhichNeedsLocking as $oid => $root)
        {
            $meta = $om->getClassMetadata(get_class($root));
            $config = $this->listener->getConfiguration($om, $meta->name);
            $lockTimeProp = $meta->getReflectionProperty($config['lock_time']);
            $lockTimeProp->setAccessible(true);
            $lockTimeValue = null;
            $lockTimeProp->setValue($root, $lockTimeValue);
            /*
             * $changes = [
             * $config['lock_time'] => [
             * null,
             * null
             * ]
             * ];
             */

            $ea->recomputeSingleObjectChangeSet($uow, $meta, $root);

            unset($this->rootsOfTreesWhichNeedsLocking[$oid]);
        }

    }

}
