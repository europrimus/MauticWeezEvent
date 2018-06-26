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
    <form method="post">
      <label for="login"><?=$view['translator']->trans('plugin.weezevent.config_login');?></label>
      <input type="text" name="login" id="login" value="<?=$login?>"><br>
      <label for="pass"><?=$view['translator']->trans('plugin.weezevent.config_pass');?></label>
      <input type="text" name="pass" id="pass" value="<?=$pass?>"><br>
      <label for="APIkey"><?=$view['translator']->trans('plugin.weezevent.config_APIkey');?></label>
      <input type="text" name="APIkey" id="APIkey" value="<?=$APIkey?>"><br>
      <input type="submit" name="submit" value="<?=$view['translator']->trans('plugin.weezevent.config_submit');?>">
    </form>
</div>
