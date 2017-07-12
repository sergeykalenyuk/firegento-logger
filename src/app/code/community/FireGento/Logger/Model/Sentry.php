<?php
/**
 * This file is part of a FireGento e.V. module.
 *
 * This FireGento e.V. module is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * PHP version 5
 *
 * @category  FireGento
 * @package   FireGento_Logger
 * @author    FireGento Team <team@firegento.com>
 * @copyright 2013 FireGento Team (http://www.firegento.com)
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */
/**
 * Model for Sentry logging
 *
 * @category FireGento
 * @package  FireGento_Logger
 * @author   FireGento Team <team@firegento.com>
 */
class FireGento_Logger_Model_Sentry extends Zend_Log_Writer_Abstract
{
    /**
     * @var Raven_Client
     */
    protected $_ravenClient;

    /**
     * @var bool Indicates if backtrace should be sent to Sentry.
     */
    protected $_enableBacktrace = FALSE;

    /**
     * Setter for class variable _enableBacktrace
     *
     * @param bool $flag Flag for Backtrace
     * @return $this
     */
    public function setEnableBacktrace($flag)
    {
        $this->_enableBacktrace = (bool)$flag;
        return $this;
    }

    /**
     * Retrieve Raven_Client instance
     *
     * @return Raven_Client|null
     */
    public function getRavenClient()
    {
        return $this->_ravenClient;
    }

    /**
     * Create Raven_Client instance
     *
     * @return void
     */
    public function initRavenClient()
    {
        if (is_null($this->_ravenClient)) {
            require_once Mage::getBaseDir('lib').DS.'sentry'.DS.'lib'.DS.'Raven'.DS.'Autoloader.php';
            Raven_Autoloader::register();
            $helper = Mage::helper('firegento_logger');
            $dsn = $helper->getLoggerConfig('sentry/dsn');
            $options = [
                'trace' => $this->_enableBacktrace,
                'curl_method' => $helper->getLoggerConfig('sentry/curl_method'),
            ];
            $this->_ravenClient = new Raven_Client($dsn, $options);
            $this->_ravenClient->install();
        }
    }

    /**
     * Write a message to the log
     *
     * Sentry has own build-in processing the logs.
     * Nothing to do here.
     *
     * @see FireGento_Logger_Model_Observer::actionPreDispatch()
     *
     * @param  array $event log data event
     * @return void
     */
    protected function _write($event) {}

    /**
     * Satisfy newer Zend Framework
     *
     * @param  array|Zend_Config $config Configuration
     * @return void
     */
    public static function factory($config) {}
}