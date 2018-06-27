<?php
// plugins/MauticWeezeventBundle/Controller/DefaultController.php

namespace MauticPlugin\MauticWeezeventBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;
use Mautic\ConfigBundle\Event\ConfigEvent;

class adminController extends FormController
{
// affiche la configuration
  public function configAction(){
// parameters from Config/config.php
    $config = $this->get('mautic.helper.core_parameters');
    dump($config);
    $login = $config->getParameter('Weezevent_login');
    $pass = $config->getParameter('Weezevent_password');
    $APIkey = $config->getParameter('Weezevent_API_key');

// config
    $configHelper = $this->get('mautic.helper.bundle');
    //dump($configHelper);
    $parameters = $configHelper->getBundleConfig('MauticWeezeventBundle', 'parameters', true);
dump($parameters);
    //$pass = $configHelper->getBundleConfig('MauticWeezeventBundle', 'Weezevent_password', true);
    //$APIkey = $configHelper->getBundleConfig('MauticWeezeventBundle', 'Weezevent_API_key', true);
// on génère la vue
    return $this->delegateView(
      array(
        'viewParameters'  => array(
            'login'   => $login,
            'pass'   => $pass,
            'APIkey'   => $APIkey,
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

// enregistre la configuration
  public function storeAction(){
// on récupère les valeurs du formulaire
    $login = $this->request->request->get("login");
    $pass = $this->request->request->get("pass");
    $APIkey = $this->request->request->get("APIkey");
// read config
    $config = $this->get('mautic.helper.core_parameters');
    //dump( $config );

// Set updated values
    //$config->setConfig();
    return $this->configAction();
  }
}
