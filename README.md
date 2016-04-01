# Authentication
This package cover to Authentication any request before connecting to your API. And it's also probably use to authentication your form before data will submit to your DB.


# How to use this package
<?php

namespace Signature\Authentication\Test;

use Signature\Authentication\Authentication;
use Signature\Authentication\Token;
use Signature\Authentication\Request;

spl_autoload_extensions('.php');
spl_autoload_register();

$data = [ 'Fullname' => 'Harrieanto',  'E-mail' => 'harrieanto31@yahoo.com' ];

$auth = new \Signature\Authentication\Authentication;

$request = $auth->request('POST',  'site/api',  $data);

$token = $auth->token('my_key',  'my-secret');

$signed = $request->sign($token);

$integrated = array_merge($data,  $signed);

//echo json_encode($integrated);

var_dump($integrated);
