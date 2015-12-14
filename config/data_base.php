<?php
/**
 * Configuration of access to data source
 */
$db_config = array(
    'driver' => 'pdo_mysql',
    'dbname' => 'task_manager',
    'host' => 'localhost',
    'port' => null,
    'user' => 'root',
    'password' => 'root',
    'driverOptions' => array(
        1002 => 'SET NAMES utf8'
    ),
);