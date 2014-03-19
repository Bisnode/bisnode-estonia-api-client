<?php

/**
* Copyright 2014 Bisnode Estonia, Inc.
*
* Licensed under the Apache License, Version 2.0 (the "License"); you may
* not use this file except in compliance with the License. You may obtain
* a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
* WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
* License for the specific language governing permissions and limitations
* under the License.
*/

class Bisnode_Client
{
  
  private $api_url   = 'http://localhost/api'; //'http://www.bisnode.ee/intranet/api';
  private $api_mode  = 'json';


  private $client = null,
          $debug  = true,
          $log    = array()
          ;
  
  private static $instance = null;

  /**
   * Initializing is forbidden, this class is Singletone
   * @see Bisnode::getInstance()
   */
  private function __construct()
  {
  }

  /**
   * Get instance of Bisnode client
   * @return Bisnode
   */
  public static function getInstance()
  {
    return is_null(self::$instance) 
      ? self::$instance = new self()
      : self::$instance;
  }

  
  public function login($api_key)
  {
    $result = $this->_request('login', array('key'=>$api_key));
    return isset($result->success) ? true : false;
  }

  
  public function permission()
  {
    $result = array();
    foreach ($this->_request('permission') as $item)
      $result = $item.'';
    return $result;
  }
  
  public function logout()
  {
    $result = $this->_request('logout');
    return $result->success ? true : false;
  }

  public function setMode($api_mode)
  {
    $this->api_mode = $api_mode;
    return $this;
  }
  
  
  public function getShortReport($reg_code)
  {
    $result = $this->_request('shortreport', array('id'=>$reg_code));
    return $result;
  }

  /**
   * Request API with post or without
   * @param string $url
   * @param array|string $post
   * @param callback $wrap
   * @return SimpleXMLElement or wrapped/raw data
   */
  private function _request( $url, $post = null, $wrap = 'SimpleXMLElement' )
  {
    
    $wrap = $this->api_mode == 'json' 
            ? 'json_decode' 
            : 'SimpleXMLElement';
    
    $url =  $this->api_url .'/'. $url . '.' . $this->api_mode;
    
    if (is_null($this->client)) $this->_curl_init ();
    
    $this->_log('POST '. $url .' '. print_r($post,true));
    
    curl_setopt($this->client, CURLOPT_URL, $url);
    curl_setopt($this->client, CURLOPT_POSTFIELDS, $post);
    $data = curl_exec($this->client);
    
    $this->_log($data."\n");
    
    if ($wrap)
     $data = class_exists($wrap) ? new $wrap($data) : call_user_func($wrap,$data);
    
    return $data;
    
  }
  
  
  private function _curl_init()
  {
    // create cookie file in temp directory
    $cookieJar = sys_get_temp_dir() . '/curl_cookies';
    if (!is_writable($cookieJar)) 
      file_put_contents($cookieJar, '') && chmod($cookieJar, 0777);

    // init curl
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
    $this->client = $ch;
  }
  
  /**
   * Log message for debug
   * @param string $data
   */
  private function _log($message)
  {
    echo date('Y-m-d H:i:s') . ' '. substr($message, 0, 255) ."\n";
  }
  
}
