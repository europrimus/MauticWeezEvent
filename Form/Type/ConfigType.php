<?php
// plugins/MauticWeezeventBundle/Form/Type/ConfigType.php

namespace MauticPlugin\MauticWeezeventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ConfigType
 */
class ConfigType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder->add(
        'Weezevent_enabled',
        'yesno_button_group',
        [
            'label' => 'plugin.weezevent.config.enabled',
            'data'  => (bool) $options['data']['Weezevent_enabled'],
            'attr'  => [
                'tooltip' => 'plugin.weezevent.config.enabled.tooltip',
            ],
        ]
      );
      // login
        $builder->add(
            'Weezevent_login',
            'text',
            array(
                'label' => 'plugin.weezevent.config.Weezevent_login',
                'data'  => $options['data']['Weezevent_login'],
                'attr'  => array(
                    'tooltip' => 'plugin.weezevent.config.Weezevent_login_tooltip'
                )
            )
        );
      // mot de passe
        $builder->add(
            'Weezevent_password',
            'text',
            array(
                'label' => 'plugin.weezevent.config.Weezevent_password',
                'data'  => $options['data']['Weezevent_password'],
                'attr'  => array(
                    'tooltip' => 'plugin.weezevent.config.Weezevent_password_tooltip'
                )
            )
        );
      // API Key
        $builder->add(
            'Weezevent_API_key',
            'text',
            array(
                'label' => 'plugin.weezevent.config.Weezevent_API_key',
                'data'  => $options['data']['Weezevent_API_key'],
                'attr'  => array(
                    'tooltip' => 'plugin.weezevent.config.Weezevent_API_key_tooltip'
                )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'weezevent_config';
    }
}
