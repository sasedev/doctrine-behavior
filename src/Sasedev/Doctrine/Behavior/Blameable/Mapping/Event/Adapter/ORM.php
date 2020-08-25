<?php
namespace Sasedev\Doctrine\Behavior\Blameable\Mapping\Event\Adapter;

use Sasedev\Doctrine\Behavior\Mapping\Event\Adapter\ORM as BaseAdapterORM;
use Sasedev\Doctrine\Behavior\Blameable\Mapping\Event\BlameableAdapter;

/**
 * Doctrine event adapter for ORM adapted
 * for Blameable behavior.
 *
 * @author David Buchmann <mail@davidbu.ch>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class ORM extends BaseAdapterORM implements BlameableAdapter
{

}
