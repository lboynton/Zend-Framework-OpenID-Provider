<?php
/*
 *
 * Copyright (c) 2009, the University of Portsmouth. All rights reserved.
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
 *   All advertising materials mentioning features or use of
 *   this software must display the following acknowledgement:
 *   This product includes software developed by the University of Portsmouth
 *   and its contributors.
 *
 *   Neither the name of the University of Portsmouth nor the names of its
 *   contributors may be used to endorse or promote products
 *   derived from this software without specific prior
 *   written permission of the University of Portsmouth.
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
 * A user's personal information, such as name, email etc. Details are stored as
 * key/values. The name of the personal information is stored in the key, the
 * information itself is stored in the value.
 *
 * @author Lee Boynton
 */
class Default_Model_UserDetail
{
    protected $_id;
    protected $_key;
    protected $_value;
    protected $_mapper;

    /**
     * Gets the ID of the user this user detail belongs to
     * @return int User ID
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the ID of the user this user detail belongs to
     * @param int $id User ID
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * Gets the name of the user detail
     * @return string Name of user detail
     */
    public function getKey()
    {
        return $this->_key;
    }

    /**
     * Sets the name of the user detail
     * @param string $key Name of user detail
     */
    public function setKey($key)
    {
        $this->_key = $key;
    }

    /**
     * Gets the value of the user detail
     * @return string Value
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Sets the value of the user detail
     * @param string $value Value
     */
    public function setValue($value)
    {
        $this->_value = $value;
    }

    /**
     * Sets the data mapper
     * @param Default_Model_DataMapper $mapper Data mapper
     * @return Default_Model_UserDetail
     */
    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    /**
     * Gets the data mapper
     * @return Default_Model_DataMapper Data mapper
     */
    public function getMapper()
    {
        if (null === $this->_mapper)
        {
            $this->setMapper(new Default_Model_UserDetailMapper());
        }
        return $this->_mapper;
    }

    /**
     * Saves this user detail in persistent storage
     */
    public function save()
    {
        $this->getMapper()->save($this);
    }
}