<?php

/**
 * @version     1.0.0
 * @package     com_dztour
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Dztour records.
 */
class DztourModelTours extends JModelList {

    protected $context = 'com_dztour';
    
    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'language', 'a.language',
                'created', 'a.created',
                'created_by', 'a.created_by',
                'modified', 'a.modified',
                'modified_by', 'a.modified_by',
                'title', 'a.title',
                'alias', 'a.alias',
                'featured', 'a.featured',
                'on_offer', 'a.on_offer',
                'price', 'a.price',
                'saleoff_price', 'a.saleoff_price',
                'typeid', 'a.typeid',
                'locationid', 'a.locationid'
            );
        }
        
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since   1.6
     */
    protected function populateState($ordering = null, $direction = null) {

        // Initialise variables.
        $app = JFactory::getApplication();

        // Load the parameters. Merge Global and Menu Item params into new object
        $params = $app->getParams();
        $menuParams = new JRegistry;

        if ($menu = $app->getMenu()->getActive())
        {
            $menuParams->loadString($menu->params);
        }

        $mergedParams = clone $menuParams;
        $mergedParams->merge($params);

        $this->setState('params', $mergedParams);

        // List state information
        $limit = $app->getUserStateFromRequest($this->context . '.list.limit', 'limit', $mergedParams->get('tours_limit', 12));
        $this->setState('list.limit', $limit);

        $value = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
        $limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
        $this->setState('list.start', $limitstart);

        // Check if the ordering field is in the white list, otherwise use the incoming value.
        $value = $mergedParams->get('tours_order_by', 'created');
        if (!in_array($value, $this->filter_fields))
        {
            $value = $ordering;
            $app->setUserState($this->context . '.ordercol', $value);
        }
        $this->setState('list.ordering', $value);

        // Check if the ordering direction is valid, otherwise use the incoming value.
        $value = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $mergedParams->get('tours_order_direction', 'DESC'));
        if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
        {
            $value = $direction;
            $app->setUserState($this->context . '.orderdirn', $value);
        }
        $this->setState('list.direction', $value);

        $this->setState('filter.language', JLanguageMultilang::isEnabled());
        
        $this->setState('filter.access', true);
        
        $type = $app->getUserStateFromRequest($this->context . '.filter.type', 'filter_typeid', $mergedParams->get('tours_typeid'));
        if ($type) {
            $this->setState('filter.typeid', $type);
        }
        
        $location = $app->getUserStateFromRequest($this->context . '.filter.location', 'filter_locationid', $mergedParams->get('tours_locationid'));
        if ($type) {
            $this->setState('filter.locationid', $location);
        }
        
        $display_items = $app->getUserStateFromRequest($this->context . '.filter.display_items', 'filter_display_items', $mergedParams->get('tours_display_items', 'all'));
        
        $this->setState('filter.display_items', $display_items);
        
        $types = $app->getUserStateFromRequest($this->context . '.filter.special_types', 'filter_special_types', $mergedParams->get('tours_special_types', array('featured')));
        foreach($types as $type)
            $this->setState('filter.' . $type, true);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  JDatabaseQuery
     * @since   1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );

        $query->from('`#__dztour_tours` AS a');

        
        // Join over the users for the checked out user.
        $query->select('uc.name AS editor');
        $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
    
        // Join over the created by field 'created_by'
        $query->select('created_by.name AS created_by');
        $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
        // Join over the category 'typeid'
        $query->select('t.title AS typeid_title');
        $query->join('LEFT', '#__categories AS t ON t.id = a.typeid');
        // Join over the category 'locationid'
        $query->select('l.title AS locationid_title');
        $query->join('LEFT', '#__categories AS l ON l.id = a.locationid');
        

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.title LIKE '.$search.' )');
            }
        }        

        //Filtering access
        $filter_access = $this->state->get("filter.access");
        if ($filter_access) {
            $user = JFactory::getUser();
            $groups = implode(',', $user->getAuthorisedViewLevels());
            $query->where('a.access IN (' . $groups . ')')
                ->where('t.access IN (' . $groups . ')')
                ->where('l.access IN (' . $groups . ')');
        }

        // Filter by type
        if ($this->getState('filter.display_items', 'all') == 'special') {
            $special = array();
            if ($this->getState('filter.featured', false))
                $special[] = 'featured = 1';
            if ($this->getState('filter.on_offer', false))
                $special[] = 'on_offer = 1';
            if ($this->getState('filter.saleoff', false))
                $special[] = 'saleoff_price != NULL';
            if (!empty($special))
                $query->where('(' . implode(' OR ', $special) . ')');
        }
        
        //Filtering typeid
        $filter_typeid = $this->state->get("filter.typeid");
        if ($filter_typeid) {
            $query->where("a.typeid = '".$filter_typeid."'");
        }
        
        //Filtering typeid
        $filter_typeid = $this->getState('filter.typeid', 'root');
        if (is_numeric($filter_typeid)) {
            // Add subcategories check
            $includeSubcategories = $this->getState('filter.subtypes', true);
            
            if ($includeSubcategories) {
                $subQuery = $db->getQuery(true)
                               ->select('sub.id')
                               ->from('#__categories as sub')
                               ->join('INNER', '#__categories as this ON sub.lft > this.lft AND sub.rgt < this.rgt')
                               ->where('this.id = ' . (int) $filter_typeid);
                $query->where("( a.typeid = ". (int) $filter_typeid . " OR a.typeid IN (" . (string) $subQuery . ") )");
            } else {
                $query->where("a.typeid = ". (int) $filter_typeid);
            }
        }
        
        //Filtering locationid
        $filter_locationid = $this->getState('filter.locationid', 'root');
        if (is_numeric($filter_locationid)) {
            // Add subcategories check
            $includeSubcategories = $this->getState('filter.sublocations', true);
            
            if ($includeSubcategories) {
                $subQuery = $db->getQuery(true)
                               ->select('sub.id')
                               ->from('#__categories as sub')
                               ->join('INNER', '#__categories as this ON sub.lft > this.lft AND sub.rgt < this.rgt')
                               ->where('this.id = ' . (int) $filter_locationid);
                $query->where("( a.locationid = ". (int) $filter_locationid . " OR a.locationid IN (" . (string) $subQuery . ") )");
            } else {
                $query->where("a.locationid = ". (int) $filter_locationid);
            }
        }

        // Filter by language
        if ($this->getState('filter.language')) {
            $query->where('a.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
        }
        
        // Add the list ordering clause.
        $query->order($this->getState('list.ordering', 'created') . ' ' . $this->getState('list.direction', 'DESC'));
        return $query;
    }

    public function getItems() {
        return parent::getItems();
    }

}
