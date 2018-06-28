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

<div class="weezevent-content">
  <?php $view['slots']->output('_content'); ?>
  <img src="<?php echo $view['assets']->getUrl('plugins/MauticWeezeventBundle/Assets/img/weezevent-logo.png') ?>" /><br>
  <?php
    if( is_array($events) ){
      $count=0;
      //dump($events);
      foreach ( $events as $event ){
        if( $count <= 10 ){
          echo "<h2>element $count</h2><pre>";
          dump($event);
          echo "</pre>";
          $count++;
        }
      }
    }else{
      echo "<p>".$view['translator']->trans('plugin.weezevent.event.error.api')."</p>";
    }
  ?>
</div>
