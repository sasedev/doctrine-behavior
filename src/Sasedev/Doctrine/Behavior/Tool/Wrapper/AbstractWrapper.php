<?php
namespace Sasedev\Doctrine\Behavior\Tool\Wrapper;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ObjectManager;
use Sasedev\Doctrine\Behavior\Tool\WrapperInterface;
use Sasedev\Doctrine\Behavior\Exception\UnsupportedObjectManagerException;

/**
 * Wraps entity or proxy for more convenient
 * manipulation
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
abstract class AbstractWrapper implements WrapperInterface
{

    /**
     * Object metadata
     *
     * @var object
     */
    protected $meta;

    /**
     * Wrapped object
     *
     * @var object
     */
    protected $object;

    /**
     * Object manager instance
     *
     * @var ObjectManager
     */
    protected $om;

    /**
     * List of wrapped object references
     *
     * @var array
     */
    private static $wrappedObjectReferences;

    /**
     * Wrap object factory method
     *
     * @param object $object
     * @param ObjectManager $om
     *
     * @throws \Sasedev\Doctrine\Behavior\Exception\UnsupportedObjectManagerException
     *
     * @return \Sasedev\Doctrine\Behavior\Tool\WrapperInterface
     */
    public static function wrap($object, ObjectManager $om)
    {

        if ($om instanceof EntityManagerInterface)
        {
            return new EntityWrapper($object, $om);
        }
        elseif ($om instanceof DocumentManager)
        {
            return new MongoDocumentWrapper($object, $om);
        }
        throw new UnsupportedObjectManagerException('Given object manager is not managed by wrapper');

    }

    public static function clear()
    {

        self::$wrappedObjectReferences = [];

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getObject()
    {

        return $this->object;

    }

    /**
     *
     * {@inheritdoc}
     */
    public function getMetadata()
    {

        return $this->meta;

    }

    /**
     *
     * {@inheritdoc}
     */
    public function populate(array $data)
    {

        foreach ($data as $field => $value)
        {
            $this->setPropertyValue($field, $value);
        }

        return $this;

    }

}
