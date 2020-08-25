<?php

namespace Sasedev\Doctrine\Behavior\Sortable;

/**
 * This interface is not necessary but can be implemented for
 * Entities which in some cases needs to be identified as
 * Sortable
 *
 * @author Lukas Botsch <lukas.botsch@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface Sortable
{
    // use now annotations instead of predefined methods, this interface is not necessary

    /**
     * @sasedev:SortablePosition - to mark property which will hold the item position use annotation @sasedev:SortablePosition
     *              This property has to be numeric. The position index can be negative and will be counted from right to left.
     *
     * example:
     *
     * @sasedev:SortablePosition
     * @Column(type="int")
     * $position
     *
     * @sasedev:SortableGroup
     * @Column(type="string", length=64)
     * $category
     *
     */

    /**
     * @sasedev:SortableGroup - to group node sorting by a property use annotation @sasedev:SortableGroup on this property
     *
     * example:
     *
     * @sasedev:SortableGroup
     * @Column(type="string", length=64)
     * $category
     */
}
