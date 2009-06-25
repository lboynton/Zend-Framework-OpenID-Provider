<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader
        (
            array
            (
                'namespace' => 'Default_',
                'basePath'  => dirname(__FILE__),
            )
        );

        return $autoloader;
    }

    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
}

