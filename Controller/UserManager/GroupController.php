<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 25.08.15
 * Time: 13:07
 */

namespace LW\Controller\UserManager;


use LW\Core\ControllerBase;
use LW\Model\Entity\Group;
use LW\Model\FormProvider;
use LW\Model\UserManagement\Manager;

class GroupController extends ControllerBase
{
    public function indexAction()
    {
        $em = $this->dataProvider->getEntityManager();
        $formProvider = new FormProvider();
        $entities = $em->getRepository('LW\Model\Entity\Group')->findAll();
        $manager = new Manager($em, $this->router);
        $url = $this->router->getUrlByRoute('admin_group_move', array(), false);
        $moveForm = $formProvider->getGroupMoveForm($url);

        return $this->acceptSolutionRenderView('user_manager::group::index.html.twig',
            array(
                'entities' => $entities,
                'tree_data' => $manager->buildGroupTreeAaJson(0),
                'move_form' => $moveForm
            )
        );
    }

    public function newAction($ownerId)
    {
        $entity = new Group();
        $entity->setOwner($ownerId);
        $formProvider = new FormProvider();
        $form = $formProvider->getGroupEditForm();

        return $this->acceptSolutionRenderView('user_manager::group::edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $form,
            )
        );
    }

    public function createAction($ownerId)
    {
        $entity = new Group();
        $entity->setOwner($ownerId);
        $formProvider = new FormProvider();
        $form = $formProvider->getGroupEditForm();
        $form->writeFromRequest($this->request);
        $this->response->prepareForJson();

        $returnedValue = $this->createReturnedArray('create');
        if ($ownerId != 0)
        {
            $returnedValue['action'] = 'create_in';
        }

        if ($form->isValid())
        {
            $em = $this->dataProvider->getEntityManager();
            $entity->setInnerOrder($em->getRepository(get_class($entity))->getNextNumberByOwner($ownerId));
            $form->writeToEntity($entity);
            $em->persist($entity);
            $em->flush();
            $returnedValue['view'] = (new Manager($em, $this->router))->getGroupAsJson($entity);
        } else
        {
            $returnedValue['status'] = 'failed';
            $returnedValue['view'] = $this->viewer->render('user_manager::group::edit.html.twig', array(
                'entity' => $entity,
                'edit_form' => $form,
            ));
        }

        return $this->acceptSolutionFlushToResponse(json_encode($returnedValue));
    }

    public function editAction($id)
    {
        $em = $this->dataProvider->getEntityManager();

        $entity = $em->getRepository('LW\Model\Entity\Group')->find($id);

        if (!$entity)
        {
            throw new \Exception('Unable to find Group entity.');
        }
        $formProvider = new FormProvider();
        $editForm = $formProvider->getGroupEditForm();
        $editForm->writeFromEntity($entity);

        return $this->acceptSolutionRenderView('user_manager::group::edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm,
            )
        );
    }

    public function updateAction($id)
    {
        $em = $this->dataProvider->getEntityManager();

        $entity = $em->getRepository('LW\Model\Entity\Group')->find($id);

        if (!$entity)
        {
            throw new \Exception('Unable to find Group entity.');
        }


        $formProvider = new FormProvider();
        $editForm = $formProvider->getGroupEditForm();
        $editForm->writeFromRequest($this->request);
        $editForm->writeToEntity($entity);
        $this->response->prepareForJson();
        $returnedValue = $this->createReturnedArray('update');
        if ($editForm->isValid())
        {
            $manager = new Manager($em, $this->router);
            $branch = $manager->buildGroupTree($id);
            $branch->setIsActive($entity->getIsActive());
            $branch->setName($entity->getName());
            $em->flush($branch->prepareForSaveInStore($em));
            $returnedValue['view'] = json_encode($manager->prepareGroupTreeForJson($branch)[0]);
        } else
        {
            $returnedValue['status'] = 'failed';
            $returnedValue['view'] = $this->viewer->render('user_manager::group::edit.html.twig', array(
                'entity' => $entity,
                'edit_form' => $editForm,
            ));
        }

        return $this->acceptSolutionFlushToResponse(json_encode($returnedValue));
    }

    public function deleteRequestAction($id)
    {
        $em = $this->dataProvider->getEntityManager();
        $entity = $em->getRepository('LW\Model\Entity\Group')->find($id);

        if (!$entity)
        {
            throw new \Exception('Unable to find Group entity.');
        }

        $url = $this->router->getUrlByRoute('admin_group_delete', array('id' => $id), false);
        $formProvider = new FormProvider();
        $form = $formProvider->getGroupDeleteForm($url);
        return $this->acceptSolutionRenderView('user_manager::group::delete.html.twig',
            array(
                'tree' => (new Manager($em, $this->router))->buildGroupTree($id),
                'form' => $form
            )
        );
    }

    public function deleteAction($id)
    {
        $em = $this->dataProvider->getEntityManager();
        $url = $this->router->getUrlByRoute('admin_group_delete', array('id' => $id), false);
        $formProvider = new FormProvider();
        $form = $formProvider->getGroupDeleteForm($url);
        $form->writeFromRequest($this->request);
        $this->response->prepareForJson();
        $returnedValue = $this->createReturnedArray('delete');

        if ($form->isValid())
        {
            $manager = new Manager($em, $this->router);
            $manager->removeGroup($id);
        } else
        {
            $returnedValue['status'] = 'failed';
        }

        return $this->acceptSolutionFlushToResponse(json_encode($returnedValue));
    }

    public function moveAction()
    {
        $formProvider = new FormProvider();
        $url = $this->router->getUrlByRoute('admin_group_move', array(), false);
        $form = $formProvider->getGroupMoveForm($url);
        $form->writeFromRequest($this->request);
        $this->response->prepareForJson();
        $returnedValue = $this->createReturnedArray('move');
        $manager = new Manager($this->dataProvider->getEntityManager(), $this->router);

        if ($form->isValid())
        {
            $moveNodes = json_decode($form->getField('moves_data')->getValue());
            foreach ($moveNodes as $moveNode)
            {
                $manager->moveNode($moveNode->node, $moveNode->owner, $moveNode->position);
            }
        } else
        {
            $entityMoved = $manager->getGroup($form->get('movedId')->getData());
            $returnedValue['status'] = 'failed';
            $returnedValue['view'] = sprintf('Перемещение группы <b>%s</b> не было сохранено', $entityMoved->getName());
        }

        return $this->acceptSolutionFlushToResponse(json_encode($returnedValue));
    }

    public function editUsersListAction($id)
    {
        $em = $this->dataProvider->getEntityManager();

        $entity = $em->getRepository('LW\Model\Entity\Group')->find($id);

        if (!$entity)
        {
            throw new \Exception('Unable to find Group entity.');
        }

        $manager = new Manager($em, $this->router);
        $users = $manager->getUserListsForGroup($id);

        $formProvider = new FormProvider();
        $url = $this->router->getUrlByRoute('admin_group_update_users_list', array('id' => $id), false);
        $submitForm = $formProvider->getGroupUsersListForm($url);

        $usersJson = array();
        $usersJson['sourceList'] = $users['free'];
        $usersJson['recipientList'] = $users['included'];

        return $this->acceptSolutionRenderView('user_manager::group::usersList.html.twig',
            array(
                'users_data' => json_encode($usersJson),
                'form' => $submitForm,
            )
        );
    }

    public function updateUsersListAction($id)
    {
        $em = $this->dataProvider->getEntityManager();
        $entity = $em->getRepository('LW\Model\Entity\Group')->find($id);

        if (!$entity)
        {
            throw new \Exception('Unable to find Group entity.');
        }

        $formProvider = new FormProvider();
        $url = $this->router->getUrlByRoute('admin_group_update_users_list', array('id' => $id), false);
        $form = $formProvider->getGroupUsersListForm($url);
        $form->writeFromRequest($this->request);

        $this->response->prepareForJson();

        $returnedValue = $this->createReturnedArray('update_users_list');
        $manager = new Manager($em, $this->router);

        if ($form->isValid())
        {
            $users = json_decode($form->getField('users')->getValue());
            $manager->addUsersToGroup($id, $users);
            $branch = $manager->buildGroupTree($id);
            $returnedValue['view'] = json_encode($manager->prepareGroupTreeForJson($branch)[0]);
        } else
        {
            $entityMoved = $manager->getGroup($form->get('movedId')->getData());
            $returnedValue['status'] = 'failed';
            $returnedValue['view'] = sprintf('Редактирование списка подльзователей для группы <b>%s</b> не было сохранено', $entityMoved->getName());
        }

        return $this->acceptSolutionFlushToResponse(json_encode($returnedValue));
    }

    private function createReturnedArray($action)
    {
        return array(
            'status' => 'ok',
            'entity' => 'group',
            'action' => $action,
            'view' => ''
        );
    }
} 