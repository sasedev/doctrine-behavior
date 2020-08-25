<?php

namespace Sasedev\Doctrine\Behavior\Translatable\Mapping\Driver;

use Sasedev\Doctrine\Behavior\Mapping\Driver\AbstractAnnotationDriver;
use Sasedev\Doctrine\Behavior\Exception\InvalidMappingException;

/**
 * This is an annotation mapping driver for Translatable
 * behavioral extension. Used for extraction of extended
 * metadata from Annotations specifically for Translatable
 * extension.
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Annotation extends AbstractAnnotationDriver
{
    /**
     * Annotation to identity translation entity to be used for translation storage
     */
    const ENTITY_CLASS = 'Sasedev\\Doctrine\\Behavior\\Mapping\\Annotation\\TranslationEntity';

    /**
     * Annotation to identify field as translatable
     */
    const TRANSLATABLE = 'Sasedev\\Doctrine\\Behavior\\Mapping\\Annotation\\Translatable';

    /**
     * Annotation to identify field which can store used locale or language
     * alias is LANGUAGE
     */
    const LOCALE = 'Sasedev\\Doctrine\\Behavior\\Mapping\\Annotation\\Locale';

    /**
     * Annotation to identify field which can store used locale or language
     * alias is LOCALE
     */
    const LANGUAGE = 'Sasedev\\Doctrine\\Behavior\\Mapping\\Annotation\\Language';

    /**
     * {@inheritDoc}
     */
    public function readExtendedMetadata($meta, array &$config)
    {
        $class = $this->getMetaReflectionClass($meta);
        // class annotations
        if ($annot = $this->reader->getClassAnnotation($class, self::ENTITY_CLASS)) {
            if (!$cl = $this->getRelatedClassName($meta, $annot->class)) {
                throw new InvalidMappingException("Translation class: {$annot->class} does not exist.");
            }
            $config['translationClass'] = $cl;
        }

        // property annotations
        foreach ($class->getProperties() as $property) {
            if ($meta->isMappedSuperclass && !$property->isPrivate() ||
                $meta->isInheritedField($property->name) ||
                isset($meta->associationMappings[$property->name]['inherited'])
            ) {
                continue;
            }
            // translatable property
            if ($translatable = $this->reader->getPropertyAnnotation($property, self::TRANSLATABLE)) {
                $field = $property->getName();
                if (!$meta->hasField($field)) {
                    throw new InvalidMappingException("Unable to find translatable [{$field}] as mapped property in entity - {$meta->name}");
                }
                // fields cannot be overrided and throws mapping exception
                $config['fields'][] = $field;
                if (isset($translatable->fallback)) {
                    $config['fallback'][$field] = $translatable->fallback;
                }
            }
            // locale property
            if ($this->reader->getPropertyAnnotation($property, self::LOCALE)) {
                $field = $property->getName();
                if ($meta->hasField($field)) {
                    throw new InvalidMappingException("Locale field [{$field}] should not be mapped as column property in entity - {$meta->name}, since it makes no sense");
                }
                $config['locale'] = $field;
            } elseif ($this->reader->getPropertyAnnotation($property, self::LANGUAGE)) {
                $field = $property->getName();
                if ($meta->hasField($field)) {
                    throw new InvalidMappingException("Language field [{$field}] should not be mapped as column property in entity - {$meta->name}, since it makes no sense");
                }
                $config['locale'] = $field;
            }
        }

        // Embedded entity
        if (property_exists($meta, 'embeddedClasses') && $meta->embeddedClasses) {
            foreach ($meta->embeddedClasses as $propertyName => $embeddedClassInfo) {
                $embeddedClass = new \ReflectionClass($embeddedClassInfo['class']);
                foreach ($embeddedClass->getProperties() as $embeddedProperty) {
                    if ($translatable = $this->reader->getPropertyAnnotation($embeddedProperty, self::TRANSLATABLE)) {
                        $field = $propertyName . '.' . $embeddedProperty->getName();

                        $config['fields'][] = $field;
                        if (isset($translatable->fallback)) {
                            $config['fallback'][$field] = $translatable->fallback;
                        }
                    }
                }
            }
        }

        if (!$meta->isMappedSuperclass && $config) {
            if (is_array($meta->identifier) && count($meta->identifier) > 1) {
                throw new InvalidMappingException("Translatable does not support composite identifiers in class - {$meta->name}");
            }
        }
    }
}
