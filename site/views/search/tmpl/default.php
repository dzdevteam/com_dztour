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

$sortFields = $this->getSortFields();
JText::script('JGLOBAL_SELECT_AN_OPTION', '');
JHtml::_('behavior.framework');
JHtml::_('formbehavior.chosen', 'select');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

$listDirn = $this->state->get('list.direction');
$listOrder = $this->state->get('list.ordering');
JFactory::getDocument()->addScriptDeclaration("
function clearForm(ele) {
    jQuery(ele).find(':input').each(function() {
        switch(this.type) {
            case 'password':
            case 'select-one':
            case 'text':
            case 'textarea':
                jQuery(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
                break;
            case 'select-multiple':
                jQuery(this).append(jQuery('<option value=\"\"></option>'));
                jQuery(this).val('');
                break;
        }
    });
    jQuery(ele)[0].submit();
    return false;
}");
?>
<form method="POST">
    <div class="btn-toolbar">
        <div class="btn-group pull-left">
            <input type="text" name="filter_search" placeholder="<?php echo JText::_('COM_DZTOUR_SEARCH_KEYWORD'); ?>" id="search-searchword" size="30" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="inputbox" />
        </div>
        
        <div class="btn-group pull-left">
            <div class="input-prepend">
                <span class="add-on">&ge;</span>
                <input type="text" name="filter_price_min" class="input-small" placeholder="" value="<?php echo $this->state->get('filter.price.min'); ?>" class="inputbox" />
            </div>
            <div class="input-prepend">
                <span class="add-on">&le;</span>
                <input type="text" name="filter_price_max" class="input-small" placeholder="" value="<?php echo $this->state->get('filter.price.max'); ?>" class="inputbox" />
            </div>
        </div>
        <div class="btn-group pull-left">
            <button name="Search" onclick="this.form.submit()" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('COM_DZTOUR_SEARCH');?>"><span class="icon-search"></span></button>
        </div>
        <div class="btn-group pull-left">
            <button name="Clear" onclick="clearForm(this.form);" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('COM_DZTOUR_CLEAR'); ?>"><span class="icon-remove"></span></button>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="btn-toolbar">
        <div class="btn-group pull-right">
            <select name="filter_typeid">
                <option value=""><?php echo JText::_('COM_DZTOUR_OPTION_SELECT_TYPE'); ?>
                <?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_dztour.tours.typeid'), 'value', 'text', $this->state->get('filter.typeid')); ?>
            </select>
        </div>
        <div class="btn-group pull-right">
            <select name="filter_locationid">
                <option value=""><?php echo JText::_('COM_DZTOUR_OPTION_SELECT_LOCATION'); ?>
                <?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_dztour.tours.locationid'), 'value', 'text', $this->state->get('filter.locationid')); ?>
            </select>
        </div>
        <div class="btn-group pull-right">
            <select name="filter_tags[]" multiple="true">
                <?php echo JHtml::_('select.options', JHtml::_('dztour.tags'), 'value', 'text', $this->state->get('filter.tags')); ?>
            </select>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="btn-toolbar">
        <div class="btn-group pull-right hidden-phone">
            <?php echo $this->pagination->getLimitBox(); ?>
        </div>
        <div class="btn-group pull-right hidden-phone">
            <select name="filter_order_Dir" id="directionTable" class="input-medium">
                <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
                <option value="ASC" <?php if ($listDirn == 'ASC') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
                <option value="DESC" <?php if ($listDirn == 'DESC') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
            </select>
        </div>
        <div class="btn-group pull-right">
            <select name="filter_order" id="sortTable" class="input-medium">
                <option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
                <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
            </select>
        </div>
        <div class="clearfix"></div>
    </div>
</form>
<div class="items">
    <ul class="items_list">
<?php $show = false; ?>
        <?php foreach ($this->items as $item) : ?>

            
                <?php
                    if($item->state == 1 || ($item->state == 0 && JFactory::getUser()->authorise('core.edit.own',' com_dztour.tour.'.$item->id))):
                        $show = true;
                        ?>
                            <li>
                                <a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
                            </li>
                        <?php endif; ?>

<?php endforeach; ?>
        <?php
        if (!$show):
            echo JText::_('COM_DZTOUR_NO_ITEMS');
        endif;
        ?>
    </ul>
</div>
<?php if ($show): ?>
    <div class="pagination">
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>

