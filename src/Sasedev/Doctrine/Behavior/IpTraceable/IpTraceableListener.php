<?php
namespace Sasedev\Doctrine\Behavior\IpTraceable;

use Sasedev\Doctrine\Behavior\AbstractTrackingListener;
use Sasedev\Doctrine\Behavior\Exception\InvalidArgumentException;
use Sasedev\Doctrine\Behavior\Mapping\Event\AdapterInterface;

/**
 * The IpTraceable listener handles the update of
 * IPs on creation and update.
 *
 * @author Pierre-Charles Bertineau <pc.bertineau@alterphp.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class IpTraceableListener extends AbstractTrackingListener
{

    /**
     *
     * @var string|null
     */
    protected $ip;

    /**
     * Get the ipValue value to set on a ip field
     *
     * @param object $meta
     * @param string $field
     * @param AdapterInterface $eventAdapter
     *
     * @return string
     */
    public function getFieldValue($meta, $field, $eventAdapter): ?string
    {

        return $this->ip;

    }

    /**
     * Set a ip value to return
     *
     * @param string $ip
     * @throws InvalidArgumentException
     */
    public function setIpValue($ip = null)
    {

        if (isset($ip) && filter_var($ip, FILTER_VALIDATE_IP) === false)
        {
            throw new InvalidArgumentException("ip address is not valid $ip");
        }

        $this->ip = $ip;

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
