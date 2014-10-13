<?php

namespace controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

    // -- Frontend controllers -------------------------------------------------------------
    $app->mount('/', new frontendController());

    // -- Backend controllers --------------------------------------------------------------
    $app->mount('/admin',  new backendController());
     
?>