<?php
/**
 * @version     1.0.0
 * @package     com_dztour
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package     Joomla.Libraries
 * @subpackage  Form
 * @since       3.1
 */
class JFormFieldCustomList extends JFormFieldList
{
    /**
     * @var    string
     * @since  3.1
     */
    public $type = 'CustomList';

    /**
     * Method to get the field input for a custom list field.
     *
     * @return  string  The field input.
     *
     * @since   3.1
     */
    protected function getInput()
    {
        // Get the field id
        $id    = isset($this->element['id']) ? $this->element['id'] : null;
        $selector = '#' . $this->getId($id, $this->element['name']);

        // Load the ajax-chosen customised field        
        JHtml::_('formbehavior.ajaxchosen', new JRegistry(array('selector' => $selector)));
        
        JFactory::getDocument()->addScriptDeclaration("
            (function($){
                $(document).ready(function () {

                    var customTagPrefix = '';

                    // Method to add tags pressing enter
                    $('" . $selector . "_chzn input').keydown(function(event) {

                        // Tag is greater than 3 chars and enter pressed
                        if (this.value.length >= 3 && (event.which === 13 || event.which === 188)) {

                            // Search an highlighted result
                            var highlighted = $('" . $selector . "_chzn').find('li.active-result.highlighted').first();

                            // Add the highlighted option
                            if (event.which === 13 && highlighted.text() !== '')
                            {
                                // Extra check. If we have added a custom tag with this text remove it
                                var customOptionValue = customTagPrefix + highlighted.text();
                                $('" . $selector . " option').filter(function () { return $(this).val() == customOptionValue; }).remove();

                                // Select the highlighted result
                                var tagOption = $('" . $selector . " option').filter(function () { return $(this).html() == highlighted.text(); });
                                tagOption.attr('selected', 'selected');
                            }
                            // Add the custom tag option
                            else
                            {
                                var customTag = this.value;

                                // Extra check. Search if the custom tag already exists (typed faster than AJAX ready)
                                var tagOption = $('" . $selector . " option').filter(function () { return $(this).html() == customTag; });
                                if (tagOption.text() !== '')
                                {
                                    tagOption.attr('selected', 'selected');
                                }
                                else
                                {
                                    var option = $('<option>');
                                    option.text(this.value).val(customTagPrefix + this.value);
                                    option.attr('selected','selected');

                                    // Append the option an repopulate the chosen field
                                    $('" . $selector . "').append(option);
                                }
                            }

                            this.value = '';
                            $('" . $selector . "').trigger('liszt:updated');
                            event.preventDefault();

                        }
                    });
                });
            })(jQuery);
            "
        );
        

        if (!is_array($this->value) && !empty($this->value))
        {
            // String in format 2,5,4
            if (is_string($this->value))
            {
                $this->value = explode(',', $this->value);
            }
        }

        $input = parent::getInput();

        return $input;
    }

    /**
     * Method to get a list of currency
     *
     * @return  array  The field option objects.
     *
     * @since   3.1
     */
    protected function getOptions()
    {
        $options = array();
        
        foreach($this->value as $key => $value) {
            $options[$key] = new stdClass;
            $options[$key]->value = $value;
            $options[$key]->text = $value;
        }

        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}
