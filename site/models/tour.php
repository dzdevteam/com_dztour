<?php
/**
 * @version     1.0.0
 * @package     com_dztour
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

require_once JPATH_SITE.'/components/com_dztour/helpers/route.php';
/**
 * Dztour model.
 */
class DztourModelTour extends JModelForm
{
    
    var $_item = null;
    
    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since   1.6
     */
    protected function populateState()
    {
        $app = JFactory::getApplication('com_dztour');

        // Load state from the request userState on edit or from the passed variable on default
        if (JFactory::getApplication()->input->get('layout') == 'edit') {
            $id = JFactory::getApplication()->getUserState('com_dztour.edit.tour.id');
        } else {
            $id = JFactory::getApplication()->input->get('id');
            JFactory::getApplication()->setUserState('com_dztour.edit.tour.id', $id);
        }
        $this->setState('tour.id', $id);

        // Load the parameters.
        $params = $app->getParams();
        $params_array = $params->toArray();
        if(isset($params_array['item_id'])){
            $this->setState('tour.id', $params_array['item_id']);
        }
        $this->setState('params', $params);

    }
        

    /**
     * Method to get an ojbect.
     *
     * @param   integer The id of the object to get.
     *
     * @return  mixed   Object on success, false on failure.
     */
    public function &getData($id = null)
    {
        if ($this->_item === null)
        {
            $this->_item = false;

            if (empty($id)) {
                $id = $this->getState('tour.id');
            }

            // Get a level row instance.
            $table = $this->getTable();

            // Attempt to load the row.
            if ($table->load($id))
            {
                // Check published state.
                if ($published = $this->getState('filter.published'))
                {
                    if ($table->state != $published) {
                        return $this->_item;
                    }
                }

                // Convert the JTable to a clean JObject.
                $properties = $table->getProperties(1);
                $this->_item = JArrayHelper::toObject($properties, 'JObject');
                
                // Convert json encoded fields to array
                $registry = new JRegistry();
                $registry->loadString($this->_item->duration);
                $this->_item->duration = $registry->toArray();
                
                $registry = new JRegistry();
                $registry->loadString($this->_item->descriptions);
                $this->_item->descriptions = $registry->toArray();
                
                $registry = new JRegistry();
                $registry->loadString($this->_item->images);
                $this->_item->images = $registry->toArray();
                
                $registry = new JRegistry();
                $registry->loadString($this->_item->params);
                $this->_item->params = $registry->toArray();
                
                // Prebuild
                foreach (explode(',',$this->_item->typeid) as $typeid) {
                    $this->_item->types[$typeid]['title'] = $this->getCategoryName($typeid)->title;
                    $this->_item->types[$typeid]['link'] = JRoute::_(DZTourHelperRoute::getTypeRoute($typeid));
                    $this->_item->types[$typeid]['id'] = $typeid;
                }
                $this->_item->location['title'] = $this->getCategoryName($this->_item->locationid)->title;
                $this->_item->location['link'] = JRoute::_(DZTourHelperRoute::getLocationRoute($this->_item->locationid));
                $this->_item->location['id'] = $this->_item->locationid;
            } elseif ($error = $table->getError()) {
                $this->setError($error);
            }
        }

        return $this->_item;
    }
    
    public function getTable($type = 'Tour', $prefix = 'DztourTable', $config = array())
    {   
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
        return JTable::getInstance($type, $prefix, $config);
    }     

    public function getOrderForm()
    {
        jimport('joomla.form.form');
        JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
        return JForm::getInstance('com_dztour.order', 'order', array('control' => 'order'));        
    }
    /**
     * Method to check in an item.
     *
     * @param   integer     The id of the row to check out.
     * @return  boolean     True on success, false on failure.
     * @since   1.6
     */
    public function checkin($id = null)
    {
        // Get the id.
        $id = (!empty($id)) ? $id : (int)$this->getState('tour.id');

        if ($id) {
            
            // Initialise the table
            $table = $this->getTable();

            // Attempt to check the row in.
            if (method_exists($table, 'checkin')) {
                if (!$table->checkin($id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Method to check out an item for editing.
     *
     * @param   integer     The id of the row to check out.
     * @return  boolean     True on success, false on failure.
     * @since   1.6
     */
    public function checkout($id = null)
    {
        // Get the user id.
        $id = (!empty($id)) ? $id : (int)$this->getState('tour.id');

        if ($id) {
            
            // Initialise the table
            $table = $this->getTable();

            // Get the current user object.
            $user = JFactory::getUser();

            // Attempt to check the row out.
            if (method_exists($table, 'checkout')) {
                if (!$table->checkout($user->get('id'), $id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
        }

        return true;
    }    
    
    /**
     * Method to get the profile form.
     *
     * The base form is loaded from XML 
     * 
     * @param   array   $data       An optional array of data for the form to interogate.
     * @param   boolean $loadData   True if the form is to load its own data (default case), false if not.
     * @return  JForm   A JForm object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_dztour.tour', 'tour', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed   The data for the form.
     * @since   1.6
     */
    protected function loadFormData()
    {
        $data = $this->getData(); 
        
        return $data;
    }

    /**
     * Method to save the form data.
     *
     * @param   array       The form data.
     * @return  mixed       The user id on success, false on failure.
     * @since   1.6
     */
    public function save($data)
    {
        $id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('tour.id');
        $state = (!empty($data['state'])) ? 1 : 0;
        $user = JFactory::getUser();

        if($id) {
            //Check the user can edit this item
            $authorised = $user->authorise('core.edit', 'com_dztour.tour.'.$id) || $authorised = $user->authorise('core.edit.own', 'com_dztour.tour.'.$id);
            if($user->authorise('core.edit.state', 'com_dztour.tour.'.$id) !== true && $state == 1){ //The user cannot edit the state of the item.
                $data['state'] = 0;
            }
        } else {
            //Check the user can create new items in this section
            $authorised = $user->authorise('core.create', 'com_dztour');
            if($user->authorise('core.edit.state', 'com_dztour.tour.'.$id) !== true && $state == 1){ //The user cannot edit the state of the item.
                $data['state'] = 0;
            }
        }

        if ($authorised !== true) {
            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
            return false;
        }
        
        $table = $this->getTable();
        if ($table->save($data) === true) {
            return $id;
        } else {
            return false;
        }
        
    }
    
     function delete($data)
    {
        $id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('tour.id');
        if(JFactory::getUser()->authorise('core.delete', 'com_dztour.tour.'.$id) !== true){
            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
            return false;
        }
        $table = $this->getTable();
        if ($table->delete($data['id']) === true) {
            return $id;
        } else {
            return false;
        }
        
        return true;
    }
    
    function getCategoryName($id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query 
            ->select('title')
            ->from('#__categories')
            ->where('id = ' . $id);
        $db->setQuery($query);
        return $db->loadObject();
    }
    
}