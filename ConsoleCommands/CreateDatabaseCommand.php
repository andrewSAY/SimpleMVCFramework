<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 24.08.15
 * Time: 16:25
 */

namespace LW\ConsoleCommands;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDatabaseCommand extends Command
{
    protected function configure()
    {
        $this->setName('lw:pdo:create_database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        global $CONFIG;
        $dbConfig = $CONFIG['DB_CONFIG'];
        $host = $dbConfig['host'];
        $dbName = $dbConfig['dbname'];
        if ($dbConfig['port'] != null && $dbConfig['port'] != '')
        {
            $host = $host . ':' . $dbConfig['port'];
        }
        $dns = 'mysql:host=' . $host . '; charset=utf8;';

        $dbh = new \PDO($dns, $dbConfig['user'], $dbConfig['password']);


        $st = $dbh->prepare('CREATE DATABASE IF NOT EXISTS ' . $dbName . ' CHARACTER SET utf8 COLLATE utf8_general_ci');
        if ($st->execute())
        {
            $info = 'Successful';
        } else
        {
            $info = 'Failed';
        }
        $output->writeln($info);
    }
} 