<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserLogin
 *
 * @author Lee Boynton
 */
class Default_Form_UserLogin extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('text', 'username', array(
            'label'      => 'Username',
            'required'   => true,
            'filters'    => array('StringTrim', 'StripTags'),
            'class'      => 'text'
        ));

        $this->addElement('password', 'password', array(
            'label'      => 'Password',
            'required'   => true,
            'class'      => 'text'
        ));

        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Login',
        ));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'fieldset')),
            array('Description', array('placement' => 'prepend', 'class' => 'error')),
            'Form'
        ));
    }
}