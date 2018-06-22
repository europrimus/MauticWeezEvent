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
    'description' => 'Enables integrations with weezevent.com',
    'version'     => '0.1',
    'author'      => 'Didier et Geoffrey',

// les parametre de configuration
    'parameters' => array(
        'Weezevent_enabled' => false,
        'Weezevent_login' => '',
        'Weezevent_password' => '',
        'Weezevent_API_key' => '',
    )
);
