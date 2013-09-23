<?php
/**
 * @version     1.0.0
 * @package     com_dztour
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

defined('_JEXEC') or die;

abstract class JHtmlDZTour
{
    /**
     * Cached array of the tag items.
     *
     * @var    array
     * @since  3.1
     */
    protected static $items = array();
    
    /**
     * Returns an array of tags.
     *
     * @param   array  $config  An array of configuration options. By default, only published and unpublished tags are returned.
     *
     * @return  array  Tag data
     *
     * @since   3.1
     */
    public static function tags($config = array('filter.published' => array(0, 1)))
    {
        $hash = md5(serialize($config));
        $config = (array) $config;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('a.id, a.title, a.level, a.parent_id')
            ->from('#__tags AS a')
            ->join('INNER', '#__contentitem_tag_map as m ON m.tag_id = a.id AND type_alias = "com_dztour.tour"')
            ->where('a.parent_id > 0')
            ;

        // Filter on the published state
        if (isset($config['filter.published']))
        {
            if (is_numeric($config['filter.published']))
            {
                $query->where('a.published = ' . (int) $config['filter.published']);
            }
            elseif (is_array($config['filter.published']))
            {
                JArrayHelper::toInteger($config['filter.published']);
                $query->where('a.published IN (' . implode(',', $config['filter.published']) . ')');
            }
        }

        $query->order('a.lft');

        $db->setQuery($query);
        $items = $db->loadObjectList();

        // Assemble the list options.
        static::$items[$hash] = array();

        foreach ($items as &$item)
        {
            $repeat = ($item->level - 1 >= 0) ? $item->level - 1 : 0;
            $item->title = str_repeat('- ', $repeat) . $item->title;
            static::$items[$hash][] = JHtml::_('select.option', $item->id, $item->title);
        }
        return static::$items[$hash];
    }
}