<?php
/**
 * @version     1.0.0
 * @package     com_dztour
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */
// no direct access
defined('_JEXEC') or die;

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_dztour', JPATH_ADMINISTRATOR);

?>
<?php if ($this->item) : ?>

    <div class="item_fields">

        <ul class="fields_list">

                        <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_ID'); ?>:
            <?php echo $this->item->id; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_ORDERING'); ?>:
            <?php echo $this->item->ordering; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_STATE'); ?>:
            <?php echo $this->item->state; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_ACCESS'); ?>:
            <?php echo $this->item->access; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_LANGUAGE'); ?>:
            <?php echo $this->item->language; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_CHECKED_OUT'); ?>:
            <?php echo $this->item->checked_out; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_CHECKED_OUT_TIME'); ?>:
            <?php echo $this->item->checked_out_time; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_CREATED'); ?>:
            <?php echo $this->item->created; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_CREATED_BY'); ?>:
            <?php echo $this->item->created_by; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_MODIFIED'); ?>:
            <?php echo $this->item->modified; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_MODIFIED_BY'); ?>:
            <?php echo $this->item->modified_by; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_TITLE'); ?>:
            <?php echo $this->item->title; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_ALIAS'); ?>:
            <?php echo $this->item->alias; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_FEATURED'); ?>:
            <?php echo $this->item->featured; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_ON_OFFER'); ?>:
            <?php echo $this->item->on_offer; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_PRICE'); ?>:
            <?php echo $this->item->price; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_SALEOFF_PRICE'); ?>:
            <?php echo $this->item->saleoff_price; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_DURATION'); ?>:
            <?php echo $this->item->duration; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_TYPEID'); ?>:
            <?php echo $this->item->typeid_title; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_LOCATIONID'); ?>:
            <?php echo $this->item->locationid_title; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_DESCRIPTIONS'); ?>:
            <?php echo $this->item->descriptions; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_IMAGES'); ?>:
            <?php echo $this->item->images; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_METADESC'); ?>:
            <?php echo $this->item->metadesc; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_METAKEY'); ?>:
            <?php echo $this->item->metakey; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_METADATA'); ?>:
            <?php echo $this->item->metadata; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_PARAMS'); ?>:
            <?php echo $this->item->params; ?></li>


        </ul>

    </div>
    
<?php
else:
    echo JText::_('COM_DZTOUR_ITEM_NOT_LOADED');
endif;
?>
