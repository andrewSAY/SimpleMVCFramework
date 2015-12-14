<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 03.09.15
 * Time: 10:37
 */

namespace LW\Controller;


use LW\Core\ControllerBase;
use LW\Model\Dictionary\Dictionary;
use LW\Model\Entity\TaskTracker\Task;
use LW\Model\Entity\TaskTracker\TaskReport;
use LW\Model\Entity\User;
use LW\Model\FormProvider;
use LW\Model\TaskTracker\TaskManager;
use LW\Model\UserManagement\Manager;

class TaskTrackerController extends ControllerBase
{
    public function indexAction($state)
    {
        $manager = new TaskManager($this->dataProvider->getEntityManager());
        $taskList = array();
        $caption = '';
        $dictionary = new Dictionary();

        $currentFilter = $state;
        $list = array(
            $this->router->getUrlByRoute($this->router->getCurrentRoute(), array('state' => 'ALL'), false) => 'Все состояния'
        );
        foreach ($dictionary->taskStates()->getList() as $key => $value)
        {
            $url = $this->router->getUrlByRoute($this->router->getCurrentRoute(), array('state' => $key), false);
            if ($state == $key)
            {
                $currentFilter = $url;
            }
            $list[$url] = $value;
        }

        $form = (new FormProvider())->getTaskFilter($currentFilter, $list);

        if ($this->router->getCurrentRoute() == 'tasks_from_me')
        {
            $taskList = $manager->getTasksByOwner($this->authentication->getCurrentUser(), $state);
            $caption = 'Мои задачи';
        }
        if ($this->router->getCurrentRoute() == 'tasks_for_me')
        {
            $taskList = $manager->getTasksByDestination($this->authentication->getCurrentUser(), $state);
            $caption = 'Задачи для меня';
        }

        return $this->acceptSolutionRenderView('task_tracker::index.html.twig',
            array(
                'tasks' => $taskList,
                'caption' => $caption,
                'states' => $dictionary->taskStates()->getList(),
                'filter' => $form
            )
        );
    }

    public function showAction($id)
    {
        $manager = new TaskManager($this->dataProvider->getEntityManager());
        $roles = array();
        $task = $manager->getTaskForShow($this->authentication->getCurrentUser(), $id, $roles);
        $dictionary = new Dictionary();

        if (!$manager->accessHasOpened())
        {
            return $this->acceptSolutionRedirectToRoute('403', array());
        }

        return $this->acceptSolutionRenderView('task_tracker::show.html.twig',
            array(
                'task' => $task,
                'roles' => $dictionary->taskRoles()->getList(),
                'states' => $dictionary->taskStates()->getList(),
                'hard' => $dictionary->taskLevels()->getList(),
                'speed' => $dictionary->taskSpeeds()->getList(),
                'currentRoles' => $roles,
                'buttons' => $this->buildControlsModel($roles, $dictionary, $task, $manager)
            )
        );


    }

    public function newReportAction($taskId)
    {
        $url = $this->router->getUrlByRoute('task_report_create', array('taskId' => $taskId), false);
        $form = (new FormProvider())->getTaskReportForm($url);

        return $this->acceptSolutionRenderView('task_tracker::formReport.html.twig',
            array(
                'form' => $form
            )
        );
    }

    public function createReportAction($taskId)
    {
        $url = $this->router->getUrlByRoute('task_report_create', array('taskId' => $taskId), false);
        $form = (new FormProvider())->getTaskReportForm($url);

        $form->writeFromRequest($this->request);

        $this->response->prepareForJson();
        $jsonAnswer = array(
            'status' => 'ok',
            'view' => ''
        );

        if ($form->isValid())
        {
            $report = new TaskReport();
            $form->writeToEntity($report);

            $manager = new TaskManager($this->dataProvider->getEntityManager());
            $manager->addReport($this->authentication->getCurrentUser(), $taskId, $report);

            if (!$manager->accessHasOpened())
            {
                $jsonAnswer['status'] = 'failed';
                $form->addError('Вы не можете комментировать эту задачу');
                $jsonAnswer['view'] = $this->viewer->render('task_tracker::formReport.html.twig', array('form' => $form));
            }

            if ($jsonAnswer['status'] == 'ok')
            {
                $manager->save();
                $jsonAnswer['view'] = $this->viewer->render('task_tracker::singleReport.html.twig', array('report' => $report));
            }
        } else
        {
            $jsonAnswer['view'] = $this->viewer->render('task_tracker::formReport.html.twig', array('form' => $form));
            $jsonAnswer['status'] = 'failed';
        }

        return $this->acceptSolutionFlushToResponse(json_encode($jsonAnswer));
    }

    public function newAction()
    {
        $url = $this->router->getUrlByRoute('task_create', array(), false);
        $form = (new FormProvider())->getTaskEditForm($url);


        $users = $this->prepareUserListByBoss();
        $usersJson = array();
        $usersJson['sourceList'] = $users;
        $usersJson['recipientList'] = array();

        $form->getField('users')->setValue(json_encode($usersJson));

        return $this->acceptSolutionRenderView('task_tracker::edit.html.twig',
            array(
                'form' => $form,
                'id' => 0
            )
        );
    }

