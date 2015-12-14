<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 02.04.15
 * Time: 12:10
 */

namespace LW\Model\UserManagement;

use Doctrine\ORM\EntityManager;
use LW\core\Router;
use LW\Model\Entity\Group;
use LW\Model\Repository\GroupRepository;
use LW\Model\Repository\LinkRepository;
use LW\Model\Entity\User;




class Manager
{

    /**
     * @var $groupRepository GroupRepository
     */
    private $groupRepository;
    /**
     * @var $userRepository UserRepository
     */
    private $userRepository;
    /**
     * @var $linkRepository LinkRepository
     */
    private $linkRepository;
    /**
     * @var $entityManager EntityManager
     */
    private $entityManager;

    /**
     * @var Router $router
     */
    private $router;


    public function __construct(EntityManager $em, Router $router)
    {
        $this->entityManager = $em;
        $this->router = $router;
        $this->groupRepository = $this->entityManager->getRepository('LW\Model\Entity\Group');
        $this->userRepository = $this->entityManager->getRepository('LW\Model\Entity\User');
        $this->linkRepository = $this->entityManager->getRepository('LW\Model\Entity\Link');
    }

    public function addUsersToGroup($groupId, $users)
    {
        $currentLinks = $this->linkRepository->getLinksByGrouop($groupId);
        $newLinks = array();
        $deletedLinks = array();

        foreach ($currentLinks as $currentLink)
        {
            $userKey = array_search($currentLink['userId'], $users);
            if ($userKey === false)
            {
                $deletedLinks[] = $currentLink['id'];
            }
            else
            {
                unset($users[$userKey]);
            }
        }

        foreach ($users as $user)
        {
            $newLink = array();
            $newLink['userId'] = $user;
            $newLink['groupId'] = $groupId;
            $newLinks[] = $newLink;
        }

        if (count($deletedLinks) > 0)
        {
            $this->linkRepository->deleteLinks($deletedLinks);
        }
        if (count($newLinks) > 0)
        {
            $this->linkRepository->insertLinks($newLinks);
        }
    }

    public function removeUsersFromGroup($userCollection, $groupId)
    {
    }

    public function removeGroup($groupId)
    {
        $removeGroups = $this->buildGroupTree($groupId)->prepareForSaveInStore($this->entityManager);
        foreach($removeGroups as $group)
        {
            $this->entityManager->remove($group);
        }

        $this->entityManager->flush($removeGroups);
    }

    public function removeAllUsersInGroup($groupId)
    {
    }

    public function removeAllGroupsInGroup($groupId, $removeUsers = false)
    {
    }

    public function buildGroupTree($owner)
    {
        $entities = $this->groupRepository->getDataForTree();
        $tree = new NodeTree();
        $tree->setId($owner);
        $tree->buildBranches($entities);
        return $tree;
    }

    public function setActiveForGroup($isActive, $setActiveForUssers = true)
    {

    }

    public function buildGroupTreeAaJson($owner)
    {
        $entities = $this->groupRepository->getDataForTree();
        $tree = new NodeTree();
        $tree->setId($owner);
        $tree->buildBranches($entities);
        $returnedArray = $this->prepareGroupTreeForJson($tree);

        return (json_encode($returnedArray));
    }

    public function prepareGroupTreeForJson(NodeTree $tree)
    {
        $returnedArray = $tree->getDataAsArray($this->router);
        if ($tree->getId() == 0)
        {
            $returnedArray = $returnedArray['children'];
        } else
        {
            $returnedArray = array($returnedArray);
        }
        return $returnedArray;
    }

    public function getGroup($id)
    {
        $entity = $this->groupRepository->find($id);
        return $entity;
    }

    public function getGroupAsJson($entity)
    {
        return json_encode(array(
            'label' => $entity->getName(),
            'id' => $entity->getId(),
            'type_' => 'group',
            'users' => $entity->getLinks()->count(),
            'active' => $entity->getIsActive(),
            'movedUrl' => $this->router->getUrlByRoute('admin_group_move', array(), false),
            'editUrl' => $this->router->getUrlByRoute('admin_group_edit', array('id' => $entity->getId(), false)),
            'usersUrl' => $this->router->getUrlByRoute('admin_group_edit_users_list', array('id' => $entity->getId(), false)),
            'userCreateUrl' => $this->router->getUrlByRoute('admin_user_new_in_group', array('groupId' => $entity->getId(), false)),
            'groupCreateUrl' => $this->router->getUrlByRoute('admin_group_new', array('ownerId' => $entity->getId(), false)),
            'removeUrl' => $this->router->getUrlByRoute('admin_group_delete_request', array('id' => $entity->getId(), false)),
            'children' => array()
        ));
    }

    public function moveNode($nodeId, $ownerId, $position)
    {
        /**
         * @var $entity Group
         * @var $owner Group
         */
        $entity = $this->groupRepository->find($nodeId);
        if ($ownerId > 0)
        {
            $isActive = $this->groupRepository->find($ownerId)->getIsActive();
        } else
        {
            $isActive = $entity->getIsActive();
        }
        $entity->setIsActive($isActive);

        $this->groupRepository->reorderInOwners($entity, $position, $entity->getOwner(), $ownerId);

        $branch = $this->buildGroupTree($nodeId);
        $branch->setIsActive($entity->getIsActive());
        $this->entityManager->flush($branch->prepareForSaveInStore($this->entityManager));
    }

    public function getUserListsForGroup($groupId)
    {
        $users = $this->userRepository->getAllUsers();
        $usersInGroups = array();
        $freeUsers = array();
        foreach ($users as $user)
        {
            $user['editUrl'] = $this->router->getUrlByRoute('admin_user_edit', array('id' => $user['id']));
            if (in_array($groupId, $user['groups']))
            {
                $usersInGroups[] = $user;
            } else
            {
                $freeUsers[] = $user;
            }
        }
        return array('included' => $usersInGroups, 'free' => $freeUsers);
    }

    public function generateCode($length)
    {
        $chars = 'abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ1234567890_';
        if($length < 0)
        {
            $length = 1;
        }
        $numChars = strlen($chars);
        $str = '';
        for ($i = 0; $i < $length; $i++)
        {
            $str .= substr($chars, rand(1, $numChars) - 1, 1);
        }

        $array_mix = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
        srand((float)microtime() * 1000000);
        shuffle($array_mix);

        return implode("", $array_mix);
    }

    public  function  setEncodePassword(User &$user)
    {
        $factory = $this->container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($user->getPasswordOpen(), $user->getSalt());
        $user->setPassword($encodedPassword);
    }

} 