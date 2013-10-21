<?php
/**
 * @version     1.0.0
 * @package     com_dztour
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */
 
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class DztourController extends JControllerLegacy
{
    protected $privatekey = null;
    
    /**
     * Override construct to support reCaptcha across all controllers
     *
     * @param array() $config
     * @return void
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
        
        require_once JPATH_COMPONENT.'/helpers/recaptchalib.php';
        $this->privatekey = JFactory::getApplication()->getParams('com_dztour')->get('privatekey');
        
        // Set content type based on format
        if ($this->input->get('format', '') == 'json')
            header('Content-Type: application/json');        
    }
    
    /**
     *  Override execute to wrap exception handling to our own
     * @param   string  $task  The task to perform. If no matching task is found, the '__default' task is executed, if defined.
     *
     * @return  mixed   The value returned by the called method, false in error case.
     */
    public function execute($task)
    {
        try {
            parent::execute($task);
        } catch (Exception $e) {
            $this->catchException($e);
        }
    }
    
    /**
     * Entry point for throwing exception and exit
     *
     * @param Exception $e The exception to be rethrown or display
     * @param int $status_code HTTP status code (optional)
     *
     * @return void
     */
     protected function catchException($e, $status_code = 500) {
        // If the client request json output, we throw a JSON object out
        if ($this->input->get('format', '') == 'json') {
            header($_SERVER['SERVER_PROTOCOL'] . " $status_code " . $e->getMessage(), true, $status_code);
            header('Content-Type: application/json');
            echo $this->encodeMessage($e->getMessage(), 'nok');
            jexit();
        } else {
            throw $e; // rethrow 
        }
     }
    
         
    /**
      * Helper for generate success JSON message
      *
      * @param string $message The message to encode
      *
      * @return string JSON encoded message
      */
    protected function encodeMessage($message, $status = 'ok')
    {
        return json_encode(array('status' => $status, 'message' => $message));
    }
}