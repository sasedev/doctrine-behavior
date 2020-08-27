<?php
namespace Sasedev\Doctrine\Behavior\Translatable\Hydrator\ORM;

use Doctrine\ORM\Internal\Hydration\ObjectHydrator as BaseObjectHydrator;
use Sasedev\Doctrine\Behavior\Exception\RuntimeException;
use Sasedev\Doctrine\Behavior\Translatable\TranslatableListener;

/**
 * If query uses TranslationQueryWalker and is hydrating
 * objects - when it requires this custom object hydrator
 * in order to skip onLoad event from triggering retranslation
 * of the fields
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class ObjectHydrator extends BaseObjectHydrator
{

    /**
     * State of skipOnLoad for listener between hydrations
     *
     * @see ObjectHydrator::prepare()
     * @see ObjectHydrator::cleanup()
     *
     * @var bool
     */
    private $savedSkipOnLoad;

    /**
     *
     * {@inheritdoc}
     */
    protected function prepare()
    {

        $listener = $this->getTranslatableListener();
        $this->savedSkipOnLoad = $listener->isSkipOnLoad();
        $listener->setSkipOnLoad(true);
        parent::prepare();

    }

    /**
     *
     * {@inheritdoc}
     */
    protected function cleanup()
    {

        parent::cleanup();
        $listener = $this->getTranslatableListener();
        $listener->setSkipOnLoad($this->savedSkipOnLoad !== null ? $this->savedSkipOnLoad : false);

    }

    /**
     * Get the currently used TranslatableListener
     *
     * @throws RuntimeException - if listener is not found
     *
     * @return TranslatableListener
     */
    protected function getTranslatableListener()
    {

        $translatableListener = null;
        // foreach ($this->_em->getEventManager()->getListeners() as $event => $listeners) {
        foreach ($this->_em->getEventManager()
            ->getListeners() as $listeners)
        {
            // foreach ($listeners as $hash => $listener) {
            foreach ($listeners as $listener)
            {
                if ($listener instanceof TranslatableListener)
                {
                    $translatableListener = $listener;
                    break;
                }
            }
            if ($translatableListener)
            {
                break;
            }
        }

        if (is_null($translatableListener))
        {
            throw new RuntimeException('The translation listener could not be found');
        }

        return $translatableListener;

    }

}
