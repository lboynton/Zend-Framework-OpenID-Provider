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
 * Uses the data mapper design pattern to map user object to the database
 *
 * @author Lee Boynton
 */
class Default_Model_UserMapper extends Default_Model_DataMapper
{
    public function save(Default_Model_User $user)
    {
        $data = array
        (
            'username' => $user->getUsername(),
            'password' => md5($user->getUsername().$user->getPassword()),
            'created'  => date('Y-m-d H:i:s'),
            'openid' => Zend_OpenId::absoluteURL('/?user=' . $user->getUsername())
        );
        
        if (null === ($id = $user->getId()))
        {
            //$storage = new OpenId_OpenId_Provider_Storage_Db(new Default_Model_DbTable_User(), new Default_Model_DbTable_Site(), new Default_Model_DbTable_Association());
            //$server = new Zend_OpenId_Provider("/provider/login", "/provider/trust", null, $storage);

            //$username = Zend_OpenId::absoluteURL('/?user=' . $user->getUsername());

            $this->getDbTable()->insert($data);
            //$server->register($user->getUsername(), $user->getPassword());
        }
        else
        {
            $data = array
            (
                'password'  => $user->getPassword()
            );

            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Default_Model_User $user)
    {
        $result = $this->getDbTable()->find($id);

        if (0 == count($result))
        {
            return;
        }

        $row = $result->current();

        $user->setId($row->id);
        $user->setUsername($row->username);
        $user->setCreated($row->created);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row)
        {
            $entry = new Default_Model_User();
            $entry->setId($row->id)
                ->setUsername($row->username)
                ->setCreated($row->created)
                ->setMapper($this);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable)
        {
            $this->setDbTable('Default_Model_DbTable_User');
        }
        return $this->_dbTable;
    }
}