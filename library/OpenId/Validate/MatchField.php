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
 * Validation for checking two or more form fields match. Useful for password
 * confirmations.
 * 
 * @category   OpenId
 * @package    OpenId_Validate
 * @author     Lee Boynton
 */
class OpenId_Validate_MatchField extends Zend_Validate_Abstract
{
    const NO_MATCH = 'noMatch';

    /**
     *
     * @var array
     */
    protected $_messageTemplates = array
    (
        self::NO_MATCH => "Fields do not match"
    );

    /**
     * String array of the name of the fields to match
     * @var array
     */
    protected $_fieldsToMatch = array();

    /**
     * Initialises the array of fields which should be matched
     * @param array $fieldsToMatch String array of the names of the fields to
     * match
     */
    public function __construct($fieldsToMatch = array())
    {
        if(is_array($fieldsToMatch))
        {
            $this->_fieldsToMatch = $fieldsToMatch;
        }
        else $this->_fieldsToMatch[] = $fieldsToMatch;
    }

    /**
     * Performs validation
     * @param string $value The value of the field being validated
     * @param array $context String array of all the form elements
     * @return boolean Returns true if the form element is valid, false
     * otherwise
     */
    public function isValid($value, $context = null)
    {
        $this->_setValue($value);

        foreach($this->_fieldsToMatch as $field)
        {           
            if (!isset($context[$field]) || $value !== $context[$field])
            {
                 $this->_error();
                 return false;
            }
        }

        return true;
    }
}