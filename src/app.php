<?php

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider; // base de datos
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TwigServiceProvider; // Twig
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SecurityServiceProvider; // firewall
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder; // firewall
use Silex\Provider\TranslationServiceProvider; 
use Silex\Provider\SwiftmailerServiceProvider;



$app = new Application();

$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new FormServiceProvider()); // necesario para formularios
$app->register(new TranslationServiceProvider());  // necesario para formularios


// -- TWIG -------------------------------------------------------------------------------------------
$app->register(new TwigServiceProvider());
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    // add custom globals, filters, tags, ...
	$twig->addExtension(new Twig_Extensions_Extension_Text($app));
	
    return $twig;
}));


// -- DOCTRINE ---------------------------------------------------------------------------------------
$app->register(new DoctrineServiceProvider());
$app['db.options'] = array(
	'driver'	=> 'pdo_sqlite',
	'path'		=> __DIR__.'/../config/schema.sqlite',
);



// -- FIREWALL ----------------------------------------------------------------------------------------
$app->register(new SecurityServiceProvider());
$app['security.encoder.digest'] = $app->share(function ($app) {
	// algoritmo SHA-1 con 1 iteración y sin codificar en base64
	return new MessageDigestPasswordEncoder('sha1', false, 1);
});

$app['security.firewalls'] = array(
	'admin' => array(
		'pattern'	=> '^/admin', // el firewall abarca todas las páginas /admin
		'http'		=> true,  // usa el menu de autenticación del navegador
		'users'		=> array(
			// user: admin; password 1234 -- la contraseña debe ir encriptada por seguridad para no verla en este archivo
			'admin' => array('ROLE_ADMIN', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220' ),
		),
	),
);

// -- CORREO ---------------------------------------------------------------------------------------------
$app->register(new SwiftmailerServiceProvider());
$app['swiftmailer.options'] = array(
	'host'		 => 'smtp.gmail.com',
	'port'		 => 465,
	'username'	 => 'ajimenez.bf@gmail.com',
	'password'	 => 'achuparla',
	'encryption' => 'ssl',
	'auth_mode'	 => 'login'
);



return $app;
