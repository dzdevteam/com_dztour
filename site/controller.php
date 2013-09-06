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
    public function __construct()
    {
        parent::__construct();
        
        // Set content type based on format
        if ($this->input->get('format', '') == 'json')
            header('Content-Type: application/json');
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
            echo json_encode(array('status' => 'nok', 'message' => $e->getMessage()));
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
    protected function encodeMessage($message)
    {
        return json_encode(array('status' => 'nok', 'message' => $message));
    }
}