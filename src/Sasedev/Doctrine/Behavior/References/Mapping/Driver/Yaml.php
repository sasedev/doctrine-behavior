<?php
namespace Sasedev\Doctrine\Behavior\References\Mapping\Driver;

use Sasedev\Doctrine\Behavior\Mapping\Driver\File;
use Sasedev\Doctrine\Behavior\Mapping\Driver;
use Sasedev\Doctrine\Behavior\Exception\InvalidMappingException;

/**
 *
 * @author Gonzalo Vilaseca <gonzalo.vilaseca@reiss.com>
 */
class Yaml extends File implements Driver
{

    /**
     * File extension
     *
     * @var string
     */
    protected $_extension = '.dcm.yml';

    private $validReferences = [
        'referenceOne' => [],
        'referenceMany' => [],
        'referenceManyEmbed' => []
    ];

    /**
     *
     * {@inheritdoc}
     */
    public function readExtendedMetadata($meta, array &$config)
    {

        $mapping = $this->_getMapping($meta->name);

        if (isset($mapping['sasedev']) && isset($mapping['sasedev']['reference']))
        {

            foreach ($mapping['sasedev']['reference'] as $field => $fieldMapping)
            {
                $reference = $fieldMapping['reference'];

                if (! \in_array($reference, array_keys($this->validReferences)))
                {
                    throw new InvalidMappingException($reference . ' is not a valid reference, valid references are: ' . implode(', ', array_keys($this->validReferences)));
                }

                $config[$reference][$field] = [
                    'field' => $field,
                    'type' => $fieldMapping['type'],
                    'class' => $fieldMapping['class']
                ];

                if (array_key_exists('mappedBy', $fieldMapping))
                {
                    $config[$reference][$field]['mappedBy'] = $fieldMapping['mappedBy'];
                }

                if (array_key_exists('identifier', $fieldMapping))
                {
                    $config[$reference][$field]['identifier'] = $fieldMapping['identifier'];
                }

                if (array_key_exists('inversedBy', $fieldMapping))
                {
                    $config[$reference][$field]['inversedBy'] = $fieldMapping['inversedBy'];
                }
            }
        }
        $config = array_merge($this->validReferences, $config);

    }

    /**
     *
     * {@inheritdoc}
     */
    protected function _loadMappingFile($file)
    {

        return \Symfony\Component\Yaml\Yaml::parse($file);

    }

}
