<?php
namespace Sasedev\Doctrine\Behavior\Timestampable\Mapping\Event;

use Sasedev\Doctrine\Behavior\Mapping\Event\AdapterInterface;

/**
 * Doctrine event adapter interface
 * for Timestampable behavior
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface TimestampableAdapter extends AdapterInterface
{

    /**
     * Get the date value
     *
     * @param object $meta
     * @param string $field
     *
     * @return mixed
     */
    public function getDateValue($meta, $field);

}
