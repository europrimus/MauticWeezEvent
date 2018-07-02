<?php

namespace MauticPlugin\MauticWeezeventBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;

class WeezeventIntegration extends AbstractIntegration
{
    public function getName()
    {
        return 'Weezevent';
    }

    /**
     * Return's authentication method such as oauth2, oauth1a, key, etc.
     *
     * @return string
     */
    public function getAuthenticationType()
    {
        // Just use none for now and I'll build in "basic" later
        return 'none';
    }


  /**
   * Return array of key => label elements that will be converted to inputs to
   * obtain from the user.
   *
   * @return array
   */
  public function getRequiredKeyFields()
  {
    return [
      'Weezevent_login' => 'plugin.weezevent.config.Weezevent_login',
      'Weezevent_password' => 'plugin.weezevent.config.Weezevent_password',
      'Weezevent_API_key' => 'plugin.weezevent.config.Weezevent_API_key',
    ];
  }

    /**
   * {@inheritdoc}
   */

  public function getAvailableLeadFields($settings = [])
  {
    return [
      'firstname'  => ['type' => 'string', 'label' => "firstname", 'required' => true ],
      'lastname'   => ['type' => 'string', 'label' => "lastname", 'required' => true ],
      'email'      => ['type' => 'string', 'label' => "email", 'required' => true ],
      'event'      => ['type' => 'string', 'label' => "event name", 'required' => true ],
    ];
  }

}
