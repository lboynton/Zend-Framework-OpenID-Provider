<?php

/**
 * TODO: Direct to error controller if requested controller/action doesn't exist
 * Access Control List manager for the website
 */
class OpenId_Controller_Plugin_AclManager extends Zend_Controller_Plugin_Abstract
{
    /**
     * Default user role if user is not logged in or an invalid role is found in
     * the database
     * @var string Name indicating the role of the user (guest/member/admin)
     */
    private $_defaultRole = 'guest';

    /**
     * The action to dispatch if a user doesn't have sufficient privileges
     * @var array Array containing the controller and action to dispatch
     */
    private $_authController = array('controller' => 'user', 'action' => 'login');

    public function __construct(Zend_Auth $auth)
    {
        $this->auth = $auth;
        $this->acl = new Zend_Acl();

        // add the different user roles
        $this->acl->addRole(new Zend_Acl_Role($this->_defaultRole));
        $this->acl->addRole(new Zend_Acl_Role('member'));

        // add the resources we want to have control over
        $this->acl->add(new Zend_Acl_Resource('user'));
        $this->acl->add(new Zend_Acl_Resource('site'));
        $this->acl->add(new Zend_Acl_Resource('index'));
        $this->acl->add(new Zend_Acl_Resource('provider'));
        $this->acl->add(new Zend_Acl_Resource('error'));

        // deny everything by default, explicitly allow access to resources and privileges
        $this->acl->deny();
        $this->acl->allow(null, 'user', 'register');
        $this->acl->allow(null, 'index');
        $this->acl->allow(null, 'provider');
        $this->acl->allow(null, 'error');
        $this->acl->allow('member', 'user');
        $this->acl->allow('member', 'site');
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        // check if a user is logged in and has a valid role, otherwise assign
        // default role to them
        if($this->auth->hasIdentity())
        {
            $role = $this->auth->getIdentity()->user_type;
        }
        else $role = $this->_defaultRole;

        if(!$this->acl->hasRole($role)) $role = $this->_defaultRole;

        // the ACL resource is the requested controller name
        $resource = $request->controller;

        // the ACL privilege is the requested action name
        $privilege = $request->action;

        // if we haven't explicitly added the resource, check the default global
        // permissions
        if(!$this->acl->has($resource)) $resource = null;

        // access denied - reroute the request to the default action handler
        if(!$this->acl->isAllowed($role, $resource, $privilege))
        {
            $request->setControllerName($this->_authController['controller']);
            $request->setActionName($this->_authController['action']);
        }
    }
}
