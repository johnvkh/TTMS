<?php

function autoloader($className)
{
    $path = '../../includes/';
    $extension = '.php';
    $fullPath = $path . $className . $extension;
    if (file_exists('../../config/HeaderConfig.php')) {
        include_once '../../config/HeaderConfig.php';
    }
    
    if (file_exists($fullPath)) {
        include_once $fullPath;
    }
}

function loadUtils($className)
{
    $path = '../../utils/';
    $extension = '.php';
    $fullPath = $path . $className . $extension;

    if (file_exists($fullPath)) {
        include_once $fullPath;
    }
}

function loadConfig($className)
{
    $path = '../../config/';
    $extension = '.php';
    $fullPath = $path . $className . $extension;

    if (file_exists($fullPath)) {
        include_once $fullPath;
    }
}

function loadModel($className)
{
    $path = '../../model/';
    $extension = '.php';
    $fullPath = $path . $className . $extension;

    if (file_exists($fullPath)) {
        include_once $fullPath;
    }
}

function loadRepairModel($className)
{
    $path = '../../model/truckrepairModel/';
    $extension = '.php';
    $fullPath = $path . $className . $extension;

    if (file_exists($fullPath)) {
        include_once $fullPath;
    }
}

function loadTruckInventoryModel($className)
{
    $path = '../../model/truckinventorymodel/';
    $extension = '.php';
    $fullPath = $path . $className . $extension;

    if (file_exists($fullPath)) {
        include_once $fullPath;
    }
}

function loadTruckModel($className)
{
    $path = '../../model/TruckMenageModel/';
    $extension = '.php';
    $fullPath = $path . $className . $extension;

    if (file_exists($fullPath)) {
        include_once $fullPath;
    }
}

function BasicInfoModel($className)
{
    $path = '../../model/BasicInfoModel/';
    $extension = '.php';
    $fullPath = $path . $className . $extension;

    if (file_exists($fullPath)) {
        include_once $fullPath;
    }
}

spl_autoload_register('autoloader');
spl_autoload_register('loadUtils');
spl_autoload_register('loadConfig');
spl_autoload_register('loadModel');
spl_autoload_register('loadTruckInventoryModel');
spl_autoload_register('loadTruckModel');
spl_autoload_register('BasicInfoModel');
