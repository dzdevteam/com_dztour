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
                <ul>
                    <li><?php echo $this->item->duration['days']; ?> days</li>
                    <li><?php echo $this->item->duration['nights']; ?> nights</li>
                </ul>
            </li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_TYPEID'); ?>:
            <?php echo $this->item->typeid_title; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_LOCATIONID'); ?>:
            <?php echo $this->item->locationid_title; ?></li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_DESCRIPTIONS'); ?>:
                <ul>
                    <li>Short:<br />
                        <?php echo $this->item->descriptions['short']; ?>
                    </li>
                    <li>Long:<br />
                        <?php echo $this->item->descriptions['long']; ?>
                    </li>
                    <li>Other:<br />
                        <?php echo $this->item->descriptions['other']; ?>
                    </li>
                    <li>Price policy:<br />
                        <?php echo $this->item->descriptions['price']; ?>
                    </li>
                </ul>
            </li>
            <li><?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_IMAGES'); ?>:
                <ul>
                    <li>Intro: <?php echo $this->item->images['intro']; ?></li>
                    <li>Intro alt: <?php echo $this->item->images['intro_alt']; ?></li>
                    <li>Full: <?php echo $this->item->images['full']; ?></li>
                    <li>Full alt: <?php echo $this->item->images['full_alt']; ?></li>
                    <li>Album: <?php echo $this->item->images['album']; ?></li>
                </ul>
            </li>
        </ul>

    </div>
    
    <?php if ( (boolean) $this->params->get('show_order', 1) ) : ?>
    <?php
        JHtml::_('jquery.ui'); // Ensure jquery to be loaded first
        JHtml::_('script', 'com_dztour/jquery.validate.min.js', true, true);
        JHtml::_('script', 'com_dztour/messages_vi.js', true, true);
        JHtml::_('script', 'com_dztour/bootstrap-datepicker.js',  true, true);
        JHtml::_('stylesheet', 'com_dztour/datepicker.css', true, true);
        JHtml::_('script', 'com_dztour/order.js', true, true);
    ?>
    <form id="order-form" action="<?php echo JRoute::_('index.php?option=com_dztour&task=tour.order&format=json'); ?>" method="POST">
        <input type="hidden" name="order[tourid]" value="<?php echo $this->item->id; ?>" />
        <legend>Order this tour</legend>
        <div id="alert-area"></div>
        <div class="row-fluid">
            <div class="span6">
                <?php echo $this->form->getLabel('name'); ?>
                <?php echo $this->form->getInput('name'); ?>
                
                <?php echo $this->form->getLabel('email'); ?>
                <?php echo $this->form->getInput('email'); ?>
                
                <?php echo $this->form->getLabel('phone'); ?>
                <?php echo $this->form->getInput('phone'); ?>
                
                <?php echo $this->form->getLabel('address'); ?>
                <?php echo $this->form->getInput('address'); ?>
                
            </div>
            <div class="span6">
                
                <?php echo $this->form->getLabel('adults'); ?>
                <?php echo $this->form->getInput('adults'); ?>
                
                <?php echo $this->form->getLabel('children'); ?>
                <?php echo $this->form->getInput('children'); ?>
                
                <?php echo $this->form->getLabel('start_date'); ?>
                <?php echo $this->form->getInput('start_date'); ?>
                
                <?php echo $this->form->getLabel('end_date'); ?>
                <input type="text" name="order[end_date]" id="order_end_date" data-duration="<?php echo (int) $this->item->duration['nights']; ?>" readonly="readonly" class="valid">
                
                <?php echo $this->form->getLabel('comment'); ?>
                <?php echo $this->form->getInput('comment'); ?>
                
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Order</button>
        <?php echo JHtml::_('form.token'); ?>
    </form>
    <?php endif; ?>
<?php
else:
    echo JText::_('COM_DZTOUR_ITEM_NOT_LOADED');
endif;
?>
