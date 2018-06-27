<?php
// plugins/MauticWeezeventBundle/Controller/DefaultController.php

namespace MauticPlugin\MauticWeezeventBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;

class eventController extends FormController
{
// liste les événements
  public function listeAction(){

// recupération de la configuration
    $config = $this->get('mautic.helper.core_parameters');
    $api_email=     $config->getParameter('Weezevent_login');
    $api_password=  $config->getParameter('Weezevent_password');
    $api_key=       $config->getParameter('Weezevent_API_key');
// recuperation du model
    $weezeventModel = $this->getModel('mauticweezevent.api');
// connexion a l'api
    $weezeventModel->connect( $api_email,$api_password,$api_key );
// recuperation des evenements
    $events = $weezeventModel->getEvents();

    //$events=["1",2,"3","4"];
    return $this->delegateView(
      array(
        'viewParameters'  => array(
            'events'   => $events->events,
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
