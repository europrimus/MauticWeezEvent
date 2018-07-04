<?php
// plugins/MauticWeezeventBundle/Controller/DefaultController.php

namespace MauticPlugin\MauticWeezeventBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;
use Mautic\LeadBundle\Entity\Lead;

class eventController extends FormController
{
  private $connect = false;

 private function connexion(){
  // recupération de la configuration
  // config from integration
       /** @var  MauticPlugin\MauticWeezeventBundle\Integration\WeezeventIntegration $Weezevent */
       $Weezevent = $this->factory->getHelper('integration')->getIntegrationObject('Weezevent');
    //on récupère les valeurs
       $keys = $Weezevent->getKeys();
       $login = $keys["Weezevent_login"];
       $pass = $keys["Weezevent_password"];
       $APIkey = $keys["Weezevent_API_key"];

    // recuperation du model
       $weezeventModel = $this->getModel('mauticweezevent.api');
    // connexion a l'api
       $weezeventModel->connect( $login,$pass,$APIkey );
       $this->connect = $weezeventModel;
       //return $weezeventModel;
  }

// liste les événements
  public function listeAction(){
    if(!$this->connect){
      $this->connexion();
    }

// recuperation des evenements
    if($this->connect->isConnected()){
      $events = $this->connect->getEvents();
    }else{
      $events = false;
    }

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

  public function ImportTicketsAction($idEvent){
    $nomEvent= $this->request->query->get("nomEvent");
    if(!$this->connect){
      $this->connexion();
    }
// recuperation des tickets
    if($this->connect->isConnected()){
      $tickets = $this->connect->getTickets($idEvent);
    }else{
      $tickets = false;
    }

    //parcour de la liste
    foreach ($tickets as $participants) {
      // ajout aux contacts
/*
  +"first_name": ""
  +"last_name": ""
  +"email": ""
  +"comment": ""
  +"custom_field": ""
  +"origin": "web"
*/
      $this->addOrUpdateAction([
        "firstname" => $participants->owner->first_name,
        "lastname" => $participants->owner->last_name,
        "email" => $participants->owner->email,
        "event" => $nomEvent,
      ]);
    }
    // Redirect to contacts liste
    return $this->redirectToRoute('mautic_contact_index');
  }

  /* ajoute ou met à jour un contact
  @param array: [ "firstname" => string, "lastname" => string, "email" => string, "weezevent" => array ]
  */
  public function addOrUpdateAction(array $contactInfo)
  {
    // les infos de mapping
    $Weezevent = $this->factory->getHelper('integration')->getIntegrationObject('Weezevent');
    $mapping=$Weezevent->getIntegrationSettings()->getFeatureSettings()["leadFields"];
    $fieldsInfo = $Weezevent->getFormLeadFields();

    // appliquation du mapping
    $mappedContacts=[];
    foreach ($contactInfo as $key => $value) {
      $mappedContacts[$mapping[$key]] = $value;
      if ( $fieldsInfo[$key]["required"] && empty($value) )
      {
        $mappedContacts=[];
        return false;
      }
    }

    //adapted from https://developer.mautic.org/#creating-new-leads
    $leadModel = $this->getModel('lead');
    $leadId = null;

    // Optionally check for identifier fields to determine if the lead is unique
    $uniqueLeadFields    = $this->getModel('lead.field')->getUniqueIdentiferFields();
    $uniqueLeadFieldData = array();

    // Check if unique identifier fields are included
    $inList = array_intersect_key($mappedContacts, $uniqueLeadFields);

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
            // Existing found so use it
            $lead = $existingLeads[0];
        }else{
          // generate a completely new lead
          $lead = new Lead();
          $lead->setNewlyCreated(true);
        }

      // Set the lead's data
      $leadModel->setFieldValues($lead, $mappedContacts);

      // ajout de l'évènement dans les tags
      //$leadModel->setTags($lead, $contactInfo["event"]);

      // Save the entity
      $leadModel->saveEntity($lead);
    } // end check leads
    return true;
  } // end addOrUpdate()

}
