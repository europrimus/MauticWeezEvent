<?php
// plugins/MauticWeezeventBundle/EventListener/ConfigSubscriber.php

namespace MauticPlugin\MauticWeezeventBundle\EventListener;

use Mautic\ConfigBundle\Event\ConfigEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\ConfigBundle\ConfigEvents;
use Mautic\ConfigBundle\Event\ConfigBuilderEvent;

/**
 * Class ConfigSubscriber
 */
class ConfigSubscriber extends CommonSubscriber
{

    /**
     * @return array
     */
    static public function getSubscribedEvents()
    {
        return array(
            ConfigEvents::CONFIG_ON_GENERATE => array('onConfigGenerate', 0),
            ConfigEvents::CONFIG_PRE_SAVE    => array('onConfigSave', 0)
        );
    }

    /**
     * @param ConfigBuilderEvent $event
     */
    public function onConfigGenerate(ConfigBuilderEvent $event)
    {
        $event->addForm(
            array(
                'formAlias'  => 'weezevent_config',
                'formTheme'  => 'MauticWeezeventBundle:FormTheme\Config',
                'parameters' => $event->getParametersFromConfig('MauticWeezeventBundle') // de config/config.php
            )
        );
    }

    /**
     * @param ConfigEvent $event
     */
    public function onConfigSave(ConfigEvent $event)
    {
        /** @var array $values */
        $values = $event->getConfig();

        // Manipulate the values
        if (!empty($values['weezevent_config']['Weezevent_login'])) {
            $values['weezevent_config']['Weezevent_login'] = htmlspecialchars($values['weezevent_config']['Weezevent_login']);
        }
        if (!empty($values['weezevent_config']['Weezevent_password'])) {
            $values['weezevent_config']['Weezevent_password'] = htmlspecialchars($values['weezevent_config']['Weezevent_password']);
        }
        if (!empty($values['weezevent_config']['Weezevent_API_key'])) {
            $values['weezevent_config']['Weezevent_API_key'] = htmlspecialchars($values['weezevent_config']['Weezevent_API_key']);
        }

        // Set updated values
        $event->setConfig($values);
    }
}
