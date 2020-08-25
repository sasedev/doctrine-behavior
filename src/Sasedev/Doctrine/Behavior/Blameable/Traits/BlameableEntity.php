<?php
namespace Sasedev\Doctrine\Behavior\Blameable\Traits;

use Doctrine\ORM\Mapping as ORM;
use Sasedev\Doctrine\Behavior\Mapping\Annotation as Sasedev;

/**
 * Blameable Trait, usable with PHP >= 5.4
 *
 * @author David Buchmann <mail@davidbu.ch>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
trait BlameableEntity
{

    /**
     *
     * @var string
     * @Sasedev\Blameable(on="create")
     * @ORM\Column(nullable=true)
     */
    protected $createdBy;

    /**
     *
     * @var string
     * @Sasedev\Blameable(on="update")
     * @ORM\Column(nullable=true)
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
