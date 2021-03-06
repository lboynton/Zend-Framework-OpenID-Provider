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
 * Loads SQL rows
 *
 * @author Lee Boynton
 */

// Initialize the application and bootstrap the database adapter
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__)) . '/../');
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', 'testing');

require_once 'Zend/Application.php';
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$bootstrap = $application->getBootstrap();
$bootstrap->bootstrap('db');
$dbAdapter = $bootstrap->getResource('db');

// Check to see if database exists
$options = $bootstrap->getOption('resources');
$dbFile  = $options['db']['params']['dbname'];
if (!file_exists($dbFile))
{
    die("No database present to insert data to");
}

// this block executes the actual statements that were loaded from
// the schema file.
try
{
        $dataSql = file_get_contents(dirname(__FILE__) . '/sql/data.sql');
        // use the connection directly to load sql in batches
        $dbAdapter->getConnection()->exec($dataSql);
}
catch (Exception $e)
{
    echo 'AN ERROR HAS OCCURED:' . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
    return false;
}

// generally speaking, this script will be run from the command line
return true;