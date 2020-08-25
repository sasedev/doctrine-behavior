<?php
namespace Sasedev\Doctrine\Behavior\Loggable\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoODM;

/**
 * Sasedev\Doctrine\Behavior\Loggable\Document\LogEntry
 *
 * @MongoODM\Document(
 *     repositoryClass="Sasedev\Doctrine\Behavior\Loggable\Document\Repository\LogEntryRepository",
 *     indexes={
 *         @MongoODM\Index(keys={"objectId"="asc", "objectClass"="asc", "version"="asc"}),
 *         @MongoODM\Index(keys={"loggedAt"="asc"}),
 *         @MongoODM\Index(keys={"objectClass"="asc"}),
 *         @MongoODM\Index(keys={"username"="asc"})
 *     }
 * )
 */
class LogEntry extends MappedSuperclass\AbstractLogEntry
{

/**
 * All required columns are mapped through inherited superclass
 */
}
