<?php
// plugins/MauticWeezeventBundle/Controller/DefaultController.php

namespace MauticPlugin\MauticWeezeventBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;

class eventController extends FormController
{
// liste les événements
  public function listeAction(){

// recupération de la configuration
// config from integration
    /** @var \Mautic\PluginBundle\Helper\IntegrationHelper $helper */
    $helper = $this->factory->getHelper('integration');
    /** @var  MauticPlugin\MauticWeezeventBundle\Integration\WeezeventIntegration $Weezevent */
    $Weezevent = $helper->getIntegrationObject('Weezevent');
//on récupère les valeurs
    $keys = $Weezevent->getKeys();
    $login = $keys["Weezevent_login"];
    $pass = $keys["Weezevent_password"];
    $APIkey = $keys["Weezevent_API_key"];
// recuperation du model
    $weezeventModel = $this->getModel('mauticweezevent.api');
// connexion a l'api
    $weezeventModel->connect( $login,$pass,$APIkey );
// recuperation des evenements
    if($weezeventModel->isConnected()){
      $events = $weezeventModel->getEvents();
      $events = $events->events;
    }else{
      // à traduire
      $events = "Impossible de ce connecter à Weezevent. Vérifier votre configuration.";
    }

    //$events=["1",2,"3","4"];
    return $this->delegateView(
      array(
        'viewParameters'  => array(
            'events'   => $events,
        ),
        'contentTemplate' => 'MauticWeezeventBundle:event:liste.html.php',
        'passthroughVars' => array(
            'activeLink'    => 'plugin_weezevent',
            'route'         => $this->generateUrl('plugin_weezevent'),
            'mauticContent' => 'weezevent'
        )
      )
    );
  }
}
