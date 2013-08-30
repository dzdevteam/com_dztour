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
class DztourModeltours extends JModelList {

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
                'access', 'a.access',
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
                'duration', 'a.duration',
                'typeid', 'a.typeid',
                'locationid', 'a.locationid',
                'descriptions', 'a.descriptions',
                'images', 'a.images',
                'metadesc', 'a.metadesc',
                'metakey', 'a.metakey',
                'metadata', 'a.metadata',
                'params', 'a.params',

            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        
        //Filtering access
        $this->setState('filter.access', $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', '', 'string'));

        //Filtering on_offer
        $this->setState('filter.on_offer', $app->getUserStateFromRequest($this->context.'.filter.on_offer', 'filter_on_offer', '', 'string'));

        //Filtering typeid
        $this->setState('filter.typeid', $app->getUserStateFromRequest($this->context.'.filter.typeid', 'filter_typeid', '', 'string'));

        //Filtering locationid
        $this->setState('filter.locationid', $app->getUserStateFromRequest($this->context.'.filter.locationid', 'filter_locationid', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_dztour');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.title', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param   string      $id A prefix for the store id.
     * @return  string      A store id.
     * @since   1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
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
    
        // Join over the user field 'created_by'
        $query->select('created_by.name AS created_by');
        $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
        // Join over the category 'typeid'
        $query->select('typeid.title AS typeid');
        $query->join('LEFT', '#__categories AS typeid ON typeid.id = a.typeid');
        // Join over the category 'locationid'
        $query->select('locationid.title AS locationid');
        $query->join('LEFT', '#__categories AS locationid ON locationid.id = a.locationid');

        
    // Filter by published state
    $published = $this->getState('filter.state');
    if (is_numeric($published)) {
        $query->where('a.state = '.(int) $published);
    } else if ($published === '') {
        $query->where('(a.state IN (0, 1))');
    }
    

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
            $query->where("a.access = '".$db->escape($filter_access)."'");
        }

        //Filtering on_offer

        //Filtering typeid
        $filter_typeid = $this->state->get("filter.typeid");
        if ($filter_typeid) {
            $query->where("a.typeid = '".$db->escape($filter_typeid)."'");
        }

        //Filtering locationid
        $filter_locationid = $this->state->get("filter.locationid");
        if ($filter_locationid) {
            $query->where("a.locationid = '".$db->escape($filter_locationid)."'");
        }


        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    public function getItems() {
        $items = parent::getItems();
        
        return $items;
    }

}
