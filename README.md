# gocardless-enterprise
Client library for the GoCardless Enterprise API

You will need to create a config.php with the configuration settings (a dist file is provided for reference):
    
    [
        'baseUrl' => 'https://api-sandbox.gocardless.com/',
        'gocardlessVersion' => '2015-07-06'
        'webhook_secret' => XXXXXXXXXXXXXXXXXXXXXX
        'creditorId' => XXXXXXXXXXXXXX
        'token' => XXXXXXXXXXXXXXXXXXXXXXXXXXX
    ]

After adding the configuration, run the unit tests:

    $ php vendor/bin/phpunit
