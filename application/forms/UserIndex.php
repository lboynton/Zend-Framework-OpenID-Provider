<?php
/*
 *
 * $Id$
 *
 * Software License Agreement (BSD License)
 *
 * Copyright (c) 2009, University of Portsmouth
 * All rights reserved.
 *
 * Redistribution and use of this software in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 *   Redistributions of source code must retain the above
 *   copyright notice, this list of conditions and the
 *   following disclaimer.
 *
 *   Redistributions in binary form must reproduce the above
 *   copyright notice, this list of conditions and the
 *   following disclaimer in the documentation and/or other
 *   materials provided with the distribution.
 *
 *   Neither the name of University of Portsmouth nor the names of its
 *   contributors may be used to endorse or promote products
 *   derived from this software without specific prior
 *   written permission of University of Portsmouth
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A
 * PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR
 * TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * User details edit form
 *
 * @author Lee Boynton
 */
class Default_Form_UserIndex extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('text', 'fullname', array
        (
            'label'      => 'Name',
            'required'   => false,
            'filters'    => array('StringTrim', 'StripTags'),
            'class'      => 'title'
        ));

        $this->addElement('text', 'nickname', array
        (
            'label'      => 'Nickname',
            'required'   => false,
            'filters'    => array('StringTrim', 'StripTags'),
            'class'      => 'text'
        ));

        $this->addElement('text', 'email', array
        (
            'label'      => 'Email',
            'required'   => false,
            'validators' => array('emailAddress'),
            'filters'    => array('StringTrim', 'StripTags'),
            'class'      => 'text'
        ));

        $this->addElement('select', 'gender', array
        (
            'label'     => 'Gender',
            'required'  => false,
            'validators' => array(array('inArray', false, array(array('M', 'F')))),
            'multiOptions'  => array
            (
                '' => 'Not specified',
                'M' => 'Male',
                'F' => 'Female'
            )
        ));

        $this->addElement('text', 'dob', array
        (
            'label'     => 'Date of birth (dd/mm/yyyy)',
            'required'  => false,
            'class'     => 'text',
            'validators' => array(array('date', false, array('d/m/Y'))),
            'filters'    => array('StringTrim', 'StripTags'),
        ));

        // get the list of countries
        $countries = Zend_Locale::getTranslationList('Territory', 'en', 2);
        asort($countries, SORT_LOCALE_STRING);

        $country = new Zend_Form_Element_Select('country');
        $country->setLabel('Country')
        ->addMultiOption('', 'Not specified') // default option
        ->addMultiOption('GB', 'United Kingdom') // move this to the top of the list
        ->addMultiOptions($countries)
        ->setRequired(false)
        ->addFilter('StripTags')
        ->addFilter('StringTrim');

        $postcode = new Zend_Form_Element_Text('postcode');
        $postcode->setLabel('Postcode')
        ->setAttrib("class", "text")
        ->setRequired(false)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');

        $languages = Zend_Locale::getLanguageTranslationList('en');
        asort($languages, SORT_LOCALE_STRING);

        $language = new Zend_Form_Element_Select('language');
        $language->setLabel('Language')
        ->addMultiOption('', 'Not specified') // default option
        ->addMultiOption('en_GB', 'British English') // move to top of list
        ->addMultiOptions($languages)
        ->setRequired(false)
        ->addFilter('StripTags')
        ->addFilter('StringTrim');

        $timeZone = new Zend_Form_Element_Text('timezone');
        $timeZone->setLabel('Timezone')
        ->setAttrib("class", "text")
        ->setRequired(false)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');

        $this->addElements(array($country, $postcode, $language, $timeZone));

        $this->addElement('submit', 'submit', array
        (
            'ignore'   => true,
            'label'    => 'Save',
        ));

        $this->setDecorators(array
        (
            'FormElements',
            array('HtmlTag', array('tag' => 'fieldset')),
            array('Description', array('placement' => 'prepend', 'class' => 'error')),
            'Form'
        ));
    }
}