    public function createAction()
    {
        $url = $this->router->getUrlByRoute('task_create', array(), false);
        $form = (new FormProvider())->getTaskEditForm($url);
        $form->writeFromRequest($this->request);

        if ($form->isValid())
        {
            $task = new Task();
            $form->writeToEntity($task);
            $users = json_decode($form->getField('users')->getValue());
            $task->setUsers($users->recipientList);

            $manager = new TaskManager($this->dataProvider->getEntityManager());
            $manager->createTask($this->authentication->getCurrentUser(), $task);

            if ($manager->accessHasOpened())
            {
                $manager->save();
                return $this->acceptSolutionRedirectToRoute('task_show',
                    array(
                        'id' => $task->getId()
                    )
                );
            }

            $form->addError('У Вас недостаточно прав для создания новой задачи');
        }

        return $this->acceptSolutionRenderView('task_tracker::edit.html.twig',
            array(
                'form' => $form,
                'id' => 0
            )
        );
    }

    public function editAction($id)
    {
        $manager = new TaskManager($this->dataProvider->getEntityManager());
        $task = $manager->getTaskForEdit($this->authentication->getCurrentUser(), $id);
        $dictionary = new Dictionary();

        if (!$manager->accessHasOpened())
        {
            return $this->acceptSolutionRedirectToRoute('403', array());
        }

        $url = $this->router->getUrlByRoute('task_update', array('id' => $id), false);
        $form = (new FormProvider())->getTaskEditForm($url);

        $form->writeFromEntity($task);

        $users = $this->prepareUserListByBoss();
        $usersInTask = $task->getUsers();

        $usersJson = array();
        $usersJson['sourceList'] = array();
        $usersJson['recipientList'] = array();

        foreach ($users as $user)
        {
            $isRecipient = false;
            foreach ($usersInTask as $userInTask)
            {
                if ($userInTask->getUserId() == $user['id'] && $userInTask->getRole() == $dictionary->taskRoles()->EXECUTOR[0])
                {
                    $usersJson['recipientList'][] = $user;
                    $isRecipient = true;
                    break;
                }
            }
            if (!$isRecipient)
            {
                $usersJson['sourceList'][] = $user;
            }
        }

        $form->getField('users')->setValue(json_encode($usersJson));

        return $this->acceptSolutionRenderView('task_tracker::edit.html.twig',
            array(
                'form' => $form,
                'id' => $id
            )
        );
    }

    public function updateAction($id)
    {
        $url = $this->router->getUrlByRoute('task_update', array('id' => $id), false);
        $form = (new FormProvider())->getTaskEditForm($url);
        $form->writeFromRequest($this->request);

        if ($form->isValid())
        {
            $manager = new TaskManager($this->dataProvider->getEntityManager());

            $task = $manager->getTaskForEdit($this->authentication->getCurrentUser(), $id);

            $form->writeToEntity($task);
            $users = json_decode($form->getField('users')->getValue());
            $task->setUsers($users->recipientList);

            $manager->editTask($this->authentication->getCurrentUser(), $task);

            if ($manager->accessHasOpened())
            {
                $manager->save();
                return $this->acceptSolutionRedirectToRoute('task_show',
                    array(
                        'id' => $id
                    )
                );
            }

            $form->addError('У Вас недостаточно прав для редактирования задачи (' . $id . ')');
        }

        return $this->acceptSolutionRenderView('task_tracker::edit.html.twig',
            array(
                'form' => $form,
                'id' => $id
            )
        );
    }

    public function changeStateAction($id, $state)
    {
        $manager = new TaskManager($this->dataProvider->getEntityManager());
        $manager->taskStateChange($this->authentication->getCurrentUser(), $id, $state);
        if ($manager->accessHasOpened())
        {
            $manager->save();
        }
        return $this->acceptSolutionRedirectToRoute('task_show',
            array(
                'id' => $id
            )
        );
    }

    private function buildControlsModel($roles, Dictionary $dictionary, Task $task, TaskManager $manager)
    {
        $buttons = array();
        if (in_array($dictionary->taskRoles()->CREATOR[0], $roles))
        {
            $url = $this->router->getUrlByRoute('task_edit', array('id' => $task->getId()), false);
            $buttons[] = array(
                'caption' => 'Править',
                'url' => $url
            );
        }

        $states = $dictionary->taskStates();
        $statesList = $states->getList();
        $statesList[$states->COMPLETED[0]] = 'Завершить задвчу';
        $statesList[$states->ON_REVIEW[0]] = 'Отправить на рассмотрение';
        $statesList[$states->CANCELED[0]] = 'Отменить';
        $statesList[$states->RETURNED[0]] = 'Вернуть на доработку';
        $statesList[$states->RUNNING[0]] = 'Начать выполнение';

        $model = $manager->buildModel();

        foreach ($roles as $role)
        {
            if (!array_key_exists($role, $model))
            {
                continue;
            }
            if (!array_key_exists($task->getState(), $model[$role]))
            {
                continue;
            }

            foreach ($model[$role][$task->getState()] as $rule)
            {
                $url = $this->router->getUrlByRoute('task_change_state', array('id' => $task->getId(), 'state' => $rule), false);
                $buttons[] = array(
                    'caption' => $statesList[$rule],
                    'url' => $url
                );

            }
        }

        return $buttons;
    }

    private function prepareUserListByBoss()
    {
        $manager = new Manager($this->dataProvider->getEntityManager(), $this->router);
        /**
         * @var $user User
         */
        $user = $this->authentication->getCurrentUser();
        $users = array();
        $links = $user->getLinks();
        if ($links->count() != 1)
        {
            return $users;
        }

        $tree = $manager->buildGroupTree($links[0]->getGroupId());
        $users = $tree->getUsersGlobal();
        $selectedFields = array(
            'id as id',
            'firstName as fName',
            'name as name',
            'secondName as sName',
            'workPosition as wPost'
        );

        $users = $this->dataProvider->getEntityManager()->getRepository('Lw\Model\Entity\User')->getAllUsers(false, true, $users, $selectedFields);

        return $users;
    }
} 