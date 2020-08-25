<?php
namespace Sasedev\Doctrine\Behavior\References\Mapping\Event;

use Doctrine\Persistence\ObjectManager;
use Sasedev\Doctrine\Behavior\Mapping\Event\AdapterInterface;

/**
 * Doctrine event adapter interface for References behavior
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @author Bulat Shakirzyanov <mallluhuct@gmail.com>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface ReferencesAdapter extends AdapterInterface
{

    /**
     * Gets the identifier of the given object using the passed ObjectManager.
     *
     * @param ObjectManager $om
     * @param object $object
     * @param bool $single
     *
     * @return array|string|int $id - array or single identifier
     */
    public function getIdentifier($om, $object, $single = true);

    /**
     * Gets a single reference for the given ObjectManager, class and identifier.
     *
     * @param ObjectManager $om
     * @param string $class
     * @param array|string|int $identifier
     */
    public function getSingleReference($om, $class, $identifier);

    /**
     * Extracts identifiers from object or proxy.
     *
     * @param ObjectManager $om
     * @param object $object
     * @param bool $single
     *
     * @return array|string|int - array or single identifier
     */
    public function extractIdentifier($om, $object, $single = true);

}