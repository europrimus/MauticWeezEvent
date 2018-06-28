<?php
// plugins/MauticWeezeventBundle/Controller/DefaultController.php

namespace MauticPlugin\MauticWeezeventBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;
use Mautic\LeadBundle\Entity\Lead;

class eventController extends FormController
{
private $availableLeadFields = [ 'title', 'firstname', 'lastname', 'email', 'companies', 'position',
    'address1', 'address2', 'city', 'state', 'zipcode', 'country',
    'attribution', 'attribution_date',
    'mobile', 'phone', 'points', 'fax',
    'website', 'stage', 'owner', 'tags'];

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
    }else{
      // à traduire
      $events = false;
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
  } // end listeAction()

  // création de contact pour teste
  public function MultiContactsAction(){
    $contactListe=[];
    for ($i=0; $i < 5; $i++) {
      $contactListe[]=[
            "firstname" => md5(random_bytes(10)),
            "lastname" => md5(random_bytes(10)),
            "email" => "nom_prenom@mail.fr", //md5(random_bytes(10))."@mail.fr",
            "weezevent" => ["BFC","newtag"],
          ];
    }

    foreach ($contactListe as $contactInfo) {
      $this->addOrUpdateAction($contactInfo);
    }
    // Redirect to contacts liste
    return $this->redirectToRoute('mautic_contact_index');
  }


  /* ajoute ou met à jour un contact
  @param array: [ "firstname" => string, "lastname" => string, "email" => string, "weezevent" => array ]
  */
  public function addOrUpdateAction(array $contactInfo)
  {
    //from https://developer.mautic.org/#creating-new-leads
    $leadModel = $this->getModel('lead');
    $leadId = null;

    // Optionally check for identifier fields to determine if the lead is unique
    $uniqueLeadFields    = $this->getModel('lead.field')->getUniqueIdentiferFields();
    $uniqueLeadFieldData = array();

    // Check if unique identifier fields are included
    $inList = array_intersect_key($contactInfo, $uniqueLeadFields);

    foreach ($inList as $k => $v) {
        if (array_key_exists($k, $uniqueLeadFields)) {
            $uniqueLeadFieldData[$k] = $v;
        }
    }
    // If there are unique identifier fields, check for existing leads based on lead data
    if (count($inList) && count($uniqueLeadFieldData)) {
        $existingLeads = $this->getDoctrine()->getManager()->getRepository('MauticLeadBundle:Lead')->getLeadsByUniqueFields(
            $uniqueLeadFieldData,
            $leadId // If a currently tracked lead, ignore this ID when searching for duplicates
        );
        if (!empty($existingLeads)) {
            // Existing found so merge the two leads
            //$lead = $leadModel->mergeLeads($lead, $existingLeads[0]);
            $lead = $existingLeads[0];
        }else{
          // generate a completely new lead with
          $lead = new Lead();
          $lead->setNewlyCreated(true);
        }
    }

    // Set the lead's data
    $leadModel->setFieldValues($lead, $contactInfo);
    // ajout de l'évènement dans les tags
    $leadModel->setTags($lead, $contactInfo["weezevent"]);

    // Save the entity
    $leadModel->saveEntity($lead);
  } // end addOrUpdate()

}
