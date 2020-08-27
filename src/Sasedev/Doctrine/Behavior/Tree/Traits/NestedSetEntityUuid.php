<?php
namespace Sasedev\Doctrine\Behavior\Tree\Traits;

use Doctrine\ORM\Mapping as ORM;
use Sasedev\Doctrine\Behavior\Mapping\Annotation as Sasedev;

/**
 * NestedSet Trait with UUid, usable with PHP >= 5.4
 *
 * @author Benjamin Lazarecki <benjamin.lazarecki@sensiolabs.com>
 *
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
trait NestedSetEntityUuid
{
    use NestedSetEntity;

    /**
     *
     * @var string
     * @Sasedev\TreeRoot
     * @ORM\Column(name="root", type="string", nullable=true)
     */
    private $root;

}
