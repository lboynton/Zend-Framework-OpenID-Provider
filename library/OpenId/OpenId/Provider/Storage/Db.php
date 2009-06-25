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
 * External storage of OpenID accounts in a database
 *
 * @author     Lee Boynton
 * @category   OpenId
 * @package    OpenId_OpenId
 * @subpackage OpenId_OpenId_Provider
 */
class OpenId_OpenId_Provider_Storage_Db extends Zend_OpenId_Provider_Storage
{
    /**
     * The table for storing user logins
     * @var Zend_Db_Table $_usersTable
     */
    private $_usersTable;
    /**
     * The table for storing trusted/untrusted sites
     * @var Zend_Db_Table $_sitesTable
     */
    private $_sitesTable;
    /**
     * The table for storing OpenID associations
     * @var Zend_Db_Table $_associationsTable;
     */
    private $_associationsTable;

    /**
     * Creates storage object
     *
     * @param Zend_Db_Table $usersTable
     * @param Zend_Db_Table $sitesTable
     * @param Zend_Db_Table $associationsTable
     */
    public function __construct($usersTable, $sitesTable, $associationsTable)
    {
        $this->_usersTable = $usersTable;
        $this->_sitesTable = $sitesTable;
        $this->_associationsTable = $associationsTable;
    }

    /**
     * Stores information about session identified by $handle
     *
     * @param string $handle assiciation handle
     * @param string $macFunc HMAC function (sha1 or sha256)
     * @param string $secret shared secret
     * @param string $expires expiration UNIX time
     * @return void
     */
    public function addAssociation($handle, $macFunc, $secret, $expires)
    {
        $row = $this->_associationsTable->createRow();
        $row->handle = $handle;
        $row->mac_func = trim($macFunc); // trim any extraneous whitespace that $macFunc seems to have
        $row->secret = base64_encode($secret);
        $row->expires = $expires;
        $row->save();

        return true;
    }

    /**
     * Gets information about association identified by $handle
     * Returns true if given association found and not expired and false
     * otherwise
     *
     * @param string $handle association handle
     * @param string &$macFunc HMAC function (sha1 or sha256)
     * @param string &$secret shared secret
     * @param string &$expires expiration UNIX time
     * @return bool
     */
    public function getAssociation($handle, &$macFunc, &$secret, &$expires)
    {
        $row = $this->_associationsTable->find($handle)->getRow(0);

        if($row == null) return false;
        if($row->expires < time()) return false;

        $macFunc = trim($row->mac_func); // double check $macFunc hasn't got any whitespace
        $secret = base64_decode($row->secret);
        $expires = $row->expires;

        return true;
    }

    /**
     * Register new user with given $id and $password
     * Returns true in case of success and false if user with given $id already
     * exists
     *
     * @param string $id user identity URL
     * @param string $password encoded user password
     * @return bool
     */
    public function addUser($id, $password)
    {        
        // escaping not required as values are inserted as parameters
        $user = array
        (
            'username' => $id,
            'password' => $password,
            'created'  => date('Y-m-d H:i:s'),
        );

        if($this->hasUser($id)) return false;
        else $this->_usersTable->insert($user);
        return true;
    }

    /**
     * Returns true if user with given $id exists and false otherwise
     *
     * @param string $id user identity URL
     * @return bool
     */
    public function hasUser($id)
    {
        $select = $this->_usersTable->select()->where('username = ?', $id);
        $row = $this->_usersTable->fetchRow($select);

        return !($row == null);
    }

    /**
     * Verify if user with given $id exists and has specified $password
     *
     * @param string $id user identity URL
     * @param string $password user password
     * @return bool
     */
    public function checkUser($id, $password)
    {
        $select = $this->_usersTable->select()
            ->where('id = ?', $id)
            ->where('password = ?', $password);

        $row = $this->_usersTable->fetchRow($select);

        return !($row == null);
    }

    /**
     * Returns array of all trusted/untrusted sites for given user identified
     * by $id
     *
     * @param string $id user identity URL
     * @return array
     */
    public function getTrustedSites($id)
    {
        $select = $this->_sitesTable->select()->where('openid = ?', $id);

        $rows = $this->_sitesTable->fetchAll($select);

        $array = $rows->toArray();
        $array['trusted'] = unserialize($array['trusted']);

        return $array;
    }

    /**
     * Stores information about trusted/untrusted site for given user
     *
     * @param string $id user identity URL
     * @param string $site site URL
     * @param mixed $trusted trust data from extensions or just a boolean value
     * @return bool
     */
    public function addSite($id, $site, $trusted)
    {
        if (is_null($trusted))
        {
            $this->_sitesTable->select()->where('site = ?', $site);
            $this->_sitesTable->delete($where);
            return true;
        }
        
        $row = $this->_sitesTable->createRow();
        $row->openid = $id;
        $row->site = $site;
        $row->time = date('Y-m-d H:i:s O');
        $row->trusted = serialize($trusted);
        $row->save();
        return true;
    }
}
