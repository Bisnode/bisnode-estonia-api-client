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
  class Bisnode_Soap_Client
  {

    private
        $url = 'https://in.bisnode.ee/soap.wsdl',
        $token = null,
        $client = null,
        $obj = null,
        $req = null,
        $res = null;


    private static $instance = null;

    /**
     * Forbidden
     */
    private function __construct()
    {
      $this->client = new SoapClient($this->url, array(
          'cache_wsdl' => WSDL_CACHE_NONE,
          'trace'      => true,
      ));
    }


    public function __toString()
    {
      return $this->asIs();
    }

    /**
     * Get instance of SOAP client
     *
     * @return Bisnode_Soap_Client
     */
    public static function getInstance()
    {
      return is_null(self::$instance)
          ? self::$instance = new self()
          : self::$instance;
    }


    public function getFunctions()
    {
      return $this->client->__getFunctions();
    }

    /**
     * Get user profile information and api keys
     *
     * @param type $email email
     * @param type $pass  password
     * @return Bisnode_Soap_Client
     */
    public function getProfile($email, $pass)
    {
      $this->_request('getProfile', array(
          'email' => $email,
          'pass'  => $pass
      ));
      return $this;
    }

    /**
     * Get user profile information and api keys
     *
     * @param type $email email
     * @param type $pass  password
     * @return Bisnode_Soap_Client
     */
    public function getProfileFinland($email, $pass)
    {
      $this->_request('getProfileFinland', array(
          'email' => $email,
          'pass'  => $pass
      ));
      return $this;
    }


    /**
     * Get short credit report
     *
     * @param type $country  country (EST only)
     * @param type $reg_code registration number of company
     * @return Bisnode_Soap_Client
     */
    public function getCreditReport($country, $reg_code)
    {
      $this->_request('getCreditReport', array(
          'token'    => $this->token,
          'country'  => $country,
          'reg_code' => $reg_code,
      ));
      return $this;
    }

    /**
     * Dummy request for credit report
     *
     * @param type $country  country (EST only)
     * @param type $reg_code registration number of company
     * @return Bisnode_Soap_Client
     */
    public function getLightCreditReport($country, $reg_code)
    {
      $this->_request('getLightCreditReport', array(
          'token'    => $this->token,
          'country'  => $country,
          'reg_code' => $reg_code,
      ));
      return $this;
    }

    /**
     * Get KMKR information
     *
     * @param type $country  country (EST only)
     * @param type $reg_code registration number of company
     * @return Bisnode_Soap_Client
     */
    public function getKmkrData($country, $reg_code)
    {
      $this->_request('getKmkrData', array(
          'token'    => $this->token,
          'country'  => $country,
          'reg_code' => $reg_code,
      ));
      return $this;
    }

    /**
     * Get tax arrears information of estonian company from Maksuamet
     *
     * @param type $country  country (EST only)
     * @param type $reg_code registration number of company
     * @return Bisnode_Soap_Client
     */
    public function getTaxArrears($country, $reg_code)
    {
      $this->_request('getTaxArrears', array(
          'token'    => $this->token,
          'country'  => $country,
          'reg_code' => $reg_code,
      ));
      return $this;
    }

    /**
     * Get free information about company
     *
     * @param type $country  country (EST only)
     * @param type $reg_code registration number of company
     * @return Bisnode_Soap_Client
     */
    public function getFreeInfo($country, $reg_code)
    {
      $this->_request('getFreeInfo', array(
          'token'    => $this->token,
          'country'  => $country,
          'reg_code' => $reg_code,
      ));
      return $this;
    }

    /**
     * Search for company registry number by name
     *
     * @param type $country country (EST,LTU,LVA)
     * @param type $name    Name of company
     * @return Bisnode_Soap_Client
     */
    public function searchCompany($country, $name)
    {
      $this->_request('searchCompany', array(
          'token'   => $this->token,
          'country' => $country,
          'name'    => $name,
      ));
      return $this;
    }

    /**
     * Get last Annual report PDF file and analysis
     *
     * @param type $country  Country (EST only)
     * @param type $reg_code Registration number of company
     * @return Bisnode_Soap_Client
     */
    public function getLastAnnualReportPdf($country, $reg_code)
    {
      $this->_request('getLastAnnualReportPdf', array(
          'token'    => $this->token,
          'country'  => $country,
          'reg_code' => $reg_code,
      ));
      return $this;
    }

    /**
     * Get last annual report XBRL file and analysis
     *
     * @param type $country  Country (EST only)
     * @param type $reg_code Registration number of company
     * @return Bisnode_Soap_Client
     */
    public function getLastAnnualReportXbrl($country, $reg_code)
    {
      $this->_request('getLastAnnualReportXbrl', array(
          'token'    => $this->token,
          'country'  => $country,
          'reg_code' => $reg_code,
      ));
      return $this;
    }

    /**
     * Get list of annual report doc_id, doc_type and year
     *
     * @param type $country  Country (EST only)
     * @param type $reg_code Registration number of company
     * @return Bisnode_Soap_Client
     */
    public function listAnnualReport($country, $reg_code)
    {
      $this->_request('listAnnualReport', array(
          'token'    => $this->token,
          'country'  => $country,
          'reg_code' => $reg_code,
      ));
      return $this;
    }

    /**
     * Get AAA Rating
     *
     * @param $country  Country (EST only)
     * @param $reg_code Registration number of company
     * @return Bisnode_Soap_Client
     */
    public function getAaaRating($country, $reg_code)
    {
      $this->_request('getAaaRating', array(
          'token'    => $this->token,
          'country'  => $country,
          'reg_code' => $reg_code,
      ));
      return $this;
    }

    /**
     * Get annual report of specific type and year, with analysis
     *
     * @param $country     Country (EST only)
     * @param $reg_code    Registration number of company
     * @param $doc_type    X - xbrl, A - pdf, D - digidoc
     * @param $annual_year Annual year
     * @return Bisnode_Soap_Client
     */
    public function getAnnualReport($country, $reg_code, $doc_type, $annual_year)
    {
      $this->_request('getAnnualReport', array(
          'token'       => $this->token,
          'country'     => $country,
          'reg_code'    => $reg_code,
          'doc_type'    => $doc_type,
          'annual_year' => $annual_year,
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
     *
     * @return string
     */
    public function asIs()
    {
      return $this->res;
    }

    /**
     * Get XML data from last request
     *
     * @return DOMDocument
     */
    public function asXml()
    {
      $xml = new DOMDocument;
      $xml->loadXML($this->asIs());
      return $xml;
    }

    /**
     * Get data last result as Object
     *
     * @return stdClass
     */
    public function asObject()
    {
      return $this->obj;
    }

    /**
     * Format result as JSON
     *
     * @return string
     */
    public function asJson()
    {
      $json = json_encode($this->obj);
      return str_replace('{}', 'null', $json);
    }

    /**
     * Get data from last request as Array
     *
     * @return array
     */
    public function asArray()
    {
      return json_decode($this->asJson(), true);
    }


    /**
     * Change api URL
     *
     * @param $url string
     * @return Bisnode_Soap_Client
     */
    public function setUrl($url)
    {
      $this->url = $url;
      $this->__construct();
      return $this;
    }

    /**
     * Change api URL
     *
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
     *
     * @param string $url
     * @param array|string $post
     * @return nothing
     */
    private function _request($method, $obj)
    {
      $this->obj = $this->client->$method($obj);
      $this->req = $this->client->__getLastRequest();
      $this->res = $this->client->__getLastResponse();
      $this->reh = $this->client->__getLastRequestHeaders();
    }


  }
