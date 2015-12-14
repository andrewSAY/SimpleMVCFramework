<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 10.09.15
 * Time: 10:08
 */

namespace LW\ConsoleCommands;

use LW\Core\OrmProvider;
use LW\Core\Request;
use LW\core\Router;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use LW\Model\Dictionary\Dictionary;
use LW\Model\Entity\Group;
use LW\Model\Entity\User;
use LW\Model\Repository\GroupRepository;
use LW\Model\UserManagement\Manager;
use Symfony\Component\Console\Command\Command;

class InsertPrimaryDataCommand extends Command
{
    protected function configure()
    {
        $this->setName('lw:insert_primary_data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var EntityManager $em
         */
        $em = (new OrmProvider())->getEntityManager();
        /**
         * @var GroupRepository $groupRepository
         */
        $groupRepository = $em->getRepository('LW\Model\Entity\Group');
        $dict = new Dictionary();

        $users['director'] = $this
            ->generateUser('ivanov_vu', 'ivanov_vu@test', 'ivanov_vu', $dict->userRoles()->ROLE_BOSS[0], 'Иванов', 'Василий', 'Юрьевич', 'Директор');
        $users['department_chief'] = $this
            ->generateUser('vasiliev_va', 'vasiliev_va@test', 'vasiliev_va', $dict->userRoles()->ROLE_BOSS[0], 'Васильев', 'Владимир', 'Алексеевич', 'Начальник отдела рекламы');
        $users['designer'] = $this
            ->generateUser('rasstrelnikov_sv', 'rasstrelnikov_sv@test', 'rasstrelnikov_sv', $dict->userRoles()->ROLE_WORKER[0], 'Расстрельников', 'Сергей', 'Викторович', 'Дизайнер');
        $users['copywriter'] = $this
            ->generateUser('tverskaya_aa', 'tverskaya_aa@test', 'tverskaya_aa', $dict->userRoles()->ROLE_WORKER[0], 'Тверская', 'Алена', 'Александровна', 'Копирайтер');
        $users['system_administrator'] = $this
            ->generateUser('admin', 'kolelov_in@test', '_admin_', $dict->userRoles()->ROLE_WORKER[0], 'Колелов', 'Иван', 'Николаевич', 'Системный администратор');

        $groups['organisation'] = $this->generateGroup('ООО Наша фирма');
        $groups['chief'] = $this->generateGroup('Директор');
        $groups['department_advertising'] = $this->generateGroup('Отдел рекламы');
        $groups['chief_department'] = $this->generateGroup('Начальник отдела');
        $groups['worker_department'] = $this->generateGroup('Работники');

        foreach($users as $user)
        {
            $em->persist($user);
        }
        foreach($groups as $group)
        {
            $group->setInnerOrder($groupRepository->getNextNumberByOwner(0));
            $em->persist($group);
        }

        $em->flush();

        $request = new Request();
        $router = new Router($request);
        $manager = new Manager($em, $router);

        $manager->addUsersToGroup($groups['chief']->getId(), array(
            $users['director']->getId()
        ));
        $manager->addUsersToGroup($groups['chief_department']->getId(), array(
            $users['department_chief']->getId()
        ));
        $manager->addUsersToGroup($groups['worker_department']->getId(), array(
            $users['copywriter']->getId(),
            $users['designer']->getId()
        ));

        $manager
            ->moveNode($groups['chief']->getId(), $groups['organisation']->getId(), 1);
        $manager
            ->moveNode($groups['department_advertising']->getId(), $groups['chief']->getId(), 1);
        $manager
            ->moveNode($groups['chief_department']->getId(), $groups['department_advertising']->getId(), 1);
        $manager
            ->moveNode($groups['worker_department']->getId(), $groups['chief_department']->getId(), 1);

        $output->writeln('Successful!');
    }

    private function generateUser($username, $email, $pass, $role, $fName, $name, $sName, $wPosition)
    {
        $user = new User();

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($pass);
        $user->setRole($role);
        $user->setFirstName($fName);
        $user->setName($name);
        $user->setSecondName($sName);
        $user->setWorkPosition($wPosition);
        $user->setPhone(800000000000);
        $user->setWasChecked(true);
        $user->setIsActive(true);

        return $user;
    }

    private function generateGroup($name)
    {
        $group = new Group();

        $group->setName($name);
        $group->setOwner(0);
        $group->setIsActive(true);

        return $group;
    }
} 