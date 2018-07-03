<?php
//plugins/MauticWeezeventBundle/Views/admin/config.html.php

// Check if the request is Ajax
if (!$app->getRequest()->isXmlHttpRequest()) {

}
// Extend the base content
$view->extend('MauticCoreBundle:Default:content.html.php');

$titre = $view['translator']->trans('plugin.weezevent.config_titre');
$view['slots']->set('headerTitle', $titre);
?>

<div class="weezevent-content">
<img src="<?php echo $view['assets']->getUrl('plugins/MauticWeezeventBundle/Assets/img/weezevent-logo.png') ?>" />
    <?php $view['slots']->output('_content'); ?>
    <dl>
      <dt><?=$view['translator']->trans('plugin.weezevent.config_login');?> : </dt>
      <dd><?=$login?></dd>
      <dt><?=$view['translator']->trans('plugin.weezevent.config_pass');?> : </dt>
      <dd><?=$pass?></dd>
      <dt><?=$view['translator']->trans('plugin.weezevent.config_APIkey');?> : </dt>
      <dd><?=$APIkey?></dd>
      <dt><?=$view['translator']->trans('plugin.weezevent.config_cron');?> : </dt>
      <dd><?php echo $view["router"]->generate('plugin_weezevent_auto',array(), true); // /weezevent/auto ?></dd>
    </dl>
</div>
