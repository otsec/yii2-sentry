Yii2 Sentry Component
=====================

Automatically catch all PHP and JS errors and send them to [Sentry](https://sentry.io). 

Supports [PHP SDK](https://github.com/getsentry/sentry-php) and [JavaScript Client](https://github.com/getsentry/raven-js).

Installation
------------

Install via [composer](http://getcomposer.org/download/).

```bash
composer require --prefer-dist otsec/yii2-sentry
```

Add component to your application configuration:

```php
return [
    'components' => [
    	...
    
        'sentry' => [
            'class' => 'otsec\yii2\sentry\Raven',
            'dsn' => 'https://****@sentry.io/12345',
        ],
    ],
];
```

Do not forget to bootstrap it to register event handlers.

```php
return [
    'bootstrap' => ['sentry'],
    
    ...
];
```

You are ready to go!

Configuration
-------------

There are a lot of options profived.

```php
'components' => [
	...
	
    'sentry' => [
	    ...
	    
	    // Register Raven_Client error and exception handler on init.
	    // Enabled by default
        'enableErrorHandler' => true,

        // Options will be passed to Raven_Client
        'options' => [],
        
        // Cathing JS errors is disabled by default.
        'enableClientScript' => false,
        
        // Options for client library.
        'clientOptions' => [],
        
        // DSN for client libary. 
        // Will be extracted from private DSN if empty.
        'publicDsn' => null,
        
        // Client library will be loaded from CDN by default.
        // You can use any other asset bundle if you want. 
        'assetBundle' => 'otsec\yii2\sentry\RavenCdnAsset',
        
        // Asset bundles for Bower and NPM already created but you have to 
        // install assets before you will use it.
        // 'assetBundle' => 'otsec\yii2\sentry\RavenBowerAsset',
        // 'assetBundle' => 'otsec\yii2\sentry\RavenNpmAsset',
    ],
],
```

Usage
-----

Component registers error handler by default. 
It will catch any appeared error or not captured exception.

Captured exceptions can be handled manually.

```php
try {
	throw new Exception('Oh, shit!');
} catch (Exception $e) {
	Yii::$app->sentry->captureException($e, ['extra' => 'data']);
}
```

There are some methods to work with context.

```php
Yii::$app->sentry->extraContext($data);
Yii::$app->sentry->tagsContext($data);
Yii::$app->sentry->userContext($data);
Yii::$app->sentry->clearContext();
```

Or you can get access to `Raven_Client` itself.

```php
$ravenClient = Yii::$app->sentry->getClient();
```

