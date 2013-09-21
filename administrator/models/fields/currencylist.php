<?php
/**
 * @version     1.0.0
 * @package     com_dztour
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package     Joomla.Libraries
 * @subpackage  Form
 * @since       3.1
 */
class JFormFieldCurrencyList extends JFormFieldList
{
    /**
     * @var    string
     * @since  3.1
     */
    public $type = 'CurrencyList';

    
    /**
     * Method to get a list of currency
     *
     * @return  array  The field option objects.
     *
     * @since   3.1
     */
    protected function getOptions()
    {
        $options = array();
        
        $currency_list = JComponentHelper::getParams('com_dztour')->get('currency',array('USD', 'VND'));
        foreach($currency_list as $key => $value) {
            $options[$key] = new stdClass;
            $options[$key]->value = $value;
            $options[$key]->text = $value;
        }

        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}
