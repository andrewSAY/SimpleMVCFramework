<?php

namespace LW\Controller;

use LW\Core\ControllerBase;
use LW\Core\Forms\Form;
use LW\Core\Forms\Fields as Fields;

class HelloController extends ControllerBase
{
    public function indexAction()
    {
        if ($this->authentication->getCurrentUser()->getId() == 0)
        {
            $solution = $this->acceptSolutionRedirectToRoute('introduce');
        } else
        {
            $solution = $this->acceptSolutionRedirectToRoute('cabinet', array($this->authentication->getCurrentUser()->getId()));
        }
        return $solution;
    }

    public function introduceAction()
    {
        $form = $this->getFormIntroduce();
        $form->writeFromRequest($this->request);

        if ($form->wasSubmitted())
        {
            if ($this->authentication->login($form->getByName('login')->getValue(), $form->getByName('password')->getValue()))
            {
                return $this->acceptSolutionRedirectToRoute('cabinet');
            } else
            {
                $form->addError('Неправильное имя пользователя и(или) пароль');
                $form->getByName('password')->setValue(null);
            }
        }

        return $this->acceptSolutionRenderView('hello::introduce.html.twig', array('form' => $form));
    }

    public function logoutAction()
    {
        $this->authentication->logout();
        return $this->acceptSolutionRedirectToRoute('default');
    }

    private function getFormIntroduce()
    {
        $form = new Form('introduce_form', $this->router->getUrlByRoute('introduce', array(), false));
        $form->addField(new Fields\FieldText('login', 'Имя пользователя', array('style' => 'float: left; width: 300px;')));
        $form->addField(new Fields\FieldPassword('password', 'Пароль', array('style' => 'float: left; width: 300px;')));
        $form->addField(new Fields\FieldSubmit('submit', '', array('style' => 'float: right;'), 'Войти'));
        return $form;
    }

} 