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
 * @package   SimplePayV2
 * @author    SimplePay IT Support <itsupport@otpmobil.com>
 * @copyright 2019 OTP Mobil Kft.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link      http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */
 

 /**
  * RecurringStore
  *
  * @category SDK
  * @package  SimplePayV21_SDK
  * @author   SimplePay IT Support <itsupport@otpmobil.com>
  * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
  * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
  */
class RecurringStore
{
    protected $tokensFolderName = 'recurring';
    public $storingType = 'file';
    public $request ;
    
    /**
     * Write tokens into file
     *
     * @return void
     */        
    public function storeNewTokens()
    {
        if (!isset($this->transaction['tokens']) || count($this->transaction['tokens']) == 0) {
            return false;
        }
        
        $store = array();
        $counter = 1;
        foreach ($this->transaction['tokens'] as $token) {
            $store[] = array(
            'id' => $counter,
            'merchant' => $this->transaction['merchant'],
            'orderRef' => $this->transaction['orderRef'],
            'transactionId' => $this->transaction['transactionId'],
            'tokenRegDate' => @date("c", time()),
            'customerEmail' => $this->transactionBase['customerEmail'],
            'token' => $token,
            'until' => $this->transactionBase['recurring']['until'],
            'maxAmount' => $this->transactionBase['recurring']['maxAmount'],
            'currency' => $this->transaction['currency'],
            'tokenState' => 'stored'
            );        
            $counter++;
        }
        $dataToStore = json_encode($store);
        file_put_contents($this->tokensFolderName . '/' . $this->transaction['transactionId'] . '.tokens', $dataToStore, LOCK_EX);          
    }
  
    /**
     * Get tokens from file
     *
     * @return string $table HTML table populated with tokens data
     */        
    public function getTokens()
    {
        $tokensObj = json_decode(file_get_contents($this->tokensFolderName . '/' . $this->request['rContent']['t'] . '.tokens', true));
        $tokens = $this->convertToArray($tokensObj);
        
        $table = '';
        foreach ($tokens as $token) {
            $table .= '<b>' . $token['id'] . '</b></br> ' 
            . '<b>Token:</b> <a href="dorecurring.php?token=' . $token['token'] . '&merchant=' . $this->request['rContent']['m'] .'">' . $token['token'] . '</a></br>'
            . '<b>Until:</b> ' . $token['until'] . '</br> '
            . '<b>Max. amount:</b> ' . $token['maxAmount'] . '</br> '
            . '<b>Currency:</b> ' . $token['currency'] . ' </br></br>';     
        }    
        return $table;    
    }

    /**
     * Checks token existance
     *
     * @return boolean
     */        
    public function isTokenExists()
    {
        if (file_exists($this->tokensFolderName . '/' . $this->request['rContent']['t'] . '.tokens')) {
            return true;
        }
        return false;    
    }
    
    /**
     * Convert object to array
     *
     * @param object $obj Object to transform
     *
     * @return array $new Result array
     */
    protected function convertToArray($obj)
    {
        if (is_object($obj)) {
            $obj = (array) $obj;
        }
        $new = $obj;
        if (is_array($obj)) {
            $new = array();
            foreach ($obj as $key => $val) {
                $new[$key] = $this->convertToArray($val);
            }
        }
        return $new;
    }
}
