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

require_once JPATH_COMPONENT.'/controller.php';
require_once JPATH_COMPONENT.'/helpers/dztour.php';
/**
 * Tour controller class.
 */
class DztourControllerTour extends DztourController
{
    public function order()
    {
        /* ----- PREVENT CROSS DOMAIN REQUEST AND BOT ----- */
        JSession::checkToken('request') or function() {throw new RuntimeException(JText::_('JINVALID_TOKEN'));};
        $response = recaptcha_check_answer(
            $this->privatekey, 
            $_SERVER["REMOTE_ADDR"], 
            $this->input->post->get('recaptcha_challenge_field', '', 'string'),
            $this->input->post->get('recaptcha_response_field', '', 'string')
        );
        if (!$response->is_valid) {
            throw new RuntimeException(JText::_('COM_DZTOUR_ERROR_INVALID_CAPTCHA'));
        }
        
        /* ---- CREATE NEW ORDER --- */
        // Load the back end language
        JFactory::getLanguage()->load('com_dztour', JPATH_ADMINISTRATOR);
        
        // Prepare model and data
        $model = JModelLegacy::getInstance('Order', 'DZTourModel');
        $data = $this->input->post->get('order', array(), 'array');
        
        $user = JFactory::getUser();
        if (!$user->authorise('core.create', 'com_dztour')) {
            throw new RuntimeException(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
        }
        
        $form = $model->getForm($data, false);
        if (!$form) {
            throw new RuntimeException($model->getError());
        };
        
        $validData = $model->validate($form, $data);
        if ($validData === false) {
            // Get the validation messages.
            $errors = $model->getErrors();
            $messages = array();
            
            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
            {
                if ($errors[$i] instanceof Exception)
                {
                    $messages[] = $errors[$i]->getMessage();
                }
                else
                {
                    $messages[] = $errors[$i];
                }
            }
            
            throw new RuntimeException(join(',', $messages));
        }
        
        // Attempt to save data
        if (!$model->save($validData)) {
            throw new RuntimeException(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', join(',', $model->getErrors())));
        }
        
        /* ----- SEND EMAILS ---- */
        // Get mailer and set up some defaults value
        $app = JFactory::getApplication();
        $params = $app->getParams('com_dztour');
        $mailer = JFactory::getMailer();
        $mailer->setSender(array($app->getCfg('mailfrom'), $app->getCfg('fromname')));
        $mailer->isHtml(true);
        
        // Get notification list 
        $list = explode(',', $params->get('receivers'));
        if (!empty($list)) {
            // Setup a mailer
            $mailer->addRecipient($list);
            
            // Mail subject
            $mailer->setSubject($params->get('subject', 'A new order has just been placed!'));
            
            // Mail body
            $body = $params->get('body', 
                '<h1>%subject$s</h1>
                <br />Order information:
                <ul>
                    <li>Tour: %tour$s</li>
                    <li>Adults: %adults$s</li>
                    <li>Children: %children$s</li>
                    <li>Start Date: %start_date$s</li>
                    <li>End Date: %end_date$s</li>
                    <li>Comment: %comment$s</li>
                </ul>
                <br />Contact information:
                <ul>
                    <li>Name: %name$s</li>
                    <li>Email: %email$s</li>
                    <li>Phone: %phone$s</li>
                    <li>Address: %address$s</li>
                </ul>');
            $validData['tour'] = DZTourHelper::getTourName($validData['tourid']);
            $validData['subject'] = $params->get('subject', 'A new order has just been placed!');
            $body = DZTourHelper::sprintf($body, $validData);
            $mailer->setBody($body);
            
            // Send mail
            // TODO No errors should be displayed to customers
            $response = $mailer->send();
//             if ($response instanceof Exception) {
//                 throw $response;
//             } elseif (empty($response)) {
//                 throw new Exception(JText::_('COM_DZTOUR_ERROR_NO_MAIL_SENT'));
//             }
        }
        
        // Automatically send confirmation email to user
        if (!empty($validData['email'])) {
            // Setup mailer
            $mailer->addRecipient($validData['email']);
            
            // Mail subject
            $mailer->setSubject($params->get('confirmsubject', 'You have ordered a tour from our service!'));
            
            // Mail body
            $body = $params->get('confirmbody', 
                '<h1>%subject$s</h1>
                <br />Order information:
                <ul>
                    <li>Tour: %tour$s</li>
                    <li>Adults: %adults$s</li>
                    <li>Children: %children$s</li>
                    <li>Start Date: %start_date$s</li>
                    <li>End Date: %end_date$s</li>
                    <li>Comment: %comment$s</li>
                </ul>
                <br />Contact information:
                <ul>
                    <li>Name: %name$s</li>
                    <li>Email: %email$s</li>
                    <li>Phone: %phone$s</li>
                    <li>Address: %address$s</li>
                </ul>');
            $validData['tour'] = DZTourHelper::getTourName($validData['tourid']);
            $validData['subject'] = $params->get('confirmsubject', 'You have ordered a tour from our service!');
            $body = DZTourHelper::sprintf($body, $validData);
            $mailer->setBody($body);
            
            // Send mail
            $response = $mailer->send();
            // TODO No errors should be displayed to customers
//             if ($response instanceof Exception) {
//                 throw $response;
//             } elseif (empty($response)) {
//                 throw new Exception(JText::_('COM_DZTOUR_ERROR_NO_MAIL_SENT'));
//             }
        }
        // Exit with success
        jexit($this->encodeMessage(JText::_('COM_DZTOUR_ORDER_SUCCESSFULLY')));
    }
}