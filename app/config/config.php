<?php
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ? : realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');
defined('PUBLIC_PATH') || define('PUBLIC_PATH',  BASE_PATH. '/public');
return new \Phalcon\Config(
    [
        'database' => [
            'adapter'  => 'Mysql',
            'host'     => $_ENV['DB_HOST'] ? $_ENV['DB_HOST'] : 'mysql',
            'username' => $_ENV['DB_USERNAME'] ? $_ENV['DB_USERNAME'] : 'matchbizz',
            'port'     => $_ENV['DB_PORT'] ? $_ENV['DB_PORT'] : 3306,
            'password' => isset($_ENV['DB_PASSWORD']) ? $_ENV['DB_PASSWORD'] : 'matchbizzsecret',
            'dbname'   => $_ENV['DB_NAME'] ? $_ENV['DB_NAME'] : 'matchbizz',
            'charset'  => 'utf8',
        ],
        'application' => [
            'appDir'    => APP_PATH . '/',
            'domainUri' => $_ENV['DOMAIN_URI'] ? $_ENV['DOMAIN_URI'] : 'https://matchbizz.com', // base url
            // This allows the baseUri to be understand project paths that are not in the root directory
            // of the webpspace.  This will break if the public/index.php entry point is moved or
            // possibly if the web server rewrite rules are changed. This can also be set to a static path.
            'baseUri'    => '/',
            'logsDir'    => BASE_PATH . '/logs/',
            'logger'     => 1,// 1 = File Logger, 2 = std:err Logger,
            'modelsDir'  => APP_PATH.'/models/',
            'imgDir'     => 'img',
            'publicDir'  => PUBLIC_PATH,
            'ngAppIndex' => PUBLIC_PATH.'/webapp/index.html',
            'appUri'     => $_ENV['APP_URI']
        ],
        'view' => [
            'compiledPath'      => APP_PATH . '/compiled-templates/',
            'compiledExtension' => '.compiled',
            'compiledSeparator' => '%%',
            'compileAlways'     => false
        ],
        'emailer' => [
            'driver' 	 => 'smtp',
            'host'	 	 => 'smtp.1and1.com',
            'port'	 	 => 465,
            'encryption' => 'ssl',
            'username'   => 'support@matchbizz.com',
            'password'	 => 'MatchB1zz!!',
            'from'		 => [
                'email' => 'support@matchbizz.com',
                'name'	=> 'Matchbizz Support'
            ]
        ],
        'adminEmail' => 'matchbizz01@gmail.com',
        'jwt' => [
            'secret'     => 'XqZ24xvMPEM8sTPqAaxjxYuSs5ZacTAXF8H26WxFEkjrg5zShmsrr2hHW3zQ48j2V5FaSLYk228ufHUsHT2HzvUXcpTjwcSWk5bpUa9FWkSy4bAW6u54wKPfhnH77NRZ',
            'expires_in' => 172800,
            'algorithm'  => ['HS256']
        ],
        'firebase' => [
            'type'                        => 'service_account',
            'project_id'                  => 'matchbizz-183cb',
            'private_key_id'              => 'dee24af36394dbbb1ea068e9b2caed5352955007',
            'private_key'                 => "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC7sUS/lvzRhA+K\nXYAXbTcLDPZvjHuYc/QizQhbEa+5FrXrlGgsakyBPoLF+vSfVv5waZkOQaGQfQhx\nD9jff3Qjk3zAZvw5RtRESoqvyx74lWW3BNKrxasOPXsH8bNJwUOqSPsnzH/Viwx3\noHjPDFXTDFq0vbHC/iHqUsrVFQZ59nnlLOgqekxlThDd5XxjS/9JjTjZQReBqis5\nWuyKjgfgpYigi3Mzy4Zl23LVPMNM439Venc61D2fgu1vNsvaCs48qbQIRHdG4uq/\nNgytZKtrZ1OlXBgfSfHKkm6j6NmadssReklA5UY8NCDZaI8w6dB7aTPLMqFW9nrw\nG15PXjXXAgMBAAECggEAAwBasRWhBVivRu8n1TgR4UNbzXzZnR59kPDmaU5nGPac\nAiBlahBpGMf/H1tb/hBOEU4e/HNgOgdF00kFls1a9hatsAX1kpTnYBAxF46P+Dmr\nXrndBJIsohWpmf7rdwp4Ioj4OzKx+KA4mtEeHXF9DdrsuGcwKIYX1L9uLuofKGQZ\nitdCXquz/LrvdYvQF3jcQu8A9WmAmFZsRsNzWdzW7kT1vxYC+zU5deAMTen0JXcZ\npwvgYXWD0/eHBIntsfiwiteQTaasyDqAKcPkTw6aqJUX1UVRH8W6D5UZ6bmcrBSL\nSNByPpAg8/dFn8DAr54RrMD27ZyPDKJ/KcWHev0gAQKBgQD74UiR49Vf/7uRc5Fq\nzp4bkqmIDZBsDyktfRzXlUAGs049ZsenhtQjcK/AEtGYZyrTtoNIekkB39NPZrPW\nOmlnXgSmZ1XE2E0OgYq0GFhHB26PtzrD92QgxJEkbqSUH5xEQ574EIqAFhEYHJnz\n0k0XGFiLN2qAYStOAIen9Drn1wKBgQC+wzUj+PJxLSnm3sfxgtvg1Vvmbz3b2lD2\nmxZ7O5v6jhm7lLGz7WeW9sGjn6snIYIrGLq+DznR3C3pQPPB3Jrs2MGZDp37AVNS\nZ6vRBa4r6t9CYCt957Ca9pN9Tc9pOB0f4HzEuoXidjQ7yO3EUTynHBG2mr8sZM4n\n/BwzTFViAQKBgQDtb9HsDNywsS7MjyfOilVK4rdDVJ4G70e+5MjShJQlfNNNWFzv\nFE9X1TsnkdXYl7AsgUlCglveE3NrzwHJ+agw09UIVDLq64fAG+d3m9djpaLYgT45\nSzUtGn9D27TzGNVDGId7ioQ0+BhHJXcO+/jFw6J8IdIYkQC0eBVpAvagYwKBgHwP\n/Hl+qD4Ov1eUv7hEALgCSphc9IANReZ8Cmzu4Yxb6kRiegeoeFxn8pt1dhwm4L8i\ngCCnSlU/uuQeB+mmjWNHvPDL3talkAjhv45bAWEyiSxj2yUxD1xYzKcLeb7WbaqT\nltFi9TvI4EOZc3jf1HBiAusdTQOUM7cxcS2zkTwBAoGBAJmI584mPjJF5Oxy7I9y\nbuIyTZkaZi4BMJ2jEZTcylqVV+68q8Gi5VHxVvnLgbf5NL8NOxdRW/avDfL59c7o\ngux/OgJBjOUDcG63QshZxhM2AFYe/SNs9LBHXmDZMT2/uwEMwHZiCxqNsZky2ehi\nATxeYGiMeOQ1ZUor4JFn1Drx\n-----END PRIVATE KEY-----\n",
            'client_email'                => 'matchbizz-183cb@appspot.gserviceaccount.com',
            'client_id'                   => '04286784437280775948',
            'auth_uri'                    => 'https://accounts.google.com/o/oauth2/auth',
            'token_uri'                   => 'https://accounts.google.com/o/oauth2/token',
            'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
            'client_x509_cert_url'        => 'https://www.googleapis.com/robot/v1/metadata/x509/matchbizz-183cb%40appspot.gserviceaccount.com'
        ],
        'paypal' => [
            'clientId'     => 'AbWjzEMAN_1JY-ci1aPAJeDyfA1vAbbj3VPBG105JD_t8o3CTlL719Bv1L1Zy-dDE-EU5bZf2irWO7Tf',
            'clientSecret' => 'EHKIcgREhHQPCrsPLNZpRZlY0R4tkaTMa8crZiGBU_U1O-Rzf-zJqJrxtFeFQEu8N6AKXUD6--3GvCBP',
            'mode'         => 'live',
            'logEnabled'   => true,
            'logFileName'  => BASE_PATH . '/logs/paypal.log',
            'logLevel'     => 'INFO'
        ]
    ]
);
