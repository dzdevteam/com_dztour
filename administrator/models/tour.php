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

jimport('joomla.application.component.modeladmin');

/**
 * Dztour model.
 */
class DztourModeltour extends JModelAdmin
{
    /**
     * @var     string  The prefix to use with controller messages.
     * @since   1.6
     */
    protected $text_prefix = 'COM_DZTOUR';


    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param   type    The table type to instantiate
     * @param   string  A prefix for the table class name. Optional.
     * @param   array   Configuration array for model. Optional.
     * @return  JTable  A database object
     * @since   1.6
     */
    public function getTable($type = 'Tour', $prefix = 'DztourTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param   array   $data       An optional array of data for the form to interogate.
     * @param   boolean $loadData   True if the form is to load its own data (default case), false if not.
     * @return  JForm   A JForm object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Initialise variables.
        $app    = JFactory::getApplication();

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
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_dztour.edit.tour.data', array());

        if (empty($data)) {
            $data = $this->getItem();
            
        }

        return $data;
    }

    /**
     * Method to toggle the featured setting of tours.
     *
     * @param   array    The ids of the items to toggle.
     * @param   integer  The value to toggle to.
     *
     * @return  boolean  True on success.
     */
    public function featured($pks, $value = 0)
    {
        // Sanitize the ids.
        $pks = (array) $pks;
        JArrayHelper::toInteger($pks);

        if (empty($pks))
        {
            $this->setError(JText::_('COM_DZTOUR_NO_ITEM_SELECTED'));
            return false;
        }

        try
        {
            $db = $this->getDbo();
            $query = $db->getQuery(true)
                        ->update($db->quoteName('#__dztour_tours'))
                        ->set('featured = ' . (int) $value)
                        ->where('id IN (' . implode(',', $pks) . ')');
            $db->setQuery($query);
            $db->execute();
        }
        catch (Exception $e)
        {
            $this->setError($e->getMessage());
            return false;
        }

        $this->cleanCache();

        return true;
    }
    
    /**
     * Method to toggle the "on offer" setting of tours.
     *
     * @param   array    The ids of the items to toggle.
     * @param   integer  The value to toggle to.
     *
     * @return  boolean  True on success.
     */
    public function offer($pks, $value = 0)
    {
        // Sanitize the ids.
        $pks = (array) $pks;
        JArrayHelper::toInteger($pks);

        if (empty($pks))
        {
            $this->setError(JText::_('COM_DZTOUR_NO_ITEM_SELECTED'));
            return false;
        }

        try
        {
            $db = $this->getDbo();
            $query = $db->getQuery(true)
                        ->update($db->quoteName('#__dztour_tours'))
                        ->set('on_offer = ' . (int) $value)
                        ->where('id IN (' . implode(',', $pks) . ')');
            $db->setQuery($query);
            $db->execute();
        }
        catch (Exception $e)
        {
            $this->setError($e->getMessage());
            return false;
        }

        $this->cleanCache();

        return true;
    }
    
    /**
     * Method to get a single record.
     *
     * @param   integer The id of the primary key.
     *
     * @return  mixed   Object on success, false on failure.
     * @since   1.6
     */
    public function getItem($pk = null)
    {
        if ($item = parent::getItem($pk)) {

            //Do any procesing on fields here if needed
            $registry = new JRegistry();
            $registry->loadString($item->images);
            $item->images = $registry->toArray();
            
            $registry = new JRegistry();
            $registry->loadString($item->descriptions);
            $item->descriptions = $registry->toArray();
            
            $registry = new JRegistry();
            $registry->loadString($item->duration);
            $item->duration = $registry->toArray();
            
            $registry = new JRegistry();
            $registry->loadString($item->metadata);
            $item->metadata = $registry->toArray();
            
            if (!empty($item->id))
            {
                $item->tags = new JHelperTags;
                $item->tags->getTagIds($item->id, 'com_dztour.tour');
            }
        }

        return $item;
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @since   1.6
     */
    protected function prepareTable($table)
    {
        jimport('joomla.filter.output');

        if (empty($table->id)) {

            // Set ordering to the last item if not set
            if (@$table->ordering === '') {
                $db = JFactory::getDbo();
                $db->setQuery('SELECT MAX(ordering) FROM #__dztour_tours');
                $max = $db->loadResult();
                $table->ordering = $max+1;
            }

        }
    }

}