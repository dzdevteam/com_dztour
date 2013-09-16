<?php
/**
 * @version     1.0.0
 * @package     com_dztour
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class com_dztourInstallerScript
{
    /**
     * Set up the default parameter for this component
     *
     * @param string $type
     * @param string $parent
     *
     * @return void
     */
    public function postflight($type, $parent)
    {
        if ($type == 'install' || $type == 'discover_install') {
            $params['subject'] = "A new tour order has just been placed!";
            $params['body'] = 
            '<h1>%subject$s</h1>
            <br />Order information:
            <ul>
                <li>Tour: %tour$s</li>
                <li>Adults: %adults$s</li>
                <li>Children: %children$s</li>
                <li>Start Date: %start_date$s</li>
                <li>End Date: %end_date$s</li>
                <li>Comment: %comment$s</li>
            </ul>
            <br />Contact information:
            <ul>
                <li>Name: %name$s</li>
                <li>Email: %email$s</li>
                <li>Phone: %phone$s</li>
                <li>Address: %address$s</li>
            </ul>';
            
            $params['confirmsubject'] = "You have ordered a tour from our service!";
            $params['confirmbody'] = $params['body'];
            
            echo '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">×</button>Setting up default email templates and subjects...</div>';
            $this->setParams($params);
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><strong>DZ Product has been configured successfully!</strong></div>';
        }
    }
    
    public function setParams($param_array) {
        if ( count($param_array) > 0 ) {
            // read the existing component value(s)
            $db = JFactory::getDbo();
            $db->setQuery('SELECT params FROM #__extensions WHERE name = "com_dztour"');
            $params = json_decode( $db->loadResult(), true );
            // add the new variable(s) to the existing one(s)
            foreach ( $param_array as $name => $value ) {
                    $params[ (string) $name ] = (string) $value;
            }
            // store the combined new and existing values back as a JSON string
            $paramsString = json_encode( $params );
            $db->setQuery('UPDATE #__extensions SET params = ' .
                $db->quote( $paramsString ) .
                ' WHERE name = "com_dztour"' );
                $db->query();
        }
    }
}