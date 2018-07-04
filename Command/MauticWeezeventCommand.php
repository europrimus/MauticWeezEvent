<?php

namespace MauticPlugin\MauticWeezeventBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use MauticPlugin\MauticWeezeventBundle\Controller\eventController;
use Mautic\LeadBundle\Entity\Lead;

class MauticWeezeventCommand extends ContainerAwareCommand
{

  private $lastExecutionDate;
  private $input;
  private $output;
  private $Integration;
  private $connect = false;

  /**
   * Configure the command.
   */
  protected function configure()
  {
      $this->setName('mautic:weezevent:import')
          ->setDescription('Import contact from weezevent.')
          ->addOption('date', 'd', InputOption::VALUE_OPTIONAL, 'Date of the event to import', null);
  }

  /**
   * @param InputInterface  $input
   * @param OutputInterface $output
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->input = $input;
    $this->output = $output;

    // config from integration
    /** @var  MauticPlugin\MauticWeezeventBundle\Integration\WeezeventIntegration $Weezevent */
    $this->Integration = $this->getContainer()->get('mautic.helper.integration')->getIntegrationObject('Weezevent');

    // si le pluggin est actif on continu
    if( !$this->Integration->getIntegrationSettings()->getIsPublished() ){return 0;};
    // on regarde si déja executé aujourd'huit
    $this->lastExecutionDate = date("Y-m-d");
    //$this->getContainer()->get('doctrine')->getManager()->persist( ["weezevent.cron.lastExecutionDate" => $this->lastExecutionDate] );
    //$config = $this->getContainer()->get('mautic.event.model.config')->getConfig();
    //echo "config: ".gettype($config);
/*
$values = $event->getConfig();
$event->setConfig($values);

$this->em->persist( entity object );
$this->em->flush();
*/

// déplacé depuis le controller
    $nbImport=0;

    // recupération des évènements
    $events = $this->lastDayEvents();

    foreach ($events as  $event) {
      // recuperation des tickets
      if($this->connect->isConnected()){
        $tickets = $this->connect->getTickets($event->id);
        $this->output->writeln("Weezevent : Connexion résussit");
      }else{
        $this->output->writeln("Weezevent : Impossible de ce connecter");
        $tickets = false;
      }
      //parcour de la liste
      foreach ($tickets as $participants) {
        // ajout aux contacts
        $this->addOrUpdateAction([
          "firstname" => $participants->owner->first_name,
          "lastname" => $participants->owner->last_name,
          "email" => $participants->owner->email,
          "event" => $event->name,
        ]);
        $nbImport++;
      }
    }

    $this->output->writeln($this->lastExecutionDate." Weezevent : ".$nbImport." contacts importés");
  }

  // recherche par date
    public function lastDayEvents(){
      if( isset($this->input->getOptions()['date']) ){
        $date = date( 'Y-m-d',strtotime( $this->input->getOptions()['date'] ) );
      }else{
        $date = date('Y-m-d',strtotime("-1 days"));
      }
      //echo "import from :".$date.PHP_EOL;

      if(!$this->connect){
        $this->connexion();
      }
  // recuperation des evenements
      if($this->connect->isConnected()){
        return $this->connect->getEventByDate($date,5);
      }else{
        return false;
      }
    }


   private function connexion(){
    // recupération de la configuration
    //on récupère les valeurs
       $keys = $this->Integration->getKeys();
       $login = $keys["Weezevent_login"];
       $pass = $keys["Weezevent_password"];
       $APIkey = $keys["Weezevent_API_key"];

      // recuperation du model
         $weezeventModel = $this->getContainer()->get('mautic.mauticweezevent.model.api');
      // connexion a l'api
         $weezeventModel->connect( $login,$pass,$APIkey );
         $this->connect = $weezeventModel;
    }

    /* ajoute ou met à jour un contact
    @param array: [ "firstname" => string, "lastname" => string, "email" => string, "weezevent" => array ]
    */
    public function addOrUpdateAction(array $contactInfo)
    {
      // les infos de mapping
      $Weezevent = $this->getContainer()->get('mautic.helper.integration')->getIntegrationObject('Weezevent');
      $mapping = $Weezevent->getIntegrationSettings()->getFeatureSettings()["leadFields"];
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

      //from https://developer.mautic.org/#creating-new-leads
      $leadModel = $this->getContainer()->get('mautic.lead.model.lead');
      $leadId = null;

      // Check for identifier fields to determine if the lead is unique
      $uniqueLeadFields    = $this->getContainer()->get('mautic.lead.model.field')->getUniqueIdentiferFields();
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
          $existingLeads = $this->getContainer()->get('doctrine')->getManager()->getRepository('MauticLeadBundle:Lead')->getLeadsByUniqueFields(
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
