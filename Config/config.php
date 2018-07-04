<?php
/**
 * @package     MauticWeezevent
 * @copyright   2018 Didier Courty et Geoffrey Morel
 * @author      Didier Courty et Geoffrey Morel
 * @link        https://github.com/europrimus/MauticWeezEvent
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return array(
// informations sur le plugin
  'name'        => 'Weezevent',
  'description' => 'Importing from weezevent.com tickets owner as contacts',
  'version'     => '1.0.0',
  'author'      => 'Didier Courty et Geoffrey Morel',

// services
  'services'    => array(
    // pour utiliser le model API
    'models' => array(
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
    ],
  ),

// les routes
  'routes'   => array(
    'main' => array(
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
        )
      ),
    ),
  ),

// ajout dans le menu
  'menu'     => array(
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
