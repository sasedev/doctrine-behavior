<?php
namespace Sasedev\Doctrine\Behavior\Loggable\Mapping\Event;

use Sasedev\Doctrine\Behavior\Mapping\Event\AdapterInterface;

/**
 * Doctrine event adapter interface
 * for Loggable behavior
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface LoggableAdapter extends AdapterInterface
{

    /**
     * Get default LogEntry class used to store the logs
     *
     * @return string
     */
    public function getDefaultLogEntryClass();

    /**
     * Checks whether an id should be generated post insert
     *
     * @return boolean
     */
    public function isPostInsertGenerator($meta);

    /**
     * Get new version number
     *
     * @param object $meta
     * @param object $object
     *
     * @return integer
     */
    public function getNewVersion($meta, $object);

}
