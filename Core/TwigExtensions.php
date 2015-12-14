<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 21.08.15
 * Time: 13:18
 */

namespace LW\core;

use LW\Core\Forms\Form;
use LW\Core\UserSession\Authentication;

class TwigExtensions extends \Twig_Extension
{
    public function getName()
    {
        return 'lwTwigExtension';
    }

    public function getGlobals()
    {
        return array(
            'app' => $this->getApp()
        );
    }

    public function  getFilters()
    {
        return array(
            new \Twig_SimpleFilter('set_ds', array($this, 'setDs'))
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('asset', array($this, 'asset')),
            new \Twig_SimpleFunction('path', array($this, 'path')),
            new \Twig_SimpleFunction('form', array($this, 'form'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_begin', array($this, 'formBegin'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_end', array($this, 'formEnd'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_caption', array($this, 'formCaption'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_label', array($this, 'formLabel'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_field', array($this, 'formField'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('form_field_errors', array($this, 'formFieldErrors'), array('is_safe' => array('html'))),
        );
    }

    public function asset($value, $absolute = false)
    {
        if (!$absolute)
        {
            return '/' . $value;
        }
        $value = $this->getApp()->getResponse()->getPrepareUrl() . '//' . $value;
        return $value;
    }

    public function path($value, $params = array())
    {
        return $this->getApp()->getRouter()->getUrlByRoute($value, $params);
    }

    public function setDs($value, $targetChars = '::')
    {
        $value = str_replace($targetChars, DS, $value);
        return $value;
    }

    public function form(Form $form)
    {
        return $form->getRender()->rendForm();
    }

    public function formBegin(Form $form)
    {
        return $form->getRender()->rendBeginForm();
    }

   public function formEnd(Form $form)
    {
        return $form->getRender()->rendEndForm();
    }

    public function formCaption(Form $form, $key)
    {
        return $form->getRender()->rendCaption($key);
    }

    public function formLabel(Form $form, $key)
    {
        return $form->getRender()->rendLabel($key);
    }

    public function formField(Form $form, $key)
    {
        return $form->getRender()->rendField($key);
    }

    public function formFieldErrors(Form $form, $key)
    {
        return $form->getRender()->rendFieldErrors($key);
    }

    private function getApp()
    {
        global $APP;
        return $APP->getAppPresentation();
    }
}