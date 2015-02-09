<?php

use Symfony\Component\HttpFoundation\Request;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.

/** TODO: this is a hack and should probably be refactord on the sever-level **/
// if ($_SERVER['SERVER_ADDR'] != '10.245.82.67') { // if we are on amazon AWS skip all the uncessary checking
// 	if (isset($_SERVER['HTTP_CLIENT_IP'])
// 	    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
// 	    || !in_array(@$_SERVER['REMOTE_ADDR'], array(
// 	        '127.0.0.1',
// 	        '::1',
// 	        '81.1.223.1',
// 	        '109.174.97.34',
// 	    ))
// 	) {
// 	    header('HTTP/1.0 403 Forbidden');
// 	    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
// 	}
// }

//$loader = require_once __DIR__.'/../app/bootstrap.php.php';
$loader = require_once __DIR__.'/../app/autoload.php';
require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
//$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);