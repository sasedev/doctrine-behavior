<?php

namespace Sasedev\Doctrine\Behavior\Tree\Mapping\Event\Adapter;

use Sasedev\Doctrine\Behavior\Mapping\Event\Adapter\ORM as BaseAdapterORM;
use Sasedev\Doctrine\Behavior\Tree\Mapping\Event\TreeAdapter;

/**
 * Doctrine event adapter for ORM adapted
 * for Tree behavior
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class ORM extends BaseAdapterORM implements TreeAdapter
{
    // Nothing specific yet
}
