<?php
namespace Sasedev\Doctrine\Behavior\SoftDeleteable\Traits;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * SoftDeletable Trait, usable with PHP >= 5.4
 *
 * @author Wesley van Opdorp <wesley.van.opdorp@freshheads.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
trait SoftDeleteableDocument
{

    /**
     *
     * @var \DateTime
     * @ODM\Field(type="date")
     */
    protected $deletedAt;

    /**
     * Sets deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return $this
     */
    public function setDeletedAt(\DateTime $deletedAt = null)
    {

        $this->deletedAt = $deletedAt;

        return $this;

    }

    /**
     * Returns deletedAt.
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {

        return $this->deletedAt;

    }

    /**
     * Is deleted?
     *
     * @return bool
     */
    public function isDeleted()
    {

        return null !== $this->deletedAt;

    }

}
