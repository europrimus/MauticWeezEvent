<?php
// plugins/MauticWeezeventBundle/Model/weezeventAPI.php

namespace MauticPlugin\MauticWeezeventBundle\Model;

//use Mautic\CoreBundle\Model\CommonModel;
use \Mautic\CoreBundle\Model\AbstractCommonModel;

class APIModel extends AbstractCommonModel
{
    private $api_token='';
    private $api_key = '';
    private $api_email ='';
    private $api_password = '';
    private $headers = array(
             "content-type: application/x-www-form-urlencoded;charset=utf-8"
          );

    public function __construct()
    {
      $this->api_token=$this->getToken();
    }

    /**
     * Get access token
     *
     * @param void
     */
    private function getToken()
    {
      // from https://aide.weezevent.com/article/119-api
      $url = 'https://api.weezevent.com/auth/access_token';
      $ch=$this->initCurl($url);
      curl_setopt($ch, CURLOPT_POST, true);
// on ajoute l'identifiant et mot de passe
      curl_setopt($ch, CURLOPT_POSTFIELDS, '&username='.$this->api_email.
          '&password='.$this->api_password.
          '&api_key='.$this->api_key);
// on execute
      $res = curl_exec($ch);
      $res = json_decode($res);
      dump($res);
      if( property_exists($res, "accessToken") ){
        return $res->accessToken;
      }else{
        return false;
      }

    }


    private function initCurl($url)
    {
// on prepare la requette
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
      return $ch;
    }

    public function getEvents()
    {

      $url = 'https://api.weezevent.com/events?&api_key='.$this->api_key.
        '&access_token='.$this->api_token.'&include_without_sales=false';
      $ch=$this->initCurl($url);
      $events = curl_exec($curl);
      $count = 0;
      $events = json_decode($events);
      return $events;
    }
}
