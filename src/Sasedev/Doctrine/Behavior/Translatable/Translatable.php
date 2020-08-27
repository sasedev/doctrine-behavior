<?php
namespace Sasedev\Doctrine\Behavior\Translatable;

/**
 * This interface is not necessary but can be implemented for
 * Entities which in some cases needs to be identified as
 * Translatable
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface Translatable
{

    // use now annotations instead of predefined methods, this interface is not necessary

/**
 *
 * @sasedev:TranslationEntity
 * to specify custom translation class use
 * class annotation @sasedev:TranslationEntity(class="your\class")
 */

/**
 *
 * @sasedev:Translatable
 * to mark the field as translatable,
 * these fields will be translated
 */

/**
 *
 * @sasedev:Locale OR @sasedev:Language
 * to mark the field as locale used to override global
 * locale settings from TranslatableListener
 */
}
