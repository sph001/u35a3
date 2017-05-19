<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 3:20 PM
 */
include_once 'Helper.php';
include_once 'RouteTable.php';
include_once 'MasterController.php';

$uri = $_SERVER['REQUEST_URI'];
if ($uri == '/'){
    $uri = RouteTable::$DefaultPath;
}
$path = array_values(array_filter(explode("/", $uri)));
Helper::Print($_SERVER['SERVER_NAME']);
Helper::Print(Helper::GetBaseUrl());
new MasterController($path);