<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 06.04.15
 * Time: 12:05
 */

namespace LW\Model\UserManagement;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use LW\core\Router;

class NodeTree
{
    private $id;
    private $name;
    private $nodes;
    private $nodesId;
    private $users;
    private $parent;
    private $isActive;

    public function __construct(NodeTree $parent = null)
    {
        $this->parent = $parent;
        $this->nodes = new ArrayCollection();
        $this->nodesId = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getNodes()
    {
        return $this->nodes;
    }

    public function  getNodesId()
    {
        return $this->nodesId;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function getUsersGlobal()
    {
        $users = array();
        $users = $this->_integrateUsers($this, $users);
        return $users;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setParent(NodeTree $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        foreach ($this->nodes as $node)
        {
            $node->setIsActive($this->getIsActive());
        }
        return $this;
    }

    public function addNode(NodeTree $nodeTree)
    {
        $this->nodes->add($nodeTree);
        $this->nodesId->add($nodeTree->getId());
        return $this;
    }

    public function removeNode(NodeTree $nodeTree)
    {
        $this->nodes->removeElement($nodeTree);
        $this->nodesId->removeElement($nodeTree->getId());
        return $this;
    }

    public function getBranchByOwner($ownerId)
    {

    }

    public function getBranchById($id)
    {

    }

    public function getBranchByTarget($targetId, $startNodeId = 0, $endNodeId = null)
    {

    }

    public function nodeInBranch($nodeId)
    {

    }

    public function buildBranches($collection)
    {
        if ($this->id == 0)
        {
            $this->setName('root');
            $this->setIsActive(true);
        } else
        {
            foreach ($collection as $key => $item)
            {
                if ($this->id == $item['id'])
                {
                    $this->_setNodes($item);
                    unset($collection[$key]);
                    break;
                }
            }
        }

        $nodeList = $this->_searchInCollection($collection, 'owner', $this->id);

        foreach ($nodeList as $nodeItem)
        {
            $node = new NodeTree($this);
            $node->setId($nodeItem['id']);
            $node->buildBranches($collection, $this->id);
            $this->addNode($node);
        }
    }

    public function getDataAsArray(Router $router)
    {
        $node = array(
            'label' => $this->name,
            'id' => $this->id,
            'type_' => 'group',
            'users' => $this->users->count(),
            'active' => $this->isActive,
            'children' => array(),
            'movedUrl' => $router->getUrlByRoute('admin_group_move', array(), false),
            'editUrl' => $router->getUrlByRoute('admin_group_edit', array('id' => $this->id), false),
            'usersUrl' => $router->getUrlByRoute('admin_group_edit_users_list', array('id' => $this->id), false),
            'userCreateUrl' => $router->getUrlByRoute('admin_user_new_in_group', array('groupId' => $this->id), false),
            'groupCreateUrl' => $router->getUrlByRoute('admin_group_new', array('ownerId' => $this->id), false),
            'removeUrl' => $router->getUrlByRoute('admin_group_delete_request', array('id' => $this->id), false),
        );

        foreach ($this->nodes as $nodeChild)
        {
            $node['children'][] = $nodeChild->getDataAsArray($router);
        }

        return $node;
    }

    public function prepareForSaveInStore(EntityManager $em)
    {

        $returnedCollection = array();
        if ($this->id > 0)
        {
            $entity = $em->find('LW\Model\Entity\Group', $this->id);
            $entity->setIsActive($this->isActive);
            $returnedCollection[] = $entity;
        }
        $childrenCollection = array();
        foreach ($this->nodes as $node)
        {
            $childrenCollection[] = $node->prepareForSaveInStore($em);

        }
        foreach($childrenCollection as $child)
        {
            if(is_array($child))
            {
                foreach($child as $item)
                {
                    array_push($returnedCollection, $item);
                }
            }
            if(is_object($child))
            {
                array_push($returnedCollection, $child);
            }

        }

        return $returnedCollection;
    }

    public function getCountAllChildren()
    {
        $count = $this->nodes->count();
        foreach($this->nodes as $node)
        {
            $count = $count + $node->getCountAllChildren();
        }
        return $count;
    }

    private function addSelfChildren($parentCount)
    {
        $parentCount = $parentCount + $this->nodes->count();
        return $parentCount;
    }

    private function _setNodes($item)
    {
        $this->name = $item['name'];
        if ($item['isActive'] == '1')
        {
            $this->isActive = true;
        } else
        {
            $this->isActive = false;
        }
        $this->users = new ArrayCollection($item['users']);
    }

    private function _searchInCollection($objects, $keyName, $targetValue)
    {
        $returnedCollection = array();
        foreach ($objects as $object)
        {
            if (is_object($object))
            {
                if ($object->$keyName() == $targetValue)
                {
                    $returnedCollection[] = $object;
                }
            }
            if (is_array($object))
            {
                if ($object[$keyName] == $targetValue)
                {
                    $returnedCollection[] = $object;
                }
            }
        }

        return $returnedCollection;
    }

    private function _integrateUsers(NodeTree $tree, $users)
    {
        $users = array_merge($users, $tree->getUsers()->toArray());
        foreach($tree->getNodes() as $node)
        {
            $users = $this->_integrateUsers($node, $users);
        }

        return $users;
    }

}