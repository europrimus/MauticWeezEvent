<?php
// plugins/MauticWeezeventBundle/Controller/DefaultController.php

namespace MauticPlugin\MauticWeezeventBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;
use Mautic\LeadBundle\Entity\Lead;

class eventController extends FormController
{
/*
private $availableLeadFields = [ 'title', 'firstname', 'lastname', 'email', 'companies', 'position',
    'address1', 'address2', 'city', 'state', 'zipcode', 'country',
    'attribution', 'attribution_date',
    'mobile', 'phone', 'points', 'fax',
    'website', 'stage', 'owner', 'tags'];
*/

 private function connexion(){
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
       return $weezeventModel;
  }

// liste les événements
  public function listeAction(){
    $weezeventModel = $this->connexion();
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

// recherche par date
  public function lastDayEvents(){
    $date = date('Y-m-d',strtotime("-1 days"));
    return getEventByDate($date);
  }

  public function listeTicketsAction($idEvent){
    $nomEvent= $this->request->query->get("nomEvent");
    $weezeventModel = $this->connexion();
// recuperation des evenements
    if($weezeventModel->isConnected()){
      $tickets = $weezeventModel->getTickets($idEvent);
    }else{
      // à traduire
      $tickets = false;
    }
    //parcour de la liste
    foreach ($tickets as $participants) {
      // ajout aux contacts
      $this->addOrUpdateAction([
        "firstname" => $participants->owner->first_name,
        "lastname" => $participants->owner->last_name,
        "email" => $participants->owner->email,
        "weezevent" => [$nomEvent],
      ]);
    }
    // Redirect to contacts liste
    return $this->redirectToRoute('mautic_contact_index');
  }

  // création de contact pour teste
  public function MultiContactsAction(array $contactListe){
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
            // Existing found so use it
            $lead = $existingLeads[0];
        }else{
          // generate a completely new lead
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
