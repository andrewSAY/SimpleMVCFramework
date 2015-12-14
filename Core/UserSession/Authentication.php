<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 27.08.15
 * Time: 9:40
 */

namespace LW\Core\UserSession;


use LW\Core\OrmProvider;

class Authentication
{
    private $session;
    private $dataProvider;
    /**
     * @var UserInterface $currentUser
     */
    private $currentUser;

    function __construct(Session $session, OrmProvider $dataProvider)
    {
        $this->session = $session;
        $this->dataProvider = $dataProvider;
    }

    public function getCurrentUser()
    {
        if(!is_null($this->currentUser))
        {
            return $this->currentUser;
        }

        /**
         * @var UserInterface $user
         */
        global $CONFIG;
        $className = $CONFIG['USER_CLASS'];
        $id = $this->session->get('user_id');
        if ($id == null)
        {
            $user = null;
        }
        else
        {
            $user = $this->dataProvider->getEntityManager()->find($className, $id);
        }
        if (is_null($user))
        {
            $user = new $className();
            $user->setUsername('guest');
        }
        $this->currentUser = $user;

        return $user;
    }

    public function login($username, $password)
    {
        global $CONFIG;
        $className = $CONFIG['USER_CLASS'];
        $qBuilder = $this->dataProvider->getEntityManager()->createQueryBuilder();

        $users = $qBuilder
            ->select('u.id, u.username, u.password')
            ->from($className, 'u')
            ->where('u.isActive = 1 and u.username = ?1')
            ->setParameter(1,$username)
            ->getQuery()
            ->execute();
        if (count($users) == 0)
        {
            return false;
        }

        if ($users[0]['password'] != $password)
        {
            return false;
        }

        $this->session->remember('user_id', $users[0]['id']);

        return true;
    }

    public function logout()
    {
        $this->session->forger('user_id');
        $this->session->close();
    }
} 