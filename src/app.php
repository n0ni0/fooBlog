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
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\RememberMeServiceProvider;

$app = new Application();

$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new FormServiceProvider()); // necesario para formularios
$app->register(new TranslationServiceProvider());  // necesario para formularios


$app->register(new Silex\Provider\SessionServiceProvider(), array(
  'session.storage.save.path' => __DIR__.'/../var/cache',
));


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
  'driver'  => 'pdo_mysql',
  'dbname'  => 'blog',
  'host'    => 'localhost',
  'user'    => 'root',
  'password'=> 'root',
  'charset' => 'utf8',
);


// -- FIREWALL ----------------------------------------------------------------------------------------
$app->register(new SecurityServiceProvider());
$app['security.encoder.digest'] = $app->share(function ($app) {
  return new MessageDigestPasswordEncoder('sha1', false, 1);
});

$app['security.firewalls'] = array(
  'admin'         => array(
    'pattern'     => '^/admin',
    'form'        => array('login_path'  => '/login', 'check_path' => '/admin/login_check'),
    'logout'      => array('logout_path' => '/admin/logout'),
    'remember_me' => array('key'         => '123456789', 'always_remember_me' => true ),
    'users'       => $app->share(function() use ($app)
    {
      return new users\userProvider($app['db']);
    }),
 ),
);


$app->register(new Silex\Provider\RememberMeServiceProvider());

// -- CORREO ---------------------------------------------------------------------------------------------
$app->register(new SwiftmailerServiceProvider());
$app['swiftmailer.options'] = array(
	'host'		 => 'smtp.gmail.com',
	'port'		 => 465,
	'username'	 => 'user@email.com',
	'password'	 => 'password',
	'encryption' => 'ssl',
	'auth_mode'	 => 'login'
);



return $app;
