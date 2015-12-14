<?php
/**
 * Configuration for differentiation of access to the routes by users roles
 */

$access = array(
    array(
        'pattern' => '/^admin_+$/',
        'roles' => array('ROLE_ADMIN')
    ),
    array(
        'pattern' => '/^cabinet+$/',
        'roles' => array('ROLE_ADMIN', 'ROLE_BOSS', 'ROLE_WORKER')
    ),
    array(
        'pattern' => '/^tasks_for_me+$/',
        'roles' => array('ROLE_ADMIN', 'ROLE_BOSS', 'ROLE_WORKER')
    ),
    array(
        'pattern' => '/^tasks_from_me+$/',
        'roles' => array('ROLE_BOSS')
    ),
    array(
        'pattern' => '/^(task_new?)|(task_create?)|(task_edit?)|(task_update?)$/',
        'roles' => array('ROLE_BOSS')
    ),
    array(
        'pattern' => '/^task_show+/',
        'roles' => array('ROLE_BOSS', 'ROLE_WORKER', 'ROLE_ADMIN')
    ),
    array(
        'pattern' => '/^task_report_+/',
        'roles' => array('ROLE_BOSS', 'ROLE_WORKER', 'ROLE_ADMIN')
    ),
    array(
        'pattern' => '/^task_change_state+/',
        'roles' => array('ROLE_BOSS', 'ROLE_WORKER', 'ROLE_ADMIN')
    ),
);