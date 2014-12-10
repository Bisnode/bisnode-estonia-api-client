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
class Bisnode_Soap_Client {

  public 
    $url    = 'http://in.bisnode.fi/soap.wsdl',
    $token  = null,
    $client = null,
    $obj    = null,
    $req    = null,
    $res    = null;
          
  
  private static $instance = null;

  /**
   * Forbidden
   */
  private function __construct( )
  {
    $this->client = new SoapClient($this->url, array(
          'cache_wsdl' => WSDL_CACHE_NONE, 
          'trace'      => true,
          /*'classmap' => array(
            'managersType' => 'PropartnerXmlCollectionType',
            'activitiesType' => 'PropartnerXmlCollectionType',
          ),*/
          ));
  }

  
  public function __toString()
  {
    return $this->asIs();
  }
  
  /**
   * Get instance of SOAP client
   * @return Bisnode_Soap_Client
   */
  public static function getInstance( )
  {
    return is_null(self::$instance) 
      ? self::$instance = new self( )
      : self::$instance;
  }

  
  public function getFunctions()
  {
    return $this->client->__getFunctions();
  }
  
  /**
   * Get user profile information and api keys
   * @param type $email email
   * @param type $pass  password
   * @return Bisnode_Soap_Client
   */
  public function getProfile( $email, $pass)
  {
    $this->_request('getProfile', array('email' => $email, 'pass' => $pass));
    return $this;
  }
    
  public function getCreditReport( $country, $reg_code  )
  {
    $this->_request('getCreditReport', array(
        'token'=>$this->token, 
        'country' => $country, 
        'reg_code' => $reg_code,
    ));
    return $this;
  }
    
  public function getPropartnerCompany( $country, $reg_code  )
  {
    $this->_request('getPropartnerCompany', array(
        'token'=>$this->token, 
        'country' => $country, 
        'reg_code' => $reg_code,
    ));
    return $this;
  }
    
  public function getKmkrData( $country, $reg_code  )
  {
    $this->_request('getKmkrData', array(
        'token'=>$this->token, 
        'country' => $country, 
        'reg_code' => $reg_code,
    ));
    return $this;
  }
  
  public function getTaxArrears( $country, $reg_code  )
  {
    $this->_request('getTaxArrears', array(
        'token'=>$this->token, 
        'country' => $country, 
        'reg_code' => $reg_code,
    ));
    return $this;
  }
  
  public function getFreeInfo( $country, $reg_code  )
  {
    $this->_request('getFreeInfo', array(
        'token'=>$this->token, 
        'country' => $country, 
        'reg_code' => $reg_code,
    ));
    return $this;
  }
  
  public function searchCompany($country, $name)
  {
    $this->_request('searchCompany', array(
        'token'=>$this->token, 
        'country' => $country, 
        'name' => $name,
    ));
    return $this;
  }
    
  public function getRequestHeaders()
  {
    return $this->reh;
  }
    
  public function getResponse()
  {
    return $this->res;
  }
  
  public function getRequest()
  {
    return $this->req;
  }
  
  
  /**
   * Get raw data from last request
   * @return string
   */
  public function asIs()
  {
    return $this->res;
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
   * Get data last result as Object
   * @return stdClass
   */
  public function asObject()
  {
    return $this->obj;
  }
  
  /**
   * Format result as JSON
   * @return string
   */
  public function asJson()
  {
    $json = json_encode($this->obj);
    return str_replace('{}', 'null', $json);
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
   * Change api URL
   * @param $url string
   * @return Bisnode_Soap_Client
   */
  public function setUrl($url)
  {
    $this->url = $url;
    return $this;
  }

  /**
   * Change api URL
   * @param $value string
   * @return Bisnode_Soap_Client
   */
  public function setToken($value)
  {
    $this->token = $value;
    return $this;
  }

  /**
   * Request API with post or without
   * @param string $url
   * @param array|string $post
   * @return nothing
   */
  private function _request($method, $params)
  {
    $this->obj = $this->client->__soapCall($method, $params);
    $this->req = $this->client->__getLastRequest();
    $this->res = $this->client->__getLastResponse();    
    $this->reh = $this->client->__getLastRequestHeaders();    
  }
  

}
