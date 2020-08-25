<?php

namespace Sasedev\Doctrine\Behavior\Uploadable;

/**
 * This interface is not necessary but can be implemented for
 * Domain Objects which in some cases needs to be identified as
 * Uploadable
 *
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
interface Uploadable
{
    // this interface is not necessary to implement

    /**
     * @sasedev:Uploadable
     * to mark the class as Uploadable use class annotation @sasedev:Uploadable
     * this object will be able Uploadable
     * example:
     *
     * @sasedev:Uploadable
     * class MyEntity
     */
}
