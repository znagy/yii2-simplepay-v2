<?php

/**
 * @package   yii2-simplepay-v2
 * @author    Zoltan Nagy <zoltan.nagy@aiee.eu>
 * @copyright 2020 Zoltan Nagy 
 * @license   http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link      https://github.com/znagy/yii2-simplepay-v2
 */

namespace znagy\SimplePayV2;

use Yii;
use yii\base\Component;
use yii\console\Application;
use yii\helpers\Url;

use znagy\SimplePayV2\sdk\SimplePayStart;
use znagy\SimplePayV2\sdk\SimplePayBack;
use znagy\SimplePayV2\sdk\SimplePayIpn;
use znagy\SimplePayV2\sdk\SimplePayQuery;
use znagy\SimplePayV2\sdk\SimplePayFinish;

/**
 * @property array $sdkConfig
 */
class SimplePayV2 extends Component
{
    const SUPPORTED_CURRENCIES = ['HUF', 'EUR', 'USD'];
    const SUPPORTED_PAYMENT_METHODS = ['CARD', 'WIRE'];

    protected $sdkConfig;
    protected $defaultCurrency = 'HUF';
    protected $defaultLanguage = 'HU';
    protected $defaultPaymentMethod = 'CARD';
    protected $defaultTimeoutInSec = 600;

    /**
     * @return array $config
     */
    public function getSdkConfig()
    {
        return $this->sdkConfig;
    }

    public function generateCallbackUrl($routeDefinition)
    {
        return preg_replace('/^\/{2}/', '', Url::to($routeDefinition, 'true'));
    }

    /**
     * @param array $config
     * @throws InvalidConfigException
     */
    public function setSdkConfig(array $config)
    {
        if (!is_array($config)) {
            throw new InvalidConfigException('The options property must be any array');
        }

        if (!(Yii::$app instanceof Application)) {
            foreach ($config as $key => $value) {
                if (($key === 'URL' || preg_match('/^URLS_[A-Z_]+$/', $key)) && is_array($value)) {
                    $config[$key] = $this->generateCallbackUrl($value);
                }
            }
        }

        $this->sdkConfig = array_merge([
            //HUF
            'HUF_MERCHANT' => "",            //merchant account ID (HUF)
            'HUF_SECRET_KEY' => "",          //secret key for account ID (HUF)

            //EUR
            'EUR_MERCHANT' => "",            //merchant account ID (EUR)
            'EUR_SECRET_KEY' => "",          //secret key for account ID (EUR)

            //USD
            'USD_MERCHANT' => "",            //merchant account ID (USD)
            'USD_SECRET_KEY' => "",          //secret key for account ID (USD)

            'SANDBOX' => true,

            //common return URL
            'URL' => 'http://' . $_SERVER['HTTP_HOST'] . '/back.php',

            //optional uniq URL for events
            /*
            'URLS_SUCCESS' => 'http://' . $_SERVER['HTTP_HOST'] . '/success.php',       //url for successful payment
            'URLS_FAIL' => 'http://' . $_SERVER['HTTP_HOST'] . '/fail.php',             //url for unsuccessful
            'URLS_CANCEL' => 'http://' . $_SERVER['HTTP_HOST'] . '/cancel.php',         //url for cancell on payment page
            'URLS_TIMEOUT' => 'http://' . $_SERVER['HTTP_HOST'] . '/timeout.php',       //url for payment page timeout
            */

            'GET_DATA' => (isset($_GET['r']) && isset($_GET['s'])) ? ['r' => $_GET['r'], 's' => $_GET['s']] : [],
            'POST_DATA' => $_POST,
            'SERVER_DATA' => $_SERVER,

            'LOGGER' => true,                              //basic transaction log
            'LOG_PATH' => 'log',                           //path of log file

            //3DS
            'AUTOCHALLENGE' => true,                      //in case of unsuccessful payment with registered card run automatic challange
        ], $config);
    }

    public function getDefaultCurrency()
    {
        return $this->defaultCurrency;
    }

    public function setDefaultCurrency($currency)
    {
        $currency = strtoupper($currency);

        if (!in_array($currency, static::SUPPORTED_CURRENCIES)) {
            throw new InvalidConfigException(
                'The default currency property must have a valid value from the following: '
                . join(', ', static::SUPPORTED_CURRENCIES)
            );
        }

        $this->defaultCurrency = $currency;
    }

    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }

    public function setDefaultLanguage($language)
    {
        $this->defaultLanguage = $language;
    }

    public function getDefaultPaymentMethod()
    {
        return $this->defaultPaymentMethod;
    }

    public function setDefaultPaymentMethod($paymentMethod)
    {
        $paymentMethod = strtoupper($paymentMethod);

        if (!in_array($paymentMethod, static::SUPPORTED_PAYMENT_METHODS)) {
            throw new InvalidConfigException(
                'The default payment method property must have a valid value from the following: '
                . join(', ', static::SUPPORTED_PAYMENT_METHODS)
            );
        }

        $this->defaultPaymentMethod = $paymentMethod;
    }

    public function getDefaultTimeoutInSec()
    {
        return $this->defaultTimeoutInSec;
    }

    public function setDefaultTimeoutInSec($timeoutInSec)
    {
        $this->defaultTimeoutInSec = $timeoutInSec;
    }

    protected function generateConfigArray($config = null)
    {
        return is_array($config) ? array_merge($this->sdkConfig, $config) : $this->sdkConfig;
    }

    public function createSimplePayStart(array $config = null, $currency = '', $language = '')
    {
        $trx = new SimplePayStart;

        $sdkConfig = $this->generateConfigArray($config);
        $trx->addConfig($sdkConfig);

        $trx->addData('currency', $this->defaultCurrency);
        $trx->addData('language', $this->defaultLanguage);
        $trx->addData('methods', [ $this->defaultPaymentMethod ]);
        $trx->addData('timeout ', date("c", time() + $this->defaultTimeoutInSec));
        $trx->addData('url', $sdkConfig['URL']);

        return $trx;
    }

    public function createSimplePayBack(array $config = null)
    {
        $trx = new SimplePayBack;

        $trx->addConfig($this->generateConfigArray($config));

        return $trx;
    }

    public function createSimplePayIpn(array $config = null)
    {
        $trx = new SimplePayIpn;

        $trx->addConfig($this->generateConfigArray($config));

        return $trx;
    }

    public function createSimplePayQuery(array $config = null)
    {
        $trx = new SimplePayQuery;

        $trx->addConfig($this->generateConfigArray($config));

        return $trx;
    }

    public function createSimplePayFinish(array $config = null, $currency = '')
    {
        $trx = new SimplePayFinish;

        $trx->addConfig($this->generateConfigArray($config));

        $trx->transactionBase['currency'] = $this->defaultCurrency;

        return $trx;
    }

    public function createSimplePayRefund(array $config = null, $currency = '')
    {
        $trx = new SimplePayRefund;

        $trx->addConfig($this->generateConfigArray($config));

        $trx->addData('currency', $this->defaultCurrency);

        return $trx;
    }

}
