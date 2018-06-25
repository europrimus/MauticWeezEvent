<?php
// plugins/MauticWeezeventBundle/Controller/DefaultController.php

namespace MauticPlugin\MauticWeezeventBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;

class eventController extends FormController
{
// liste les événements
  public function listeAction(){
    $weezeventObj = $this->getModel('mauticweezevent.api');
//$weezeventObj = $this->getModel('weezeventAPI');
    dump($weezeventObj);
    //$weezeventObj = new weezeventModel();
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
