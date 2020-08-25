<?php

namespace Sasedev\Doctrine\Behavior\Tree\Traits;

use Doctrine\ORM\Mapping as ORM;
use Sasedev\Doctrine\Behavior\Mapping\Annotation as Sasedev;

/**
 * NestedSet Trait, usable with PHP >= 5.4
 *
 * @author Renaat De Muynck <renaat.demuynck@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
trait NestedSetEntity
{
    /**
     * @var integer
     * @Sasedev\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @var integer
     * @Sasedev\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $level;

    /**
     * @var integer
     * @Sasedev\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $left;

    /**
     * @var integer
     * @Sasedev\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $right;
}
