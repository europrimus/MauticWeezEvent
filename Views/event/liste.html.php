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
  .weezevent-content> img {
    display: inline-block;
    top: 0;
  }
  .weezevent-content> content-body {
    margin-left: 10vw;
    margin-bottom: 20em;
  }
  .weezevent-content> h3 {
    margin-top: 30px;
  }
   ul {
    list-style-type: none;
  }

  .weezevent-content  li {
    margin: 12px;
    cursor: default;
  }

   .weezevent-content .fa {
    font-size: 23px;
  }

   .weezevent-content li:hover {
    color: blue;
  }

</style>


<div class="weezevent-content">
  <?php $view['slots']->output('_content'); ?>
  <div class="container">
    <div class="row">
<!-- affiche logo  -->
      <div class="col-md-6">
          <img src="<?php echo $view['assets']->getUrl('plugins/MauticWeezeventBundle/Assets/img/weezevent-logo.png') ?>" />
      </div>

<!-- afficher les evenements + bouton d ' importation  -->
      <div class="col-md-6">

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
              echo '<li>'. date("d/m/Y",strtotime($date) ) .' | '. $event->name .' <a href="'.$view['router']->generate('plugin_weezevent_tickets',
                array('idEvent' => $event->id, 'nomEvent'=> $event->name )).'" ><i class="fa fa-cloud-download" aria-hidden="true"></i></a>
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
    </div>
  </div>


</div>
