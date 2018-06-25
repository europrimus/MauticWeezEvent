<?php
// plugins/MauticWeezeventBundle/Controller/DefaultController.php

namespace MauticPlugin\MauticWeezeventBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;
use MauticPlugin\MauticWeezeventBundle\Model\weezeventModel;

class eventController extends FormController
{
// gaffiche la configuration
  public function listeAction(){
    $weezeventObj = new weezeventModel();
    $events = $weezeventObj->getEvents();
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
