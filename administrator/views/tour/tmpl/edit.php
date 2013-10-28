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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_dztour/assets/css/dztour.css');
?>
<script type="text/javascript">  
    Joomla.submitbutton = function(task)
    {
        if(task == 'tour.cancel'){
            Joomla.submitform(task, document.getElementById('tour-form'));
        }
        else{
            
            if (task != 'tour.cancel' && document.formvalidator.isValid(document.id('tour-form'))) {
                
                Joomla.submitform(task, document.getElementById('tour-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_dztour&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="tour-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_DZTOUR_DETAILS', true)); ?>
            <div class="row-fluid">
                <div class="span6">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <label class="hasTooltip" data-title="<?php echo JText::_('COM_DZTOUR_FORM_DESC_TOUR_DURATION'); ?>">
                                <?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_DURATION'); ?>
                            </label>
                        </div>
                        <div class="controls">
                            <div class="input-append">
                                <?php echo $this->form->getInput('days', 'duration'); ?>
                                <span class="add-on hasTooltip" data-title="<?php echo JText::_('COM_DZTOUR_FORM_DESC_TOUR_DURATION_DAYS'); ?>">
                                    <?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_DURATION_DAYS'); ?>
                                </span>
                            </div>
                            <div class="input-append">
                                <?php echo $this->form->getInput('nights', 'duration'); ?>
                                <span class="add-on hasTooltip" data-title="<?php echo JText::_('COM_DZTOUR_FORM_DESC_TOUR_DURATION_NIGHTS'); ?>">
                                    <?php echo JText::_('COM_DZTOUR_FORM_LBL_TOUR_DURATION_NIGHTS'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('typeid'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('typeid'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('locationid'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('locationid'); ?></div>
                    </div>
                </div>
                <div class="span6">
                     <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('on_offer'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('on_offer'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('price'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('price'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('saleoff_price'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('saleoff_price'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('currency', 'params'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('currency', 'params'); ?></div>
                    </div>
                </div>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'descriptions', JText::_('COM_DZTOUR_DESCRIPTIONS', true)); ?>
            <div class="row-fluid">
                <div class="span6">
                    <div class="control-group">
                        <?php echo $this->form->getLabel('short', 'descriptions'); ?>
                        <?php echo $this->form->getInput('short', 'descriptions'); ?>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <?php echo $this->form->getLabel('long', 'descriptions'); ?>
                        <?php echo $this->form->getInput('long', 'descriptions'); ?>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <div class="control-group">
                        <?php echo $this->form->getLabel('price', 'descriptions'); ?>
                        <?php echo $this->form->getInput('price', 'descriptions'); ?>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <?php echo $this->form->getLabel('other', 'descriptions'); ?>
                        <?php echo $this->form->getInput('other', 'descriptions'); ?>
                    </div>
                </div>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('COM_DZTOUR_IMAGES', true)); ?>
            <div class="row-fluid">
                <div class="span6">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('intro', 'images'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('intro', 'images'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('intro_alt', 'images'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('intro_alt', 'images'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('album', 'images'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('album', 'images'); ?></div>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('full', 'images'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('full', 'images'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('full_alt', 'images'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('full_alt', 'images'); ?></div>
                    </div>
                </div>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_DZTOUR_PUBLISHING', true)); ?>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('id'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('created'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('created'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('modified'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('modified'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('modified_by'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('modified_by'); ?></div>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'metadata', JText::_('JGLOBAL_FIELDSET_METADATA_OPTIONS', true)); ?>
                <?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php if (JFactory::getUser()->authorise('core.admin','com_dztour')): ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_DZTOUR_RULES', true)); ?>
            <div class="fltlft" style="width:86%;">
            <fieldset class="panelform">
            <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
            <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
            <?php echo $this->form->getInput('rules'); ?>
            <?php echo JHtml::_('sliders.end'); ?>
            </fieldset>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php endif; ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
        </div>

        <div class="clr"></div>
        <!-- Begin Sidebar -->
        <?php echo JLayoutHelper::render('joomla.edit.details', $this); ?>
        <!-- End Sidebar -->

        <div class="clr"></div>
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>