<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require '../vendor/autoload.php';

new mathiasgrimm\X\X();

// spl_autoload_register(function($sClass){
//     if (strpos($sClass, 'mathiasgrimm\\htmlwrappers\\') === 0) {
//         $sClass = str_replace('mathiasgrimm\\htmlwrappers\\', '', $sClass);
//         $sPath  = "../src/{$sClass}.php";

//         require $sPath;  
//     } 
// });

// function __autoload($sClass)
// {
//     xd($sClass);
//     require "../src/{$sClass}.php"; 
// }



