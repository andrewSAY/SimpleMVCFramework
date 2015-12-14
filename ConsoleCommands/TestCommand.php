<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 25.08.15
 * Time: 10:20
 */

namespace LW\ConsoleCommands;
use LW\Core\Forms\Fields\FieldText;
use LW\Core\Forms\Form;
use LW\Core\Forms\Validation\Rules\IsEmail;
use LW\Core\UserSession\Authentication;
use LW\Core\UserSession\Session;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


use LW\Core\OrmProvider;
use Symfony\Component\Console\Command\Command;

class TestCommand extends Command
{
    protected function configure()
    {
        $this->setName('lw:test');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        include_once SITE_PATH.DS.'bootstup.php';
        $email = new FieldText('ine', 'ine', array(), 'doublesolo@yandex.ru');
        $email->addRule(new IsEmail());
        $form = new Form('','');
        $form->addField($email);
        $form->isValid();
    }
} 