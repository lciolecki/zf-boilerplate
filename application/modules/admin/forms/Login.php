<?php

use System\Traits\DependencyInjection;

/**
 * Class Admin_Form_Login
 */
class Admin_Form_Login extends Twitter_Form
{
    use DependencyInjection;

    /**
     *  Initalization
     */
    public function init()
    {
        $this->setMethod('post');
        $this->addAttribs(array(
            'role' => 'form'
        ));

        $this->addElement('text', 'email', array(
            'required' => true,
            'value' => $this->getCookie()->get('email'),
            'filters' => array('StringTrim', array('StringToLower', array('UTF-8'))),
            'validators' => array(),
            'attribs' => array(
                'class' => 'form-control',
                'placeholder' => 'Email address'
            )
        ));

        $this->addElement('password', 'password', array(
            'required' => true,
            'validators' => array(),
            'attribs' => array(
                'class' => 'form-control',
                'placeholder' => 'Password'
            ),
        ));

        $this->addElement('checkbox', 'remember', array(
            'label' => 'Remember me',
            'value' => $this->getCookie()->get('remember')
        ));

        $this->addElement('button', 'submit', array(
            'label' => 'Sign In',
            'type' => 'submit',
            'class' => 'btn-lg btn-primary btn-block',
        ));
    }

    /**
     * Get system cookie
     *
     * @return \Extlib\System\Cookie
     */
    public function getCookie()
    {
        return $this->getDi()->get('Extlib\System\Cookie');
    }
}