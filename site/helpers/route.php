<?php
/**
 * @version     1.0.0
 * @package     com_dztour
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

defined('_JEXEC') or die;

/**
 * DZ Tour Route Helper
 *
 * @static
 * @package     com_dztour
 */
abstract class DZTourHelperRoute
{
    protected static $lookup = null;

    /**
     * @param   integer  The route of the content item
     */
    public static function getTourRoute($id, $catid = 0, $language = 0)
    {
        //Create the link
        $link = 'index.php?option=com_dztour&view=tour&id='. $id;

        if ($itemId = self::_findItemid(array('tour', 'tours')))
            $link .= '&Itemid='.$itemId;
        
        return $link;
    }
    
    protected static function _findItemid($needle)
    {
        $app        = JFactory::getApplication();
        $menus      = $app->getMenu('site');
        
        // Prepare the reverse lookup array.
        if (self::$lookup === null)
        {
            self::$lookup = array();

            $component  = JComponentHelper::getComponent('com_dztour');
            $items      = $menus->getItems('component_id', $component->id);
            
            foreach ($items as $item)
            {
                if (isset($item->query) && isset($item->query['view']))
                {
                    $view = $item->query['view'];
                    self::$lookup[$view] = $item->id;
                }
            }
        }
        
        foreach ($needle as $view) {
            if (isset(self::$lookup[$view]))
                return self::$lookup[$view];
        }
            
        return null;
    }
}
