<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 25.08.15
 * Time: 13:07
 */

namespace LW\Controller\UserManager;


use Doctrine\DBAL\LockMode;
use LW\Core\ControllerBase;
use LW\Model\Entity\User;
use LW\Model\FormProvider;
use LW\Model\UserManagement\Manager;

class UserController extends ControllerBase
{
    public function indexAction()
    {
        $em = $this->dataProvider->getEntityManager();

        $entities = $em->getRepository('LW\Model\Entity\User')->findAll();

        return $this->acceptSolutionRenderView('user_manager::user::index.html.twig',
            array(
                'entities' => $entities
            )
        );
    }

    public function newAction($groupId = 0)
    {
        $entity = new User();
        $formProvider = new FormProvider();
        $em = $this->dataProvider->getEntityManager();

        $route = $this->router->getCurrentRoute();
        if ($route == 'admin_user_new')
        {
            $url = $this->router->getUrlByRoute('admin_user_create', array(), false);
        }
        if ($route == 'admin_user_new_in_group')
        {
            $url = $this->router->getUrlByRoute('admin_user_create_in_group', array('groupId' => $groupId), false);
        }
        $form = $formProvider->getUserEditForm($url, $em->getRepository(get_class($entity)));

        return $this->acceptSolutionRenderView('user_manager::user::edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $form,
            )
        );
    }

    public function createAction($groupId = 0)
    {
        $entity = new User();
        $route = $this->router->getCurrentRoute();
        $formProvider = new FormProvider();
        $this->response->prepareForJson();
        $em = $this->dataProvider->getEntityManager();

        if ($route == 'admin_user_create')
        {
            $url = $this->router->getUrlByRoute('admin_user_create', array(), false);
            $returnedValue = $this->createReturnedArray('create');
        }
        if ($route == 'admin_user_create_in_group')
        {
            $url = $this->router->getUrlByRoute('admin_user_create_in_group', array('groupId' => $groupId), false);
            $returnedValue = $this->createReturnedArray('create_in_group');
        }

        $form = $formProvider->getUserEditForm($url, $em->getRepository(get_class($entity)));
        $form->writeFromRequest($this->request);

        if ($form->isValid())
        {
            $form->writeToEntity($entity);
            $entity->setWasChecked(true);

            $em->persist($entity);
            $em->flush();
            if ($route == 'admin_user_create_in_group')
            {
                $manager = new Manager($em, $this->router);
                $branch = $manager->buildGroupTree($groupId);
                $users = $branch->getUsers();
                $users->add($entity->getId());

                $manager->addUsersToGroup($groupId, $users->toArray());
                $returnedValue['view'] = json_encode($manager->prepareGroupTreeForJson($branch)[0]);
            } else
            {
                $returnedValue['view'] = $this->viewer->render('user_manager::user::newRow.html.twig', array('entity' => $entity));
            }
        } else
        {
            $returnedValue['status'] = 'failed';
            $returnedValue['view'] = $this->viewer->render('user_manager::user::edit.html.twig', array(
                'entity' => $entity,
                'edit_form' => $form
            ));
        }

        return $this->acceptSolutionFlushToResponse(json_encode($returnedValue));
    }

    public function editAction($id)
    {
        $em = $this->dataProvider->getEntityManager();
        $formProvider = new FormProvider();

        $repository = $em->getRepository('LW\Model\Entity\User');
        $entity = $repository->find($id);

        if (!$entity)
        {
            throw new \Exception('Unable to find User entity.');
        }

        $url = $this->router->getUrlByRoute('admin_user_update', array('id' => $id), false);
        $editForm = $formProvider->getUserEditForm($url, $repository);
        $editForm->writeFromEntity($entity);

        //$deleteForm = $this->createDeleteForm($id);

        return $this->acceptSolutionRenderView('user_manager::user::edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm,
            )
        );
    }

    public function updateAction($id)
    {
        $em = $this->dataProvider->getEntityManager();
        $formProvider = new FormProvider();

        $repository = $em->getRepository('LW\Model\Entity\User');
        $entity = $repository->find($id);

        if (!$entity)
        {
            throw new \Exception('Unable to find User entity.');
        }

        $url = $this->router->getUrlByRoute('admin_user_update', array('id' => $id), false);
        $editForm = $formProvider->getUserEditForm($url, $repository, $entity->getId());
        $editForm->writeFromRequest($this->request);

        $this->response->prepareForJson();
        $returnedValue = $this->createReturnedArray('update');

        if ($editForm->isValid())
        {
            $editForm->writeToEntity($entity);
            $em->flush();
            $returnedValue['view'] = $this->viewer->render('user_manager::user::newRow.html.twig', array('entity' => $entity));
        } else
        {
            $returnedValue['status'] = 'failed';
            $returnedValue['view'] = $this->viewer->render('user_manager::user::edit.html.twig', array(
                'entity' => $entity,
                'edit_form' => $editForm,
            ));
        }

        return $this->acceptSolutionFlushToResponse(json_encode($returnedValue));
    }

    public function deleteRequestAction($id)
    {
        $em = $this->dataProvider->getEntityManager();

        $formProvider = new FormProvider();
        $url = $this->router->getUrlByRoute('admin_user_delete', array('id' => $id), false);
        $form = $formProvider->getUserDeleteForm($url);
        $entity = $em->getRepository('LW\Model\Entity\User')->find($id);

        if (!$entity)
        {
            throw new \Exception('Unable to find User entity.');
        }

        return $this->acceptSolutionRenderView('user_manager::user::deleteRequest.html.twig', array(
                'entity' => $entity,
                'delete_form' => $form,
            )
        );
    }

    public function deleteAction($id)
    {
        $formProvider = new FormProvider();
        $url = $this->router->getUrlByRoute('admin_user_delete', array('id' => $id), false);
        $form = $formProvider->getUserDeleteForm($url);
        $form->writeFromRequest($this->request);
        $returnedValue = $this->createReturnedArray('delete');

        if ($form->isValid())
        {
            $em = $this->dataProvider->getEntityManager();
            $entity = $em->getRepository('LW\Model\Entity\User')->find($id);

            if (!$entity)
            {
                throw new \Exception('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        } else
        {
            $returnedValue['status'] = 'failed';
        }

        $this->response->prepareForJson();
        return $this->acceptSolutionFlushToResponse(json_encode($returnedValue));
    }

    private function createReturnedArray($action)
    {
        return array(
            'status' => 'ok',
            'entity' => 'user',
            'action' => $action,
            'view' => ''
        );
    }
} 