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
class Bisnode_Client {

  private $api_key = '',
          $api_url = 'http://www.bisnode.ee/intranet/api',
          $api_mode = 'xml',
          $client = null,
          $debug = false,
          $raw_data = ''
  ;
  private static $instance = null;

  /**
   * Initializing is forbidden, this class is Singletone
   * @see Bisnode::getInstance()
   */
  private function __construct()
  {
    // do nothing
  }

  /**
   * Get instance of Bisnode client
   * @return Bisnode_Client
   */
  public static function getInstance()
  {
    return is_null(self::$instance) ? self::$instance = new self() : self::$instance;
  }

  public function __toString()
  {
    return $this->asIs();
  }
  
  /**
   * Get company short report
   * @param type $reg_code
   * @return Bisnode_Client
   */
  public function getListOfCompanies()
  {
    $this->_request('listcompanieszipped');
    $this->raw_data = gzinflate(substr($this->raw_data,10,-8)); 
    return $this;
  }

  
  
  /**
   * Get company short report
   * @param type $reg_code
   * @return Bisnode_Client
   */
  public function getShortReport($reg_code)
  {
    $this->_request('shortreport', array('id' => $reg_code));
    return $this;
  }

  /**
   * Get company kmkr data from maksuamet
   * @param type $reg_code
   * @return Bisnode_Client
   */
  public function getKmkrData($reg_code)
  {
    $this->_request('kmkrData', array('id' => $reg_code));
    return $this;
  }

  /**
   * Get tax arrears information from Maksuamet
   * @param type $reg_code
   * @return Bisnode_Client
   */
  public function getTaxArrears($reg_code)
  {
    $this->_request('taxArrears', array('id' => $reg_code));
    return $this;
  }

  /**
   * Get list of documents from Ariregister
   * @param type $reg_code
   * @return Bisnode_Client
   */
  public function getArListDocs($reg_code)
  {
    $this->_request('arListDocs', array('id' => $reg_code));
    return $this;
  }

  /**
   * Get list of annual reports from Ariregister
   * @param type $reg_code
   * @return Bisnode_Client
   */
  public function getArListReports($reg_code)
  {
    $this->_request('arListReports', array('id' => $reg_code));
    return $this;
  }

  /**
   * Get raw data from last request
   * @return string
   */
  public function asIs()
  {
    return $this->raw_data;
  }

  /**
   * Get XML data from last request
   * @return SimpleXMLElement
   */
  public function asXml()
  {
    return simplexml_load_string($this->asIs(), null, LIBXML_NOCDATA);
  }

  
  /**
   * Format result as JSON
   * @return string
   */
  public function asJson()
  {
    $json = json_encode($this->asXml());
    return str_replace('{}', 'null', $json);
  }
  
  
  /**
   * Get data last result as Object
   * @return stdClass
   */
  public function asObject()
  {
    return json_decode($this->asJson());
  }

  /**
   * Get data from last request as Array
   * @return array
   */
  public function asArray()
  {
    return json_decode($this->asJson(), true);
  }

  /**
   * Change api_key
   * @param type $api_key must be generated in www.bisnode.ee/intranet/api
   * @return Bisnode_Client
   */
  public function setApiKey($api_key)
  {
    $this->api_key = $api_key;
    return $this;
  }

  /**
   * Change api URL
   * @param $url string
   * @return Bisnode_Client
   */
  public function setApiUrl($url)
  {
    $this->api_url = $url;
    return $this;
  }

  /**
   * Request API with post or without
   * @param string $url
   * @param array|string $post
   * @return nothing
   */
  private function _request($url, $post = null)
  {
    if (is_null($this->client))
      $this->_curl_init();

    $url = $this->api_url . '/' . $this->api_key . '/' . $url . '.' . $this->api_mode;

    $this->_log('POST ' . $url . ' ' . print_r($post, true));

    curl_setopt($this->client, CURLOPT_URL, $url);
    curl_setopt($this->client, CURLOPT_POSTFIELDS, $post);
    $this->raw_data = curl_exec($this->client);

    $this->_log($this->raw_data . "\n");
  }

  /**
   * Init cURL
   * @return type
   */
  private function _curl_init()
  {
    if ($this->client)
      return;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $this->client = $ch;
  }

  /**
   * Log message for debug
   * @param string $data
   */
  private function _log($message)
  {
    if (!$this->debug)
      return;
    echo date('Y-m-d H:i:s') . ' ' . substr($message, 0, 255) . "\n";
  }

}
