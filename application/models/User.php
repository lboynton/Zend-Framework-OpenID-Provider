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
 * Represents an individual user
 *
 * @author Lee Boynton
 */
class Default_Model_User
{
    protected $_id;
    protected $_username;
    protected $_password;
    protected $_created;
    protected $_mapper;
    protected $_userType;

    /**
     * Create a new user with specified values
     * @param array $options Key/value pairs of options for the user
     */
    public function __construct(array $options = null)
    {
        if (is_array($options))
        {
            $this->setOptions($options);
        }
    }

    /**
     * Sets user property
     * @param string $name Name of the property to set
     * @param string $value Value of the property
     */
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method))
        {
            throw new Exception('Invalid user property');
        }
        $this->$method($value);
    }

    /**
     * Gets user property
     * @param string $name Name of the property to set
     * @return string Value of the property
     */
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method))
        {
            throw new Exception('Invalid user property');
        }
        return $this->$method();
    }

    /**
     * Sets multiple user properties. Will only set properties which have setter
     * methods.
     * @param array $options Array of properties to set
     * @return Default_Model_User
     */
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value)
        {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods))
            {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * Gets the user's id
     * @return int ID
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the user's id
     * @param int $id ID
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * Gets the user's username
     * @return string Username
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * Sets the user's username
     * @param String $username Username
     */
    public function setUsername($username)
    {
        $this->_username = $username;
    }

    /**
     * Gets the user's password
     * @return string Password
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * Sets the user's password
     * @param string $password Password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * Sets the date the user registered
     * @param string $created Date
     */
    public function setCreated($created)
    {
        $this->_created = $created;
    }

    /**
     * Gets the date the user registered
     * @return string Date
     */
    public function getCreated()
    {
        return $this->_created;
    }

    /**
     * Sets the user type (e.g. member, admin)
     * @param string $type User type
     */
    public function setUserType($type)
    {
        $this->_userType = $type;
    }

    /**
     * Gets the user type
     * @return string User type
     */
    public function getUserType()
    {
        return $this->_userType;
    }

    /**
     * Sets the data mapper
     * @param Default_Model_DataMapper $mapper
     * @return Default_Model_DataMapper
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
            $this->setMapper(new Default_Model_UserMapper());
        }
        return $this->_mapper;
    }

    /**
     * Saves this user in persistent storage
     */
    public function save()
    {
        $id = $this->getMapper()->save($this);

        // get id if this is a new user
        if (is_null($this->_id))
        {
            $this->_id = $id;
        }
    }

    /**
     * Find user with given id
     * @param int $id Id of user to find. If null finds currently logged in user
     * @return Default_Model_User The user that was found
     */
    public function find($id = null)
    {
        // get id of currently logged in user if id is null
        if($id == null) $id = Zend_Auth::getInstance()->getIdentity()->id;

        $this->getMapper()->find($id, $this);
        return $this;
    }

    /**
     * Gets all users
     * @return array Collection of Default_Model_User
     */
    public function fetchAll()
    {
        return $this->getMapper()->fetchAll();
    }

    /**
     * Gets the user's trusted sites
     * @param int $id ID of the user
     * @return Zend_Db_Table_Rowset Trusted sties
     */
    public function findSites($id = null)
    {
        if($id == null) $id = Zend_Auth::getInstance()->getIdentity()->id;

        $sites = $this->getMapper()->findSites($id);
        return $sites;
    }
}
