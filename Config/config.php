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
<br>
Pensser à le <a href="/plugins/weezevent">configurer</a>',
  'version'     => '0.12',
  'author'      => 'Didier et Geoffrey',

// les parametre de configuration
  'parameters' => array(
      'Weezevent_enabled' => true,
// identifiant weezevent et cles API
/*
      'Weezevent_login' => 'login',
      'Weezevent_password' => 'password',
      'Weezevent_API_key' => 'API Key',
*/
  ),

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

/*
    'forms' => [
      'mautic.form.type.mauticweezevent.config' => [
        'class' => MauticPlugin\MauticWeezeventBundle\Form\Type\ConfigType::class,
        'alias' => 'weezevent_config',
      ],
    ],
*/
    'integrations' =>[
      'mautic.integration.weezevent' => [
        'class' => 'MauticPlugin\MauticWeezeventBundle\Integration\WeezeventIntegration',
        'arguments' => [],
      ],
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
/*
      'plugin_weezevent_config_store' => array(
        'path'       => '/weezevent/admin',
        'controller' => 'MauticWeezeventBundle:admin:store',
        'method'     => 'POST',
      ),
*/
  // les evenements
      'plugin_weezevent' => array(
        'path'       => '/weezevent',
        'controller' => 'MauticWeezeventBundle:event:liste',
      ),
      'plugin_weezevent_tickets' => array(
        'path'       => '/weezevent/{idEvent}',
        'controller' => 'MauticWeezeventBundle:event:listeTickets',
        'requirements' => array(
            'idEvent' => '[0-9]*'
            //'nomEvent' => '(.*)'
        )
      ),
/*
      // pour tester
      'plugin_weezevent_contacts' => array(
        'path'       => '/weezevent/contact',
        'controller' => 'MauticWeezeventBundle:event:MultiContacts',
      ),
*/
    ),
  ),

// ajout dans le menu
  'menu'     => array(
      'admin' => array(
          'plugin.weezevent.admin' => array(
              'route'     => 'plugin_weezevent_config',
              'iconClass' => 'fa-ticket',
              'access'    => 'admin',
              'checks'    => array(
                  'parameters' => array(
                  )
              ),
              'priority'  => 60
          )
      ),
      'main' => array(
          'plugin.weezevent.menu' => array(
              'parent' => 'mautic.core.components',
              'route'     => 'plugin_weezevent',
              'iconClass' => 'fa-calendar',
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
