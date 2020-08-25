<?php
namespace Sasedev\Doctrine\Behavior\Uploadable\Mapping\Driver;

use Sasedev\Doctrine\Behavior\Mapping\Driver\AbstractAnnotationDriver;
use Sasedev\Doctrine\Behavior\Uploadable\Mapping\Validator;

/**
 * This is an annotation mapping driver for Uploadable
 * behavioral extension.
 * Used for extraction of extended
 * metadata from Annotations specifically for Uploadable
 * extension.
 *
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Annotation extends AbstractAnnotationDriver
{

    /**
     * Annotation to define that this object is uploadable
     */
    const UPLOADABLE = 'Sasedev\\Doctrine\\Behavior\\Mapping\\Annotation\\Uploadable';

    const UPLOADABLE_FILE_MIME_TYPE = 'Sasedev\\Doctrine\\Behavior\\Mapping\\Annotation\\UploadableFileMimeType';

    const UPLOADABLE_FILE_NAME = 'Sasedev\\Doctrine\\Behavior\\Mapping\\Annotation\\UploadableFileName';

    const UPLOADABLE_FILE_PATH = 'Sasedev\\Doctrine\\Behavior\\Mapping\\Annotation\\UploadableFilePath';

    const UPLOADABLE_FILE_SIZE = 'Sasedev\\Doctrine\\Behavior\\Mapping\\Annotation\\UploadableFileSize';

    /**
     *
     * {@inheritdoc}
     */
    public function readExtendedMetadata($meta, array &$config)
    {

        $class = $this->getMetaReflectionClass($meta);

        // class annotations
        if ($annot = $this->reader->getClassAnnotation($class, self::UPLOADABLE))
        {
            $config['uploadable'] = true;
            $config['allowOverwrite'] = $annot->allowOverwrite;
            $config['appendNumber'] = $annot->appendNumber;
            $config['path'] = $annot->path;
            $config['pathMethod'] = $annot->pathMethod;
            $config['fileMimeTypeField'] = false;
            $config['fileNameField'] = false;
            $config['filePathField'] = false;
            $config['fileSizeField'] = false;
            $config['callback'] = $annot->callback;
            $config['filenameGenerator'] = $annot->filenameGenerator;
            $config['maxSize'] = (double) $annot->maxSize;
            $config['allowedTypes'] = $annot->allowedTypes;
            $config['disallowedTypes'] = $annot->disallowedTypes;

            foreach ($class->getProperties() as $prop)
            {
                if ($this->reader->getPropertyAnnotation($prop, self::UPLOADABLE_FILE_MIME_TYPE))
                {
                    $config['fileMimeTypeField'] = $prop->getName();
                }

                if ($this->reader->getPropertyAnnotation($prop, self::UPLOADABLE_FILE_NAME))
                {
                    $config['fileNameField'] = $prop->getName();
                }

                if ($this->reader->getPropertyAnnotation($prop, self::UPLOADABLE_FILE_PATH))
                {
                    $config['filePathField'] = $prop->getName();
                }

                if ($this->reader->getPropertyAnnotation($prop, self::UPLOADABLE_FILE_SIZE))
                {
                    $config['fileSizeField'] = $prop->getName();
                }
            }

            Validator::validateConfiguration($meta, $config);
        }

        /*
         * // Code in case we need to identify entities which are not Uploadables, but have associations
         * // with other Uploadable entities
         * } else {
         * // We need to check if this class has a relation with Uploadable entities
         * $associations = $meta->getAssociationMappings();
         * foreach ($associations as $field => $association) {
         * $refl = new \ReflectionClass($association['targetEntity']);
         * if ($annot = $this->reader->getClassAnnotation($refl, self::UPLOADABLE)) {
         * $config['hasUploadables'] = true;
         * if (!isset($config['uploadables'])) {
         * $config['uploadables'] = [];
         * }
         * $config['uploadables'][] = [
         * 'class' => $association['targetEntity'],
         * 'property' => $association['fieldName']
         * ];
         * }
         * }
         * }
         */

        $this->validateFullMetadata($meta, $config);

    }

}
