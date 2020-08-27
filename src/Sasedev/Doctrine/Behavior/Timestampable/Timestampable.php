<?php
namespace Sasedev\Doctrine\Behavior\Timestampable;

/**
 * This interface is not necessary but can be implemented for
 * Entities which in some cases needs to be identified as
 * Timestampable
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface Timestampable
{

    // timestampable expects annotations on properties

/**
 *
 * @sasedev:Timestampable(on="create")
 * dates which should be updated on insert only
 */

/**
 *
 * @sasedev:Timestampable(on="update")
 * dates which should be updated on update and insert
 */

/**
 *
 * @sasedev:Timestampable(on="change", field="field", value="value")
 * dates which should be updated on changed "property"
 * value and become equal to given "value"
 */

/**
 *
 * @sasedev:Timestampable(on="change", field="field")
 * dates which should be updated on changed "property"
 */

/**
 *
 * @sasedev:Timestampable(on="change", fields={"field1", "field2"})
 * dates which should be updated if at least one of the given fields changed
 */

/**
 * example
 *
 * @sasedev:Timestampable(on="create")
 * @Column(type="date")
 * $created
 */
}
