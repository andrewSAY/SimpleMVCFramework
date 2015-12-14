<?php
/**
 *Configuration of routes
 *!!!! WARNING !!!
 * Routes default, 403, 404, 500 are the service routes. They always must be configured.
 */

$routes = array(
    'default' => array(
        'controller' => 'LW\Controller\Hello',
        'action' => 'index',
        'params' => array()
    ),
    '403' => array(
        'controller' => 'LW\Controller\Errors\Error403',
        'action' => 'index',
        'params' => array()
    ),
    '404' => array(
        'controller' => 'LW\Controller\Errors\Error404',
        'action' => 'index',
        'params' => array()
    ),
    '500' => array(
        'controller' => 'LW\Controller\Errors\Error500',
        'action' => 'index',
        'params' => array()
    ),
    'introduce' => array(
        'controller' => 'LW\Controller\Hello',
        'action' => 'introduce',
        'params' => array()
    ),
    'logout' => array(
        'controller' => 'LW\Controller\Hello',
        'action' => 'logout',
        'params' => array()
    ),
    'cabinet' => array(
        'controller' => 'LW\Controller\Cabinet',
        'action' => 'index',
        'params' => array()
    ),
    'admin_panel_user_management' => array(
        'controller' => 'LW\Controller\UserManager\Main',
        'action' => 'index',
        'params' => array()
    ),
    'admin_users' => array(
        'controller' => 'LW\Controller\UserManager\User',
        'action' => 'index',
        'params' => array()
    ),
    'admin_user_new' => array(
        'controller' => 'LW\Controller\UserManager\User',
        'action' => 'new',
        'params' => array()
    ),
    'admin_user_new_in_group' => array(
        'controller' => 'LW\Controller\UserManager\User',
        'action' => 'new',
        'params' => array('groupId')
    ),
    'admin_user_create' => array(
        'controller' => 'LW\Controller\UserManager\User',
        'action' => 'create',
        'params' => array()
    ),
    'admin_user_create_in_group' => array(
    'controller' => 'LW\Controller\UserManager\User',
        'action' => 'create',
        'params' => array('groupId')
    ),
    'admin_user_show' => array(
        'controller' => 'LW\Controller\UserManager\User',
        'action' => 'show',
        'params' => array('id')
    ),
    'admin_user_edit' => array(
        'controller' => 'LW\Controller\UserManager\User',
        'action' => 'edit',
        'params' => array('id')
    ),
    'admin_user_update' => array(
        'controller' => 'LW\Controller\UserManager\User',
        'action' => 'update',
        'params' => array('id')
    ),
    'admin_user_delete_request' => array(
        'controller' => 'LW\Controller\UserManager\User',
        'action' => 'deleteRequest',
        'params' => array('id')
    ),
    'admin_user_delete' => array(
        'controller' => 'LW\Controller\UserManager\User',
        'action' => 'delete',
        'params' => array('id')
    ),
    'admin_groups' => array(
        'controller' => 'LW\Controller\UserManager\Group',
        'action' => 'index',
        'params' => array()
    ),
    'admin_group_move' => array(
        'controller' => 'LW\Controller\UserManager\Group',
        'action' => 'move',
        'params' => array()
    ),
    'admin_group_edit' => array(
        'controller' => 'LW\Controller\UserManager\Group',
        'action' => 'edit',
        'params' => array('id')
    ),
    'admin_group_edit_users_list' => array(
        'controller' => 'LW\Controller\UserManager\Group',
        'action' => 'editUsersList',
        'params' => array('id')
    ),
    'admin_group_update_users_list' => array(
        'controller' => 'LW\Controller\UserManager\Group',
        'action' => 'updateUsersList',
        'params' => array('id')
    ),
    'admin_group_new' => array(
        'controller' => 'LW\Controller\UserManager\Group',
        'action' => 'new',
        'params' => array('ownerId')
    ),
    'admin_group_create' => array(
        'controller' => 'LW\Controller\UserManager\Group',
        'action' => 'create',
        'params' => array('ownerId')
    ),
    'admin_group_update' => array(
        'controller' => 'LW\Controller\UserManager\Group',
        'action' => 'update',
        'params' => array('ownerId')
    ),
    'admin_group_delete_request' => array(
        'controller' => 'LW\Controller\UserManager\Group',
        'action' => 'deleteRequest',
        'params' => array('id')
    ),
    'admin_group_delete' => array(
        'controller' => 'LW\Controller\UserManager\Group',
        'action' => 'delete',
        'params' => array('id')
    ),
    'tasks_for_me' => array(
        'controller' => 'LW\Controller\TaskTracker',
        'action' => 'index',
        'params' => array('state')
    ),
    'tasks_from_me' => array(
        'controller' => 'LW\Controller\TaskTracker',
        'action' => 'index',
        'params' => array('state')
    ),
    'task_new' => array(
        'controller' => 'LW\Controller\TaskTracker',
        'action' => 'new',
        'params' => array()
    ),
    'task_create' => array(
        'controller' => 'LW\Controller\TaskTracker',
        'action' => 'create',
        'params' => array()
    ),
    'task_edit' => array(
        'controller' => 'LW\Controller\TaskTracker',
        'action' => 'edit',
        'params' => array('id')
    ),
    'task_update' => array(
        'controller' => 'LW\Controller\TaskTracker',
        'action' => 'update',
        'params' => array('id')
    ),
    'task_show' => array(
        'controller' => 'LW\Controller\TaskTracker',
        'action' => 'show',
        'params' => array('id')
    ),
    'task_change_state' => array(
        'controller' => 'LW\Controller\TaskTracker',
        'action' => 'changeState',
        'params' => array('id', 'state')
    ),
    'task_report_new' => array(
        'controller' => 'LW\Controller\TaskTracker',
        'action' => 'newReport',
        'params' => array('taskId')
    ),
    'task_report_create' => array(
        'controller' => 'LW\Controller\TaskTracker',
        'action' => 'createReport',
        'params' => array('taskId')
    ),
);