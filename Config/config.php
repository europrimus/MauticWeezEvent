<?php
/**
 * @package     MauticWeezevent
 * @copyright   2018 Didier et Geoffrey
 * @author      Didier et Geoffrey
 * @link        https://github.com/europrimus/MauticWeezEvent
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return array(
// informations sur le plugin
  'name'        => 'Weezevent',
  'description' => 'Récupération de donnée weezevent.com',
  'version'     => '0.25.0',
  'author'      => 'Didier et Geoffrey',

// services
  'services'    => array(
    // pour tuiliser le model API
    'models' => array(
      //mautic.mauticweezevent.model.api
      'mautic.mauticweezevent.model.api' => array(
        'class' => MauticPlugin\MauticWeezeventBundle\Model\APIModel::class,
        'arguments' => [],
      ),
    ),

    'integrations' =>[
      'mautic.integration.weezevent_config' => [
        'class' => 'MauticPlugin\MauticWeezeventBundle\Integration\WeezeventIntegration',
        'arguments' => [],
      ],
      /*
      'mautic.integration.weezevent_mapping' => [
        'class' => 'MauticPlugin\MauticWeezeventBundle\Integration\WeezeventIntegration',
        'arguments' => [],
      ],
      */
    ],
  ),

// les routes
  'routes'   => array(
    'public' => [
      'plugin_weezevent_auto' => array(
        'path'       => '/weezevent/auto',
        'controller' => 'MauticWeezeventBundle:event:auto',
        'method'     => 'GET',
      ),
      ],
    'main' => array(
  // la configuration
      'plugin_weezevent_config' => array(
        'path'       => '/weezevent/admin',
        'controller' => 'MauticWeezeventBundle:admin:config',
        'method'     => 'GET',
      ),

  // les evenements
      'plugin_weezevent' => array(
        'path'       => '/weezevent',
        'controller' => 'MauticWeezeventBundle:event:liste',
      ),
      'plugin_weezevent_tickets' => array(
        'path'       => '/weezevent/{idEvent}',
        'controller' => 'MauticWeezeventBundle:event:ImportTickets',
        'requirements' => array(
            'idEvent' => '[0-9]*'
            //'nomEvent' => '(.*)'
        )
      ),
    ),
  ),

// ajout dans le menu
  'menu'     => array(
      'admin' => array(
          'plugin.weezevent.admin' => array(
            //'parent' => 'mautic.core.plugins',
            'route'     => 'plugin_weezevent_config',
            'iconClass' => 'fa-ticket',
            'access'    => 'admin',
            'checks'    => array(
              'integration' => [
                  'Weezevent' => [
                      'enabled' => true,
                  ],
              ],
            ),
            'priority'  => 100
          )
      ),
      'main' => array(
          'plugin.weezevent.menu' => array(
              'parent' => 'mautic.core.components',
              'route'     => 'plugin_weezevent',
              //'iconClass' => 'fa-calendar',
              'access'    => 'admin',
              'checks'    => array(
                'integration' => [
                    'Weezevent' => [
                        'enabled' => true,
                    ],
                ],
              ),
              'priority'  => 60
          )
      )
  ),
);
