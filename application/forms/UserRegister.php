<?php

/**
 *
 *
 * @author Lee Boynton
 */
class Default_Form_UserRegister extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('text', 'name', array(
            'label'      => 'Name',
            'required'   => false,
            'filters'    => array('StringTrim', 'StripTags'),
            'class'      => 'title'
        ));

        $this->addElement('text', 'nickname', array(
            'label'      => 'Nickname',
            'required'   => false,
            'filters'    => array('StringTrim', 'StripTags'),
            'class'      => 'text'
        ));

        $this->addElement('text', 'username', array(
            'label'      => 'Username',
            'required'   => true,
            'filters'    => array('StringTrim', 'StripTags'),
            'class'      => 'text'
        ));

        $this->addElement('password', 'password', array(
            'label'      => 'Password',
            'required'   => true,
            'validators' => array(array('StringLength', false, 6)),
            'class'      => 'text'
        ));

        $this->addElement('password', 'password_confirm', array(
            'label'      => 'Confirm password',
            'required'   => false,
            'class'      => 'text'
        ));

        /*
        

        

        $this->addElement('text', 'email', array(
            'label'      => 'Email address',
            'required'   => true,
            'filters'    => array('StringTrim', 'StripTags'),
            'validators' => array('EmailAddress')
        ));
         * 
         */

        /**
        $this->addElement('captcha', 'captcha', array(
            'label'      => 'Please enter the 5 letters displayed below:',
            'required'   => true,
            'captcha'    => array(
                'captcha' => 'Figlet',
                'wordLen' => 5,
                'timeout' => 300
            )
        ));
         * 
         */

        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Register',
        ));

/*
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
 * 
 */

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'fieldset')),
            'Form'
        ));
    }
}