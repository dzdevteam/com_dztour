<?php
/**
 * @version     1.0.0
 * @package     com_dztour
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

defined('_JEXEC') or die;

JLoader::register('ContentHelper', JPATH_ADMINISTRATOR . '/components/com_content/helpers/content.php');

/**
 * DZ Tour HTML helper
 *
 */
abstract class JHtmlTourAdministrator
{
    /**
     * Show the feature/unfeature links
     *
     * @param   int      $value      The state value
     * @param   int      $i          Row number
     * @param   boolean  $canChange  Is user allowed to change?
     *
     * @return  string       HTML code
     */
    public static function featured($value = 0, $i = 0, $canChange = true)
    {
        JHtml::_('bootstrap.tooltip');

        // Array of image, task, title, action
        $states = array(
            0   => array('unfeatured',  'tours.featured',    'COM_DZTOUR_UNFEATURED',   'COM_DZTOUR_TOGGLE_TO_FEATURE'),
            1   => array('featured',    'tours.unfeatured',  'COM_DZTOUR_FEATURED',     'COM_DZTOUR_TOGGLE_TO_UNFEATURE'),
        );
        $state  = JArrayHelper::getValue($states, (int) $value, $states[1]);
        $icon   = $state[0];

        if ($canChange)
        {
            $html   = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[3]) . '"><i class="icon-'
                    . $icon . '"></i></a>';
        }
        else
        {
            $html   = '<a class="btn btn-micro hasTooltip disabled' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><i class="icon-'
                    . $icon . '"></i></a>';
        }

        return $html;
    }
    
    /**
     * Show the offer/unoffer links
     *
     * @param   int      $value      The state value
     * @param   int      $i          Row number
     * @param   boolean  $canChange  Is user allowed to change?
     *
     * @return  string       HTML code
     */
    public static function offer($value = 0, $i = 0, $canChange = true)
    {
        JHtml::_('bootstrap.tooltip');

        // Array of image, task, title, action
        $states = array(
            0   => array('unpublish',  'tours.offer',    'COM_DZTOUR_OFFERED',   'COM_DZTOUR_TOGGLE_TO_OFFER'),
            1   => array('publish',    'tours.unoffer',  'COM_DZTOUR_UNOFFERED',     'COM_DZTOUR_TOGGLE_TO_UNOFFER'),
        );
        $state  = JArrayHelper::getValue($states, (int) $value, $states[1]);
        $icon   = $state[0];

        if ($canChange)
        {
            $html   = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[3]) . '"><i class="icon-'
                    . $icon . '"></i></a>';
        }
        else
        {
            $html   = '<a class="btn btn-micro hasTooltip disabled' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><i class="icon-'
                    . $icon . '"></i></a>';
        }

        return $html;
    }
}
