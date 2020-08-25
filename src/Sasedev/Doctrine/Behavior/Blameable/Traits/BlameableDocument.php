<?php
namespace Sasedev\Doctrine\Behavior\Blameable\Traits;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sasedev\Doctrine\Behavior\Mapping\Annotation as Sasedev;

/**
 * Blameable Trait, usable with PHP >= 5.4
 *
 * @author David Buchmann <mail@davidbu.ch>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
trait BlameableDocument
{

    /**
     *
     * @var string
     * @Sasedev\Blameable(on="create")
     * @ODM\Field(type="string")
     */
    protected $createdBy;

    /**
     *
     * @var string
     * @Sasedev\Blameable(on="update")
     * @ODM\Field(type="string")
     */
    protected $updatedBy;

    /**
     * Sets createdBy.
     *
     * @param string $createdBy
     * @return $this
     */
    public function setCreatedBy($createdBy)
    {

        $this->createdBy = $createdBy;

        return $this;

    }

    /**
     * Returns createdBy.
     *
     * @return string
     */
    public function getCreatedBy()
    {

        return $this->createdBy;

    }

    /**
     * Sets updatedBy.
     *
     * @param string $updatedBy
     * @return $this
     */
    public function setUpdatedBy($updatedBy)
    {

        $this->updatedBy = $updatedBy;

        return $this;

    }

    /**
     * Returns updatedBy.
     *
     * @return string
     */
    public function getUpdatedBy()
    {

        return $this->updatedBy;

    }

}
