<?php
namespace Sasedev\Doctrine\Behavior\SoftDeleteable\Filter\ODM;

use Doctrine\ODM\MongoDB\Query\Filter\BsonFilter;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Sasedev\Doctrine\Behavior\SoftDeleteable\SoftDeleteableListener;

class SoftDeleteableFilter extends BsonFilter
{

    protected $listener;

    protected $documentManager;

    protected $disabled = [];

    /**
     * Gets the criteria part to add to a query.
     *
     * @param ClassMetadata $targetEntity
     *
     * @return array The criteria array, if there is available, empty array otherwise
     */
    public function addFilterCriteria(ClassMetadata $targetEntity)
    {

        $class = $targetEntity->getName();
        if (array_key_exists($class, $this->disabled) && $this->disabled[$class] === true)
        {
            return [];
        }
        elseif (array_key_exists($targetEntity->rootDocumentName, $this->disabled) && $this->disabled[$targetEntity->rootDocumentName] === true)
        {
            return [];
        }

        $config = $this->getListener()
            ->getConfiguration($this->getDocumentManager(), $targetEntity->name);

        if (! isset($config['softDeleteable']) || ! $config['softDeleteable'])
        {
            return [];
        }

        $column = $targetEntity->fieldMappings[$config['fieldName']];

        if (isset($config['timeAware']) && $config['timeAware'])
        {
            return [
                '$or' => [
                    [
                        $column['fieldName'] => null
                    ],
                    [
                        $column['fieldName'] => [
                            '$gt' => new \DateTime('now')
                        ]
                    ]
                ]
            ];
        }

        return [
            $column['fieldName'] => null
        ];

    }

    protected function getListener()
    {

        if ($this->listener === null)
        {
            $em = $this->getDocumentManager();
            $evm = $em->getEventManager();

            foreach ($evm->getListeners() as $listeners)
            {
                foreach ($listeners as $listener)
                {
                    if ($listener instanceof SoftDeleteableListener)
                    {
                        $this->listener = $listener;

                        break 2;
                    }
                }
            }

            if ($this->listener === null)
            {
                throw new \RuntimeException('Listener "SoftDeleteableListener" was not added to the EventManager!');
            }
        }

        return $this->listener;

    }

    protected function getDocumentManager()
    {

        if ($this->documentManager === null)
        {
            $refl = new \ReflectionProperty('Doctrine\ODM\MongoDB\Query\Filter\BsonFilter', 'dm');
            $refl->setAccessible(true);
            $this->documentManager = $refl->getValue($this);
        }

        return $this->documentManager;

    }

    public function disableForDocument($class)
    {

        $this->disabled[$class] = true;

    }

    public function enableForDocument($class)
    {

        $this->disabled[$class] = false;

    }

}