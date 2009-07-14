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

class OpenId_Validate_MatchOldPassword extends Zend_Validate_Abstract
{
    const INCORRECT = 'incorrect';

    /**
     * Array of error messages
     * @var array Error messages
     */
    protected $_messageTemplates = array
    (
        self::INCORRECT => "This does not match your old password"
    );

    /**
     * The model which contains the getUsername() and getPassword() methods
     * @var Model
     */
    protected $_model;

    /**
     *
     * @param <type> $model The model which contains the password. Must contain
     * getUsername() and getPassword() methods.
     */
    public function __construct($model)
    {
        $this->_model = $model;
    }

    /**
     * Gets the password from the model and matches it against the value supplied
     * @param string $value The password to match
     * @return boolean Returns true if the passwords match, false otherwise
     */
    public function isValid($value)
    {
        $this->_setValue($value);

        $value = md5($this->_model->getUsername().$value);

        if(!($this->_model->getPassword() == $value))
        {
            $this->_error();
            return false;
        }

        return true;
    }
}