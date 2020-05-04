<?php

namespace znagy\SimplePayV2\sdk;

/**
 *  Copyright (C) 2019 OTP Mobil Kft.
 *
 *  PHP version 7.x
 *
 *  This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  SDK
 * @package   SimplePayV21
 * @author    SimplePay IT Support <itsupport@otpmobil.com>
 * @copyright 2019 OTP Mobil Kft.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link      http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */
 
 
 /**
  * Do
  *
  * @category SDK
  * @package  SimplePayV21_SDK
  * @author   SimplePay IT Support <itsupport@otpmobil.com>
  * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
  * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
  */
class SimplePayDo extends Base
{
    protected $currentInterface = 'do';
    protected $returnArray = [];
    public $transactionBase = [
        'salt' => '',
        'orderRef' => '',
        'customerEmail' => '',
        'merchant' => '',
        'currency' => '',
        'customer' => '',
        ];

    /**
     * Constructor for do
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiInterface['do'] = '/v2/do';
    }    
    
    /**
     * Run Do
     *
     * @return array $result API response
     */
    public function runDo()
    {
        return $this->execApiCall();
    }
}


 /**
  * CardQuery
  *
  * @category SDK
  * @package  SimplePayV21_SDK
  * @author   SimplePay IT Support <itsupport@otpmobil.com>
  * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
  * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
  */
class SimplePayCardQuery extends Base
{
    protected $currentInterface = 'cardquery';
    protected $returnArray = [];
    public $transactionBase = [
        'salt' => '',
        'cardId' => '',
        'history' => false,
        'merchant' => '',
        ];

    /**
     * Constructor for cardquery
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiInterface['cardquery'] = '/v2/cardquery';
    }    
    
    /**
     * Run CardQuery
     *
     * @return array $result API response
     */
    public function runCardQuery()
    {
        return $this->execApiCall();
    }
}


 /**
  * CardCancel
  *
  * @category SDK
  * @package  SimplePayV21_SDK
  * @author   SimplePay IT Support <itsupport@otpmobil.com>
  * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
  * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
  */
class SimplePayCardCancel extends Base
{
    protected $currentInterface = 'cardcancel';
    protected $returnArray = [];
    public $transactionBase = [
        'salt' => '',
        'cardId' => '',
        'merchant' => '',
        ];

    /**
     * Constructor for cardcancel
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiInterface['cardcancel'] = '/v2/cardcancel';
    }    
    
    /**
     * Run CardCancel
     *
     * @return array $result API response
     */
    public function runCardCancel()
    {
        return $this->execApiCall();
    }
}


  /**
   * Recurring for DO
   *
   * @category SDK
   * @package  SimplePayV21_SDK
   * @author   SimplePay IT Support <itsupport@otpmobil.com>
   * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
   * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
   */
class SimplePayDoRecurring extends Base
{

    protected $currentInterface = 'dorecurring';
    
    /**
     * Constructor for dorecurring
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiInterface['dorecurring'] = '/v2/dorecurring';
    }    
    
    /**
     * Run Dorecurring
     *
     * @return array $result API response
     */
    public function runDorecurring()
    {
        return $this->execApiCall();
    }
}

