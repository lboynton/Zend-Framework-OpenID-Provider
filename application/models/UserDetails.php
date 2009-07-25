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
 * Wrapper class for all of a user's details
 *
 * @author Lee Boynton
 */
class Default_Model_UserDetails
{
    protected $_userDetails;

    public function __construct(array $options = null)
    {
        if (is_array($options))
        {
            $this->setOptions($options);
        }
    }

    public function setOptions(array $options)
    {
        $this->_userDetails = array();

        foreach($options as $key => $value)
        {
            $detail = new Default_Model_UserDetail();
            $detail->setKey($key);
            $detail->setValue($value);
            $detail->setId(Zend_Auth::getInstance()->getIdentity()->id);
            $this->_userDetails[] = $detail;
        }
    }

    public function getUserDetails($id = null)
    {
        if(is_null($id)) $id = Zend_Auth::getInstance()->getIdentity()->id;

        if(is_null($id)) throw new Exception("Can't get user details. Must specify which user id to retrieve.");

        $mapper = new Default_Model_UserDetailMapper();
        $this->_userDetails = $mapper->findAllById($id);

        foreach($this->_userDetails as $detail)
        {
            $value = $detail->getValue();
            if(empty($value)) continue;
            
            $details[$detail->getKey()] = $detail->getValue();
        }

        return $details;
    }

    /**
     * Gets the specified user's details
     * @param string $openid The OpenID of the user whose details should be retrieved
     */
    public function getUserDetailsFromOpenId($openid)
    {
        $userMapper = new Default_Model_UserMapper();
        $user = $userMapper->findByOpenId($openid);

        return $this->getUserDetails($user->getId());
    }

    public function save()
    {
        foreach($this->_userDetails as $detail)
        {
            $detail->save();
        }
    }
}
