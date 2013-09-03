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

jimport('joomla.application.component.view');

/**
 * View class for a list of Dztour.
 */
class DztourViewOrders extends JViewLegacy
{
    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->state        = $this->get('State');
        $this->items        = $this->get('Items');
        $this->pagination   = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        
        DztourHelper::addSubmenu('orders');
        
        $this->addToolbar();
        
        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        require_once JPATH_COMPONENT.'/helpers/dztour.php';

        $state  = $this->get('State');
        $canDo  = DztourHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_DZTOUR_TITLE_ORDERS'), 'orders.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/order';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('order.add','JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('order.edit','JTOOLBAR_EDIT');
            }

        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('orders.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('orders.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'orders.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('orders.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('orders.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'orders.delete','JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                JToolBarHelper::trash('orders.trash','JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_dztour');
        }
        
        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_dztour&view=orders');
        
        $this->extra_sidebar = '';
                //Filter for the field ".tourid;
        jimport('joomla.form.form');
        $options = array();
        $options[] = JHtml::_('select.option', "", JText::_('COM_DZTOUR_OPTION_SELECT_TOUR'));
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_dztour.order', 'order');

        $field = $form->getField('tourid');

        $query = $form->getFieldAttribute('tourid','query');
        $translate = $form->getFieldAttribute('tourid','translate');
        $key = $form->getFieldAttribute('tourid','key_field');
        $value = $form->getFieldAttribute('tourid','value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($translate == true)
                {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                }
                else
                {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }
        
        JHtmlSidebar::addFilter(
            'Tour',
            'filter_tourid',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.tourid')),
            true
        );
        JHtmlSidebar::addFilter(

            JText::_('JOPTION_SELECT_PUBLISHED'),

            'filter_published',

            JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

        );

        
    }
    
    protected function getSortFields()
    {
        return array(
        'a.id' => JText::_('JGRID_HEADING_ID'),
        'a.tourid' => JText::_('COM_DZTOUR_ORDERS_TOURID'),
        'a.state' => JText::_('JSTATUS'),        
        'a.name' => JText::_('COM_DZTOUR_ORDERS_NAME'),        
        'a.email' => JText::_('COM_DZTOUR_ORDERS_EMAIL'),
        'a.adults' => JText::_('COM_DZTOUR_ORDERS_ADULTS'),
        'a.children' => JText::_('COM_DZTOUR_ORDERS_CHILDREN'),
        'a.start_date' => JText::_('COM_DZTOUR_ORDERS_START_DATE'),
        'a.end_date' => JText::_('COM_DZTOUR_ORDERS_END_DATE'),
        'a.created' => JText::_('COM_DZTOUR_ORDERS_CREATED'),
        );
    }

    
}
