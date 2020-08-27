<?php
namespace Sasedev\Doctrine\Behavior\Translatable\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoODM;

/**
 * Sasedev\Doctrine\Behavior\Translatable\Document\Translation
 *
 * @MongoODM\Document(repositoryClass="Sasedev\Doctrine\Behavior\Translatable\Document\Repository\TranslationRepository")
 * @MongoODM\UniqueIndex(name="lookup_unique_idx", keys={
 *         "locale" = "asc",
 *         "object_class" = "asc",
 *         "foreign_key" = "asc",
 *         "field" = "asc"
 * })
 * @MongoODM\Index(name="translations_lookup_idx", keys={
 *      "locale" = "asc",
 *      "object_class" = "asc",
 *      "foreign_key" = "asc"
 * })
 */
class Translation extends MappedSuperclass\AbstractTranslation
{

/**
 * All required columns are mapped through inherited superclass
 */
}
