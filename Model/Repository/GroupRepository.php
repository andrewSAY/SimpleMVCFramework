<?php

namespace LW\Model\Repository;

use Doctrine\ORM\EntityRepository;
use LW\Model\Entity\Group;

/**
 * GroupRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GroupRepository extends EntityRepository
{
    public function getNextNumberByOwner($ownerId)
    {
        $countsChildren = $this->createQueryBuilder('g')
            ->select('count(g.id) as count_')
            ->where('g.owner=?1')->setParameter(1, $ownerId)->getQuery()->execute();
        return $countsChildren[0]['count_'] + 1;
    }

    public function existOwner($ownerId)
    {
        if ($ownerId == 0)
        {
            return true;
        }
        $owner = $this->find($ownerId);
        if (!$owner)
        {
            return false;
        }

        return true;
    }

    public function getDataForTree()
    {
        $groupsSelected = $this->getEntityManager()->createQueryBuilder()
            ->select('g.id as id, g.name as name, g.owner as owner, g.innerOrder as innerOrder, g.isActive as isActive ')
            ->from('LW\Model\Entity\Group ', 'g')
            ->addOrderBy('g.owner', 'ASC')
            ->addOrderBy('g.innerOrder', 'ASC')
            ->getQuery()->execute();

        $links = $this->getEntityManager()->createQueryBuilder()
            ->select('l.groupId as gId, l.userId as uId')
            ->from('LW\Model\Entity\Link ', 'l')
            ->addOrderBy('gId', 'ASC')
            ->getQuery()->execute();

        $groups = array();
        foreach ($groupsSelected as $groupSelected)
        {
            $currentId = $groupSelected['id'];
            $groups[$currentId] = $groupSelected;
            $groups[$currentId]['users'] = array();
        }

        foreach ($links as $link)
        {
            if ($link['gId'] == $groups[$link['gId']]['id'])
            {
                $groups[$link['gId']]['users'][] = $link['uId'];
            }

        }

        return $groups;
    }

    public function reorderInOwners(Group $entity, $position, $oldOwnerId, $newOwnerId)
    {
        /**
         * @var $item Group
         */
        $collection = $this->findBy(array(
            'owner' => $newOwnerId,
        ), array(
            'innerOrder' => 'ASC'
        ));

        $collectionOld = array();

        if ($oldOwnerId == $newOwnerId)
        {
            if ($entity->getInnerOrder() < $position)
            {
                $beginning = $entity->getInnerOrder() + 1;
                $ending = $position;
                $increment = -1;
            }

            if ($entity->getInnerOrder() > $position)
            {
                $beginning = $position;
                $ending = $entity->getInnerOrder();
                $increment = 1;
            }
        }

        if ($oldOwnerId != $newOwnerId)
        {
            $collectionOld = $this->findBy(array(
                'owner' => $oldOwnerId
            ), array(
                'innerOrder' => 'ASC'
            ));


            unset($collectionOld[$entity->getInnerOrder() - 1]);

            $beginning = $position;
            $ending = $position;
            $increment = 0;
            $countInCollection = count($collection);
            if($countInCollection > 0)
            {
                $ending = $collection[$countInCollection - 1]->getInnerOrder();
                $increment = 1;
            }


        }

        foreach ($collection as $item)
        {
            if ($item->getInnerOrder() >= $beginning && $item->getInnerOrder() <= $ending)
            {
                $item->setInnerOrder($item->getInnerOrder() + $increment);
            }
        }

        $i = 1;
        foreach($collectionOld as  $item)
        {
            $item->setInnerOrder($i);
            array_unshift($collection, $item);
            $i++;
        }
        $entity->setInnerOrder($position);
        $entity->setOwner($newOwnerId);
        array_unshift($collection, $entity);

        $this->getEntityManager()->flush($collection);

    }

    public function getGroupByName($name)
    {
        return $this->findBy(array('name'=>$name));
    }
}
