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
 * tour Table class
 */
class DztourTabletour extends JTable {

    /**
     * Constructor
     *
     * @param JDatabase A database connector object
     */
    public function __construct(&$db) {
        parent::__construct('#__dztour_tours', 'id', $db);
        
        JTableObserverTags::createObserver($this, array('typeAlias' => 'com_dztour.tour'));
        JObserverMapper::addObserverClassToClass('JTableObserverTags', 'DZTourTableTour', array('typeAlias' => 'com_dztour.tour'));
    }

    /**
     * Overloaded bind function to pre-process the params.
     *
     * @param   array       Named array
     * @return  null|string null is operation was satisfactory, otherwise returns an error
     * @see     JTable:bind
     * @since   1.5
     */
    public function bind($array, $ignore = '') {
        $input = JFactory::getApplication()->input;
        $task = $input->getString('task', '');
        if(($task == 'save' || $task == 'apply') && (!JFactory::getUser()->authorise('core.edit.state','com_dztour.tour.'.$array['id']) && $array['state'] == 1)){
            $array['state'] = 0;
        }
        $task = JRequest::getVar('task');
        if($task == 'apply' || $task == 'save'){
            $array['modified'] = date("Y-m-d H:i:s");
        }

        //Support for checkbox field: featured
        if (!isset($array['featured'])){
            $array['featured'] = 0;
        }

        //Support for checkbox field: on_offer
        if (!isset($array['on_offer'])){
            $array['on_offer'] = 0;
        }
        
        // Multiple types
        if (isset($array['typeid']) && is_array($array['typeid'])) {
            $array['typeid'] = implode(',', $array['typeid']);
        }
        

        if (isset($array['params']) && is_array($array['params'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['params']);
            $array['params'] = (string) $registry;
        }

        if (isset($array['metadata']) && is_array($array['metadata'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['metadata']);
            $array['metadata'] = (string) $registry;
        }
        
        if (isset($array['images']) && is_array($array['images'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['images']);
            $array['images'] = (string) $registry;
        }
        
        if (isset($array['descriptions']) && is_array($array['descriptions'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['descriptions']);
            $array['descriptions'] = (string) $registry;
        }
        
        if (isset($array['duration']) && is_array($array['duration'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['duration']);
            $array['duration'] = (string) $registry;
        }
        
        if(!JFactory::getUser()->authorise('core.admin', 'com_dztour.tour.'.$array['id'])){
            $actions = JFactory::getACL()->getActions('com_dztour','tour');
            $default_actions = JFactory::getACL()->getAssetRules('com_dztour.tour.'.$array['id'])->getData();
            $array_jaccess = array();
            foreach($actions as $action){
                $array_jaccess[$action->name] = $default_actions[$action->name];
            }
            $array['rules'] = $this->JAccessRulestoArray($array_jaccess);
        }
        //Bind the rules for ACL where supported.
        if (isset($array['rules']) && is_array($array['rules'])) {
            $this->setRules($array['rules']);
        }

        return parent::bind($array, $ignore);
    }
    
    /**
     * This function convert an array of JAccessRule objects into an rules array.
     * @param type $jaccessrules an arrao of JAccessRule objects.
     */
    private function JAccessRulestoArray($jaccessrules){
        $rules = array();
        foreach($jaccessrules as $action => $jaccess){
            $actions = array();
            foreach($jaccess->getData() as $group => $allow){
                $actions[$group] = ((bool)$allow);
            }
            $rules[$action] = $actions;
        }
        return $rules;
    }

    /**
     * Overloaded check function
     */
    public function check() {

        //If there is an ordering column and this is a new row then get the next ordering value
        if (property_exists($this, 'ordering') && $this->id == 0) {
            $this->ordering = self::getNextOrder();
        }

        // Check for unique alias
        // Checking valid title and alias
        if (trim($this->title) == '')
        {
            $this->setError(JText::_('COM_DZTOUR_WARNING_PROVIDE_VALID_NAME'));
            return false;
        }

        if (trim($this->alias) == '')
        {
            $this->alias = $this->title;
        }

        $this->alias = $this->_stringURLSafe($this->alias);

        if (trim(str_replace('-', '', $this->alias)) == '')
        {
            $this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
        }

        // Verify that the alias is unique
        $table = JTable::getInstance('Tour', 'DZTourTable');
        if ($table->load(array('alias' => $this->alias)) && ($table->id != $this->id || $this->id == 0))
        {
            $this->setError(JText::_('COM_DZTOUR_ERROR_UNIQUE_ALIAS'));
            return false;
        }
        
        return parent::check();
    }
    
    /**
     * Overrides JTable::store to set modified data and user id.
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success.
     */
    public function store($updateNulls = false)
    {
        $date = JFactory::getDate();
        $user = JFactory::getUser();
        
        if ($this->id) {
            $this->modified = $date->toSql();
            $this->modified_by = $user->get('id');
        } else {
            if (empty($this->created))
                $this->created = $date->toSql();
            if (empty($this->created_by))
                $this->created_by = $user->get('id');
        }
        
        $oldRules = $this->getRules();
        if (empty($oldRules))
        {
            $this->setRules('{}');
        }
        
        return parent::store($updateNulls);
    }

    /**
     * Method to set the publishing state for a row or list of rows in the database
     * table.  The method respects checked out rows by other users and will attempt
     * to checkin rows that it can after adjustments are made.
     *
     * @param    mixed    An optional array of primary key values to update.  If not
     *                    set the instance property value is used.
     * @param    integer The publishing state. eg. [0 = unpublished, 1 = published]
     * @param    integer The user id of the user performing the operation.
     * @return    boolean    True on success.
     * @since    1.0.4
     */
    public function publish($pks = null, $state = 1, $userId = 0) {
        // Initialise variables.
        $k = $this->_tbl_key;

        // Sanitize input.
        JArrayHelper::toInteger($pks);
        $userId = (int) $userId;
        $state = (int) $state;

        // If there are no primary keys set check to see if the instance key is set.
        if (empty($pks)) {
            if ($this->$k) {
                $pks = array($this->$k);
            }
            // Nothing to set publishing state on, return false.
            else {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
                return false;
            }
        }

        // Build the WHERE clause for the primary keys.
        $where = $k . '=' . implode(' OR ' . $k . '=', $pks);

        // Determine if there is checkin support for the table.
        if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
            $checkin = '';
        } else {
            $checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
        }

        // Update the publishing state for rows with the given primary keys.
        $this->_db->setQuery(
                'UPDATE `' . $this->_tbl . '`' .
                ' SET `state` = ' . (int) $state .
                ' WHERE (' . $where . ')' .
                $checkin
        );
        $this->_db->query();

        // Check for a database error.
        if ($this->_db->getErrorNum()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        // If checkin is supported and all rows were adjusted, check them in.
        if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
            // Checkin each row.
            foreach ($pks as $pk) {
                $this->checkin($pk);
            }
        }

        // If the JTable instance value is in the list of primary keys that were set, set the instance.
        if (in_array($this->$k, $pks)) {
            $this->state = $state;
        }

        $this->setError('');
        return true;
    }
    
    /**
      * Define a namespaced asset name for inclusion in the #__assets table
      * @return string The asset name 
      *
      * @see JTable::_getAssetName 
    */
    protected function _getAssetName() {
        $k = $this->_tbl_key;
        return 'com_dztour.tour.' . (int) $this->$k;
    }
 
    /**
      * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
      *
      * @see JTable::_getAssetParentId 
    */
    protected function _getAssetParentId($table = null, $id = null){
        // We will retrieve the parent-asset from the Asset-table
        $assetParent = JTable::getInstance('Asset');
        // Default: if no asset-parent can be found we take the global asset
        $assetParentId = $assetParent->getRootId();
        // The item has the component as asset-parent
        $assetParent->loadByName('com_dztour');
        // Return the found asset-parent-id
        if ($assetParent->id){
            $assetParentId=$assetParent->id;
        }
        return $assetParentId;
    }
    
    /**
     * Convert string into URL safe one
     * @param string $url
     *
     * @return string Safe URL
     */
    protected function _stringURLSafe($url) {
        // Support russian
        $tr = array(
            "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
            "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
            "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
            "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
            "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
            "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
            "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
        );
        $url= strtr($url,$tr);
        
        // Other languages
        setlocale(LC_ALL, 'en_US.UTF8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $url);
        $clean = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_| -]+/", '-', $clean);

        return $clean;
    }

}
