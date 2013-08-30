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

/**
 * Dztour helper.
 */
class DztourHelper
{
    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '')
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_DZTOUR_TITLE_TOURS'),
            'index.php?option=com_dztour&view=tours',
            $vName == 'tours'
        );
        JHtmlSidebar::addEntry(
            'Categories (Tours - Type)',
            "index.php?option=com_categories&extension=com_dztour.tours.typeid",
            $vName == 'categories.tours'
        );
        
if ($vName=='categories.tours.typeid') {            
JToolBarHelper::title('DZ Tours Management: Categories (Tours - Type)');        
}       JHtmlSidebar::addEntry(
            'Categories (Tours - Location)',
            "index.php?option=com_categories&extension=com_dztour.tours.locationid",
            $vName == 'categories.tours'
        );
        
if ($vName=='categories.tours.locationid') {            
JToolBarHelper::title('DZ Tours Management: Categories (Tours - Location)');        
}       JHtmlSidebar::addEntry(
            JText::_('COM_DZTOUR_TITLE_ORDERS'),
            'index.php?option=com_dztour&view=orders',
            $vName == 'orders'
        );

    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return  JObject
     * @since   1.6
     */
    public static function getActions()
    {
        $user   = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_dztour';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }
}
