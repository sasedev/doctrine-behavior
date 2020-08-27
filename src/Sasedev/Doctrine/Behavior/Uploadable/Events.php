<?php
namespace Sasedev\Doctrine\Behavior\Uploadable;

/**
 * Container for all Sasedev Uploadable events
 *
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class Events
{

    private function __construct()
    {


    }

    /**
     * The uploadablePreFileProcess event occurs before a file is processed inside
     * the Uploadable listener.
     * This means it happens before the file is validated and moved
     * to the configured path.
     *
     * @var string
     */
    const UPLOADABLE_PRE_FILE_PROCESS = 'uploadablePreFileProcess';

    /**
     * The uploadablePostFileProcess event occurs after a file is processed inside
     * the Uploadable listener.
     * This means it happens after the file is validated and moved
     * to the configured path.
     *
     * @var string
     */
    const UPLOADABLE_POST_FILE_PROCESS = 'uploadablePostFileProcess';

}
