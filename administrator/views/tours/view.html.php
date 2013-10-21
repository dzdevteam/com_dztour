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
class DztourViewTours extends JViewLegacy
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
        
        DztourHelper::addSubmenu('tours');
        
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

        JToolBarHelper::title(JText::_('COM_DZTOUR_TITLE_TOURS'), 'tours.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/tour';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('tour.add','JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('tour.edit','JTOOLBAR_EDIT');
            }

        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('tours.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('tours.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'tours.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('tours.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('tours.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'tours.delete','JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                JToolBarHelper::trash('tours.trash','JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_dztour');
        }
        
        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_dztour&view=tours');
        
        $this->extra_sidebar = '';
        
        JHtmlSidebar::addFilter(

            JText::_('JOPTION_SELECT_PUBLISHED'),

            'filter_published',

            JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

        );

        JHtmlSidebar::addFilter(
            JText::_("JOPTION_SELECT_ACCESS"),
            'filter_access',
            JHtml::_('select.options', JHtml::_("access.assetgroups", true, true), "value", "text", $this->state->get('filter.access'), true)

        );

        JHtmlSidebar::addFilter(
            JText::_("COM_DZTOUR_OPTION_SELECT_TYPE"),
            'filter_typeid',
            JHtml::_('select.options', JHtml::_('category.options', 'com_dztour.tours.typeid'), "value", "text", $this->state->get('filter.typeid'))

        );

        JHtmlSidebar::addFilter(
            JText::_("COM_DZTOUR_OPTION_SELECT_LOCATION"),
            'filter_locationid',
            JHtml::_('select.options', JHtml::_('category.options', 'com_dztour.tours.locationid'), "value", "text", $this->state->get('filter.locationid'))

        );

        
    }
    
    protected function getSortFields()
    {
        return array(
        'a.id' => JText::_('JGRID_HEADING_ID'),
        'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
        'a.state' => JText::_('JSTATUS'),
        'a.access' => JText::_('COM_DZTOUR_TOURS_ACCESS'),
        'a.created_by' => JText::_('COM_DZTOUR_TOURS_CREATED_BY'),
        'a.title' => JText::_('COM_DZTOUR_TOURS_TITLE'),
        'a.featured' => JText::_('COM_DZTOUR_TOURS_FEATURED'),
        'a.on_offer' => JText::_('COM_DZTOUR_TOURS_ON_OFFER'),
        'a.price' => JText::_('COM_DZTOUR_TOURS_PRICE'),
        'a.saleoff_price' => JText::_('COM_DZTOUR_TOURS_SALEOFF_PRICE'),
        'a.typeid' => JText::_('COM_DZTOUR_TOURS_TYPEID'),
        'a.locationid' => JText::_('COM_DZTOUR_TOURS_LOCATIONID'),
        );
    }

    
}
