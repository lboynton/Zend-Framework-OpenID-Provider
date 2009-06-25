<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserMapper
 *
 * @author Lee Boynton
 */
class Default_Model_UserMapper
{
    protected $_dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable))
        {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract)
        {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable)
        {
            $this->setDbTable('Default_Model_DbTable_User');
        }
        return $this->_dbTable;
    }

    public function save(Default_Model_User $user)
    {
        if (null === ($id = $user->getId()))
        {
            $storage = new OpenId_OpenId_Provider_Storage_Db(new Default_Model_DbTable_User(), new Default_Model_DbTable_Site(), new Default_Model_DbTable_Association());
            $server = new Zend_OpenId_Provider("/provider/login", "/provider/trust", null, $storage);

            $username = Zend_OpenId::absoluteURL('/?user=' . $user->getUsername());

            $server->register($username, $user->getPassword());
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
        
        $user->setId($row->id)
            ->setUsername($row->username)
            ->setCreated($row->created);
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
}
?>
