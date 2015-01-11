<?php

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\RememberMeServiceProvider;

$app = new Application();

$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new TranslationServiceProvider());

$app->register(new Silex\Provider\SessionServiceProvider(), array(
  'session.storage.save.path' => __DIR__.'/../var/cache',
));


$app->register(new TwigServiceProvider());
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
  $twig->addExtension(new Twig_Extensions_Extension_Text($app));
    return $twig;
}));


$app->register(new DoctrineServiceProvider());
$app['db.options'] = array(
  'driver'  => 'pdo_mysql',
  'dbname'  => 'blog',
  'host'    => 'localhost',
  'user'    => 'root',
  'password'=> 'root',
  'charset' => 'utf8',
);


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
