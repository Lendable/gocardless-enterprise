# ABANDONED
We recommend that the official package from Gocardless is used: https://packagist.org/packages/gocardless/gocardless-pro
___

# Unofficial integration with GoCardless Enterprise API
Client library for the GoCardless Enterprise API

You will need to create a config.php with the configuration settings (a [dist file](https://github.com/Lendable/gocardless-enterprise/blob/master/config.dist.php) is provided for reference):
    
    <?php
    
    return [
        'baseUrl' => 'https://api-sandbox.gocardless.com/',
        'gocardlessVersion' => '2015-07-06',
        'webhook_secret' => XXXXXXXXXXXXXXXXXXXXXX,
        'creditorId' => XXXXXXXXXXXXXX,
        'token' => XXXXXXXXXXXXXXXXXXXXXXXXXXX,
    ];

After adding the configuration, run the unit tests:

    $ php vendor/bin/phpunit
