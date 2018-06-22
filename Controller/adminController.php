<?php
// plugins/HelloWorldBundle/Controller/DefaultController.php

namespace MauticPlugin\MauticWeezeventBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;

class adminController extends FormController
{
// gaffiche la configuration
  public function configAction(){
    return $this->delegateView(
      array(
        'viewParameters'  => array(
            'texte'   => "un texte",
        ),
        'contentTemplate' => 'MauticWeezeventBundle:admin:config.html.php',
        'passthroughVars' => array(
            'activeLink'    => 'plugin_weezevent_config',
            'route'         => $this->generateUrl('plugin_weezevent_config'),
            'mauticContent' => 'weezeventConfig'
        )
      )
    );
  }
}
