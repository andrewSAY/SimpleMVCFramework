<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 25.08.15
 * Time: 10:20
 */

namespace LW\ConsoleCommands;

use LW\Model\Entity\User;
use LW\Model\Repository\UserRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


use LW\Core\OrmProvider;
use Symfony\Component\Console\Command\Command;

class AddUserCommand extends Command
{
    protected function configure()
    {
        $this->setName('lw:add_user')
            ->setDescription('Add  users')
            ->addArgument('username', InputArgument::REQUIRED, 'The username')
            ->addArgument('password', InputArgument::REQUIRED, 'The password')
            ->addArgument('email', InputArgument::REQUIRED, 'The e-mail')
            ->addArgument('role', InputArgument::OPTIONAL, 'The roles', '');;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var UserRepository $repository
         */

        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $email = $input->getArgument('email');
        $role = $input->getArgument('role');


        $em = (new OrmProvider())->getEntityManager();

        $user = new User();

        $repository = $em->getRepository(get_class($user));

        if (!$repository->isUniqueUser('username', 0,$username))
        {
            throw new \Exception("Value of parameter username $username it nof unique");
        }

        if (!$repository->isUniqueUser('email', 0, $email))
        {
            throw new \Exception("Value of parameter email $email it nof unique");
        }

        if (!in_array($role, User::getRoleListForAssert()))
        {
            throw new \Exception("Value $role it must be included in list  User::getRoleListForAssert()");
        }

        $user->setUsername($username);
        $user->setPassword($password);
        $user->setEmail($email);
        $user->setRole($role);
        $user->setPhone('98888887');
        $user->setWasChecked(true);
        $user->setIsActive(true);
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt($user->getCreatedAt());
        $em->persist($user);
        $em->flush();

        $output->writeln(sprintf('Added %s user with password %s in role %s', $username, $password, $role));
    }
} 