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
  'description' => 'Récupération de donnée weezevent.com
---
Plus d info sur <a href="https://github.com/europrimus/MauticWeezEvent">github</a>.
Pensser à le <a href="/plugins/weezevent">configurer</a>',
  'version'     => '0.1',
  'author'      => 'Didier et Geoffrey',

// les parametre de configuration
  'parameters' => array(
      'Weezevent_enabled' => true,
      'Weezevent_login' => '',
      'Weezevent_password' => '',
      'Weezevent_API_key' => '',
  ),

// les routes
  'routes'   => array(
    'main' => array(
      'plugin_weezevent_config' => array(
        'path'       => '/weezevent/admin',
        'controller' => 'MauticWeezeventBundle:admin:config',
      ),
      'plugin_weezevent' => array(
        'path'       => '/weezevent',
        'controller' => 'MauticWeezeventBundle:event:liste',
      ),
    ),
  ),

// ajout dans le menu
  'menu'     => array(
      'admin' => array(
          'plugin.weezevent.admin' => array(
              'route'     => 'plugin_weezevent_config',
              'iconClass' => 'fa-gears',
              'access'    => 'admin',
              'checks'    => array(
                  'parameters' => array(
                      'Weezevent_enabled' => true
                  )
              ),
              'priority'  => 60
          )
      ),
      'main' => array(
          'plugin.weezevent.menu' => array(
              'route'     => 'plugin_weezevent',
              'iconClass' => 'fa-gears',
              'access'    => 'admin',
              'checks'    => array(
                  'parameters' => array(
                      'Weezevent_enabled' => true
                  )
              ),
              'priority'  => 60
          )
      )
  ),

// Catégorie
  'categories' => array(
      'plugin:weezevent' => 'mautic.weezevent.categories'
  ),
);
