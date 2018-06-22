<?php
//plugins/HelloWorldBundle/Views/admin/config.html.php

// Check if the request is Ajax
if (!$app->getRequest()->isXmlHttpRequest()) {

}
// Extend the base content
$view->extend('MauticCoreBundle:Default:content.html.php');

$titre = $view['translator']->trans('mautic.weezevent.config.titre');
$view['slots']->set('headerTitle', $titre);
?>

<div class="weezevent-content">
<img src="<?php echo $view['assets']->getUrl('plugins/MauticWeezeventBundle/Assets/img/weezevent-logo.png') ?>" />
    <?php $view['slots']->output('_content'); ?>
</div>
