<?php
namespace Sasedev\Doctrine\Behavior\Timestampable;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Sasedev\Doctrine\Behavior\AbstractTrackingListener;
use Sasedev\Doctrine\Behavior\Timestampable\Mapping\Event\TimestampableAdapter;

/**
 * The Timestampable listener handles the update of
 * dates on creation and update.
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class TimestampableListener extends AbstractTrackingListener
{

    /**
     *
     * @param ClassMetadata $meta
     * @param string $field
     * @param TimestampableAdapter $eventAdapter
     * @return mixed
     */
    protected function getFieldValue($meta, $field, $eventAdapter)
    {

        return $eventAdapter->getDateValue($meta, $field);

    }

    /**
     *
     * {@inheritdoc}
     */
    protected function getNamespace()
    {

        return __NAMESPACE__;

    }

}
