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
<img src="<?php echo $view['assets']->getUrl('plugins/MauticWeezeventBundle/Assets/img/weezevent-logo.png') ?>" />
  <?php
     foreach ( $events as $event ){
       if( $count <= 10 ){
           var_dump($event);
           $count++;
       }
    }
  ?>
</div>
