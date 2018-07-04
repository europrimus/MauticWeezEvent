<?php
//plugins/MauticWeezeventBundle/Views/event/liste.html.php

// Check if the request is Ajax
if (!$app->getRequest()->isXmlHttpRequest()) {

}
// Extend the base content
$view->extend('MauticCoreBundle:Default:content.html.php');

$titre = $view['translator']->trans('plugin.weezevent.event_liste_titre');
$view['slots']->set('headerTitle', $titre);
?>

<!-- un peu de style -->
<style type="text/css">
  ul {
    list-style-type: none;
  }

  li {
    margin: 10px;
  }

</style>


<div class="weezevent-content">
  <?php $view['slots']->output('_content'); ?>
  <img src="<?php echo $view['assets']->getUrl('plugins/MauticWeezeventBundle/Assets/img/weezevent-logo.png') ?>" /><br>
  <ul>
  <?php
    if( is_array($events) ){
      $count=0;
      foreach ( $events as $event ){
        if( $count <= 10 ){
           if(empty($event->date->end)){
            $date=$event->date->start;
          }else{
            $date=$event->date->end;
          }
          echo '<li>'. date("d/m/Y",strtotime($date) ) .' |'. $event->name .' -- <a href="'.$view['router']->generate('plugin_weezevent_tickets',
            array('idEvent' => $event->id, 'nomEvent'=> $event->name )).'" ><button> Importer les contacts </button></a>
          </li>';
          $count++;
        }
      }
    }else{
      echo "<li>".$view['translator']->trans('plugin.weezevent.event.error.api')."</li>";
    }
  ?>
  </ul>
</div>
