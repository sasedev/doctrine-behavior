<?php
namespace Sasedev\Doctrine\Behavior\IpTraceable\Mapping\Event\Adapter;

use Sasedev\Doctrine\Behavior\Mapping\Event\Adapter\ORM as BaseAdapterORM;
use Sasedev\Doctrine\Behavior\IpTraceable\Mapping\Event\IpTraceableAdapter;

/**
 * Doctrine event adapter for ORM adapted
 * for IpTraceable behavior
 *
 * @author Pierre-Charles Bertineau <pc.bertineau@alterphp.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class ORM extends BaseAdapterORM implements IpTraceableAdapter
{

}
