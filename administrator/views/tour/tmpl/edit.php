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
    js = jQuery.noConflict();
    js(document).ready(function(){
        
    });
    
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
            <fieldset class="adminform">

                            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('id'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('state'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('access'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('access'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('language'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('language'); ?></div>
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
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('featured'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('featured'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('on_offer'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('on_offer'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('price'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('price'); ?></div>
            </div>
                <input type="hidden" name="jform[saleoff_price]" value="<?php echo $this->item->saleoff_price; ?>" />
                <input type="hidden" name="jform[duration]" value="<?php echo $this->item->duration; ?>" />
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('typeid'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('typeid'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('locationid'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('locationid'); ?></div>
            </div>
                <input type="hidden" name="jform[descriptions]" value="<?php echo $this->item->descriptions; ?>" />
                <input type="hidden" name="jform[images]" value="<?php echo $this->item->images; ?>" />
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('metadesc'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('metadesc'); ?></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('metakey'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('metakey'); ?></div>
            </div>
                <input type="hidden" name="jform[metadata]" value="<?php echo $this->item->metadata; ?>" />
                <input type="hidden" name="jform[params]" value="<?php echo $this->item->params; ?>" />


            </fieldset>
        </div>

        <div class="clr"></div>

<?php if (JFactory::getUser()->authorise('core.admin','dztour')): ?>
    <div class="fltlft" style="width:86%;">
        <fieldset class="panelform">
            <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
            <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
            <?php echo $this->form->getInput('rules'); ?>
            <?php echo JHtml::_('sliders.end'); ?>
        </fieldset>
    </div>
<?php endif; ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>