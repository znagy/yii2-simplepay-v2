# yii2-simplepay-v2
SimplePay 2.x by OTP Mobil Extension for Yii 2

## Installation
The extension can be installed via Composer.

### Adding dependency
Add an entry for the extension in the require section in your composer.json:
```
"znagy/yii2-simplepay-v2": "dev-master"
```
After this, you can execute `composer update` in your project directory to install the extension.

### Enabling the component
You can use the classes of the SDK right away. If you would like to configure the SDK globally (not specifying the configuration every time you create an instance of one of the classes), you can use the built-in component.

The component must be enabled in Yii's configuration by adding an entry for it in the components section, for example:
```
'simplePayV2' => [
    'class' => 'znagy\SimplePayV2\SimplePayV2',
    'sdkConfig' => [
        'HUF_MERCHANT' => '<MERCHANT_ID>',
        'HUF_SECRET_KEY' => '<SECRET_KEY>',
        'URL' => ['/order/status'],
        'LOGGER' => true,
        'LOG_PATH' => '@app/logs',
    ],
    'defaultCurrency' => 'HUF',
    'defaultLanguage' => 'HU',
    'defaultPaymentMethod' => 'CARD',
    'defaultTimeoutInSec' => '600',
],
```

Please refer to the [SimplePay SDK documentation](http://simplepartner.hu/download.php?target=dochu) for more information on how to configure the SDK.  
For URL configuration (e.g. URL, URLS) you can use a Yii style route which will be processed by a Url::to() call.

You can use the component for example the following way:
```php
$trx = Yii::$app->get('simplePay')->createSimplePayIpn();

if ($trx->isIpnSignatureCheck($json)) {
    // TODO: finalize order
    $trx->runIpnConfirm();
} else {
    echo 'IPN request is not valid';
}
```
Every Simple class has a corresponding function in the component to make them easy to use with the global configuration.

The above example would look like this without the component:
```php
$config = Yii::$app->params['simplePayV2Config'];

$trx = new SimplePayIpn;
$trx->addConfig($config);

if ($trx->isIpnSignatureCheck($json)) {
    // TODO: finalize order
    $trx->runIpnConfirm();
} else {
    echo 'IPN request is not valid';
}
```
Where the ``$config`` is the array of options for the SDK, possibly stored in the application's params.php.
