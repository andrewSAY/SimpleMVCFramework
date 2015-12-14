<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 31.08.15
 * Time: 10:02
 */

namespace LW\Model;


use LW\Core\Forms\Fields\FieldCheckBox;
use LW\Core\Forms\Fields\FieldDateTime;
use LW\Core\Forms\Fields\FieldDateTimeLists;
use LW\Core\Forms\Fields\FieldDropDawnList;
use LW\Core\Forms\Fields\FieldFile;
use LW\Core\Forms\Fields\FieldHidden;
use LW\Core\Forms\Fields\FieldPassword;
use LW\Core\Forms\Fields\FieldSubmit;
use LW\Core\Forms\Fields\FieldText;
use LW\Core\Forms\Fields\FieldTextArea;
use LW\Core\Forms\Form;
use LW\Core\Forms\Validation\Rules\IsEmail;
use LW\Core\Forms\Validation\Rules\IsMatch;
use LW\Core\Forms\Validation\Rules\IsUniqueInMethod;
use LW\Core\Forms\Validation\Rules\Length;
use LW\Core\Forms\Validation\Rules\NotEmpty;
use LW\Model\Dictionary\Dictionary;
use LW\Model\Entity\User;
use LW\Model\Repository\UserRepository;

class FormProvider
{
    public function getUserEditForm($action, UserRepository $userRepository, $userId = 0)
    {
        $form = new Form('user_edit_form', $action);
        $currentYear = date('Y', time());

        $form->addField((new FieldText('username', 'Логин'))
                ->addRule(new NotEmpty())
                ->addRule(new IsUniqueInMethod($userRepository, 'isUniqueUser', array('username', $userId)))
        )
            ->addField((new FieldText('password', 'Пароль', array('class' => 'password')))
                    ->addRule(new NotEmpty())
                    ->addRule(new Length(6, 50))
            )
            ->addField((new FieldText('email', 'E-mail'))
                    ->addRule(new IsEmail())
                    ->addRule(new IsUniqueInMethod($userRepository, 'isUniqueUser', array('email', $userId)))
            )
            ->addField(new FieldDropDawnList('role', 'Роль', User::getRoleList()))
            ->addField(new FieldCheckBox('isActive', 'Активен', array(), true))
            ->addField(new FieldText('firstName', 'Фамилия'))
            ->addField(new FieldText('name', 'Имя'))
            ->addField(new FieldText('secondName', 'Отчество'))
            ->addField(new FieldText('name', 'Имя'))
            ->addField(new FieldDateTimeLists('dateBorn', 'Дата рождения', $currentYear - 115, $currentYear - 15, true))
            ->addField((new FieldText('phone', 'Телефон', array(), '80000000000'))
                    ->addRule(new IsMatch('/d?/', 'Поле телефон должно содержать только цифры'))
            )
            ->addField(new FieldText('workPosition', 'Должность'))
            ->addField(new FieldText('workPlace', 'Место работы'))
            ->addField(new FieldSubmit('submit', 'submit'));

        return $form;
    }

    public function getUserDeleteForm($action)
    {
        $form = new Form('user_delete_form', $action);
        $form->addField(new FieldSubmit('submit', 'submit'));

        return $form;
    }

    public function getGroupMoveForm($action)
    {
        $form = new Form('group_move_form', $action);

        $form->addField(new FieldText('moves_data', ''))
            ->addField(new FieldSubmit('submit', ''));

        return $form;
    }

    public function getGroupEditForm()
    {
        $form = new Form('group_edit_form', '');

        $form->addField((new FieldText('name', 'Названние', array('style' => 'width: 100%;')))
                ->addRule(new NotEmpty())
        )
            ->addField(new FieldCheckBox('isActive', 'Активность', array(), true))
            ->addField(new FieldSubmit('submit', 'submit'));

        return $form;
    }

    public function getGroupDeleteForm($action)
    {
        $form = new Form('group_delete_form', $action);
        $form->addField(new FieldSubmit('submit', 'submit'));

        return $form;
    }

    public function getGroupUsersListForm($action)
    {
        $form = new Form('group_users_list_form', $action);

        $form->addField(new FieldText('users', ''))
            ->addField(new FieldSubmit('submit', ''));

        return $form;
    }

    public function getTaskEditForm($action)
    {
        $form = new Form('task_edit_form', $action);
        $dictionary = new Dictionary();

        $form->addField((new FieldText('theme', 'Тема'))
                ->addRule(new NotEmpty())
        )
            ->addField((new FieldTextArea('body', 'Задание'))
                    ->addRule(new NotEmpty())
            )
            ->addField(new FieldTextArea('users', ''))
            ->addField((new FieldTextArea('users_widget', 'Исполнители', array('readonly' => true)))
                    ->addRule(new NotEmpty())
            )
            ->addField(new FieldDateTime('dateLimit', 'Крайний срок'))
            ->addField(new FieldDropDawnList('hardLevel', 'Сложность', $dictionary->taskLevels()->getList()))
            ->addField(new FieldDropDawnList('speedLevel', 'Срочность', $dictionary->taskSpeeds()->getList()))
            ->addField(new FieldSubmit('submit', 'Сохранить', array(), 'Сохранить'));

        return $form;
    }

    public function getTaskFilter($value, $list)
    {
        $form = new Form('task_filter_form', '');

        $form->addField(new FieldDropDawnList('state', 'Состояние', $list, false, false, array(), $value))
            ->addField(new FieldSubmit('submit', 'Применить', array(), 'Сохранить'));

        return $form;
    }

    public function getTaskReportForm($action)
    {
        $form = new Form('task_report_form', $action, 'post', array('enctype' => 'multipart/form-data'));

        $form->addField((new FieldTextArea('body', '', array('style' => 'width: 100%; height: 300px;')))
                ->addRule(new NotEmpty())
        )
            ->addField(new FieldSubmit('submit', '', array('style' => 'display: none;'), 'Сохранить'));

        return $form;
    }
} 