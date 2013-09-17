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
    
    /**
     * Returns a published state on a grid
     *
     * @param   integer       $value         The state value.
     * @param   integer       $i             The row index
     * @param   string|array  $prefix        An optional task prefix or an array of options
     * @param   boolean       $enabled       An optional setting for access control on the action.
     * @param   string        $checkbox      An optional prefix for checkboxes.
     *
     * @return  string  The HTML markup
     *
     * @see     JHtmlJGrid::state
     * @since   1.6
     */
    public static function published($value, $i, $prefix = '', $enabled = true, $checkbox = 'cb')
    {
        if (is_array($prefix))
        {
            $options = $prefix;
            $enabled = array_key_exists('enabled', $options) ? $options['enabled'] : $enabled;
            $checkbox = array_key_exists('checkbox', $options) ? $options['checkbox'] : $checkbox;
            $prefix = array_key_exists('prefix', $options) ? $options['prefix'] : '';
        }

        $states = array(1 => array('unpublish', 'COM_DZTOUR_OPTION_CONFIRMED', 'COM_DZTOUR_HTML_UNPUBLISH_ITEM', 'COM_DZTOUR_OPTION_CONFIRMED', true, 'publish', 'publish'),
            0 => array('publish', 'COM_DZTOUR_OPTION_PENDING', 'COM_DZTOUR_HTML_PUBLISH_ITEM', 'COM_DZTOUR_OPTION_PENDING', true, 'unpublish', 'unpublish'),
            2 => array('unpublish', 'COM_DZTOUR_OPTION_ARCHIVED', 'COM_DZTOUR_HTML_UNPUBLISH_ITEM', 'COM_DZTOUR_OPTION_ARCHIVED', true, 'archive', 'archive'),
            -2 => array('publish', 'COM_DZTOUR_OPTION_CANCELLED', 'COM_DZTOUR_HTML_PUBLISH_ITEM', 'COM_DZTOUR_OPTION_CANCELLED', true, 'trash', 'trash'));

        return JHtmlJGrid::state($states, $value, $i, $prefix, $enabled, true, $checkbox);
    }
}
