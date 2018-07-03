<?php
/**
 * @package     MauticWeezevent
 * @copyright   2018 Didier et Geoffrey
 * @author      Didier et Geoffrey
 * @link        https://github.com/europrimus/MauticWeezEvent
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
namespace MauticPlugin\MauticWeezeventBundle;
use Mautic\PluginBundle\Bundle\PluginBundleBase;

// from https://developer.mautic.org/?php#install/upgrade
use Doctrine\DBAL\Schema\Schema;
use Mautic\PluginBundle\Entity\Plugin;
use Mautic\CoreBundle\Factory\MauticFactory;

/**
 * Class MauticWeezeventBundle
 *
 * @package Mautic\LeadBundle
 */
class MauticWeezeventBundle extends PluginBundleBase
  {
/*
* Called by PluginController::reloadAction when adding a new plugin that's not already installed
*
* @param Plugin        $plugin
* @param MauticFactory $factory
* @param null          $metadata
*/

  static public function onPluginInstall(Plugin $plugin, MauticFactory $factory, $metadata = null,  $installedSchema = NULL)
  {
     if ($metadata !== null) {
         self::installPluginSchema($metadata, $factory);
     }

     // Do other install stuff
  }


  /*
  * Called by PluginController::reloadAction when the plugin version does not match what's installed
  *
  * @param Plugin        $plugin
  * @param MauticFactory $factory
  * @param null          $metadata
  * @param Schema        $installedSchema
  *
  * @throws \Exception
  */
    static public function onPluginUpdate(Plugin $plugin, MauticFactory $factory, $metadata = null, Schema $installedSchema = null)
    {
      // ne fait rien
    }
}
