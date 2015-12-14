<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 02.09.15
 * Time: 15:49
 */

namespace LW\Model\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\AST\Join;
use Doctrine\ORM\Query;
use LW\Model\Entity\TaskTracker\Task;

class TaskRepository extends EntityRepository
{
    public function findUser($id)
    {
        return $this->_em->find('LW\Model\Entity\User', $id);
    }

    public function persist($value)
    {
        $this->_em->persist($value);
    }

    public function remove($value)
    {
        $this->_em->remove($value);
    }

    public function save()
    {
        $this->_em->beginTransaction();
        $this->_em->flush();
        $this->_em->commit();
    }

    public function getRolesInTask($taskId, $userId)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('tu.role as role')
            ->from('LW\Model\Entity\TaskTracker\Task', 't')
            ->leftJoin('LW\Model\Entity\TaskTracker\TaskUser', 'tu', \Doctrine\ORM\Query\Expr\Join::WITH, 't.id = tu.taskId')
            ->where('t.id = ?0 and tu.userId = ?1');

        $queryBuilder->setParameters(array($taskId, $userId));

        $roles = $queryBuilder->getQuery()->execute();

        return $roles;
    }

    public function getTasks($fields, $order = array() ,$where = array(), $comparison = 'and')
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $fieldsString = implode(',', $fields);

        $queryBuilder->select($fieldsString)
            ->from('LW\Model\Entity\TaskTracker\Task', 't')
            ->leftJoin('LW\Model\Entity\TaskTracker\TaskUser', 'tu', \Doctrine\ORM\Query\Expr\Join::WITH, 't.id = tu.taskId');
        if (count($where) > 0)
        {
            $whereString = '';
            $params = array();

            $i = 0;
            $length = count($where);

            foreach ($where as $key => $value)
            {
                $glue = '=?' . $i;
                if ($i < $length - 1)
                {
                    $glue = $glue . ' ' . $comparison . ' ';
                }
                $whereString = $whereString . $key . $glue;
                $params[$i] = $value;
                $i++;
            }
            $queryBuilder->where($whereString)
                ->setParameters($params);
        }

        if(count($order) > 0)
        {

            foreach ($order as $key => $value)
            {
                $queryBuilder->orderBy($key, $value);
            }

        }

        return $queryBuilder->getQuery()->getArrayResult();
    }

} 