<?php
namespace Sasedev\Doctrine\Behavior\Loggable\Document\Repository;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Doctrine\ODM\MongoDB\Iterator\Iterator;
use Sasedev\Doctrine\Behavior\Exception\RuntimeException;
use Sasedev\Doctrine\Behavior\Exception\UnexpectedValueException;
use Sasedev\Doctrine\Behavior\Loggable\Document\LogEntry;
use Sasedev\Doctrine\Behavior\Loggable\LoggableListener;
use Sasedev\Doctrine\Behavior\Tool\Wrapper\MongoDocumentWrapper;

/**
 * The LogEntryRepository has some useful functions
 * to interact with log entries.
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class LogEntryRepository extends DocumentRepository
{

    /**
     * Currently used loggable listener
     *
     * @var LoggableListener
     */
    private $listener;

    /**
     * Loads all log entries for the
     * given $document
     *
     * @param object $document
     *
     * @return LogEntry[]
     */
    public function getLogEntries($document)
    {

        $wrapped = new MongoDocumentWrapper($document, $this->dm);
        $objectId = $wrapped->getIdentifier();

        $qb = $this->createQueryBuilder();
        $qb->field('objectId')
            ->equals($objectId);
        $qb->field('objectClass')
            ->equals($wrapped->getMetadata()->name);
        $qb->sort('version', 'DESC');
        $q = $qb->getQuery();

        $result = $q->execute();
        if ($result instanceof Iterator)
        {
            $result = $result->toArray();
        }
        return $result;

    }

    /**
     * Reverts given $document to $revision by
     * restoring all fields from that $revision.
     * After this operation you will need to
     * persist and flush the $document.
     *
     * @param object $document
     * @param integer $version
     *
     * @throws UnexpectedValueException
     *
     * @return void
     */
    public function revert($document, $version = 1)
    {

        $wrapped = new MongoDocumentWrapper($document, $this->dm);
        $objectMeta = $wrapped->getMetadata();
        $objectId = $wrapped->getIdentifier();

        $qb = $this->createQueryBuilder();
        $qb->field('objectId')
            ->equals($objectId);
        $qb->field('objectClass')
            ->equals($objectMeta->name);
        $qb->field('version')
            ->lte(intval($version));
        $qb->sort('version', 'ASC');
        $q = $qb->getQuery();

        $logs = $q->execute();
        if ($logs instanceof Iterator)
        {
            $logs = $logs->toArray();
        }
        if ($logs)
        {
            $data = [];
            while (($log = array_shift($logs)))
            {
                $data = array_merge($data, $log->getData());
            }
            $this->fillDocument($document, $data, $objectMeta);
        }
        else
        {
            throw new UnexpectedValueException('Count not find any log entries under version: ' . $version);
        }

    }

    /**
     * Fills a documents versioned fields with data
     *
     * @param object $document
     * @param array $data
     */
    protected function fillDocument($document, array $data)
    {

        $wrapped = new MongoDocumentWrapper($document, $this->dm);
        $objectMeta = $wrapped->getMetadata();
        $config = $this->getLoggableListener()
            ->getConfiguration($this->dm, $objectMeta->name);
        $fields = $config['versioned'];
        foreach ($data as $field => $value)
        {
            if (! \in_array($field, $fields))
            {
                continue;
            }
            $mapping = $objectMeta->getFieldMapping($field);
            // Fill the embedded document
            if ($wrapped->isEmbeddedAssociation($field))
            {
                if (! empty($value))
                {
                    $embeddedMetadata = $this->dm->getClassMetadata($mapping['targetDocument']);
                    $document = $embeddedMetadata->newInstance();
                    $this->fillDocument($document, $value);
                    $value = $document;
                }
            }
            elseif ($objectMeta->isSingleValuedAssociation($field))
            {
                $value = $value ? $this->dm->getReference($mapping['targetDocument'], $value) : null;
            }
            $wrapped->setPropertyValue($field, $value);
            unset($fields[$field]);
        }

        /*
         * if (count($fields)) {
         * throw new UnexpectedValueException('Cound not fully revert the document to version: '.$version);
         * }
         */
    }

    /**
     * Get the currently used LoggableListener
     *
     * @throws RuntimeException - if listener is not found
     *
     * @return LoggableListener
     */
    private function getLoggableListener()
    {

        if (\is_null($this->listener))
        {
            // foreach ($this->dm->getEventManager()->getListeners() as $event => $listeners) {
            foreach ($this->dm->getEventManager()
                ->getListeners() as $listeners)
            {
                foreach ($listeners as $listener)
                {
                    // foreach ($listeners as $hash => $listener) {
                    if ($listener instanceof LoggableListener)
                    {
                        $this->listener = $listener;
                        break;
                    }
                }
                if ($this->listener)
                {
                    break;
                }
            }

            if (is_null($this->listener))
            {
                throw new RuntimeException('The loggable listener could not be found');
            }
        }
        return $this->listener;

    }

}
