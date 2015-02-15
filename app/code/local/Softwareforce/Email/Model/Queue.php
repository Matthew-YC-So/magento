<?php

/**
 * Email Template Mailer Model
 *
 * @category    Softwareforce
 * @package     Softwareforce_Email
 * @copyright   Copyright (c) 2015 Software Force
 * @license     http://www.software-force.com/license
 */
class Softwareforce_Email_Model_Queue extends Mage_Core_Model_Email_Queue
{

    /**
     * Send all messages in a queue
     *
     * @return Mage_Core_Model_Email_Queue
     */
    public function send()
    {
        // Mage::log('My log - start sending email in queue');

     /** @var $collection Mage_Core_Model_Resource_Email_Queue_Collection */
        $collection = Mage::getModel('core/email_queue')->getCollection()
            ->addOnlyForSendingFilter()
            ->setPageSize(self::MESSAGES_LIMIT_PER_CRON_RUN)
            ->setCurPage(1)
            ->load();

        // ini_set('SMTP', Mage::getStoreConfig('system/smtp/host'));
        // ini_set('smtp_port', Mage::getStoreConfig('system/smtp/port'));
        $helper = Mage::helper('smtpmail/smtpSetup');
        $helper->setSmtpDefault();        
        
        $num_mail = 0;
            
        /** @var $message Mage_Core_Model_Email_Queue */
        foreach ($collection as $message) {
            if ($message->getId()) {
                $parameters = new Varien_Object($message->getMessageParameters());
                /*
                if ($parameters->getReturnPathEmail() !== null) {
                    $mailTransport = new Zend_Mail_Transport_Sendmail("-f" . $parameters->getReturnPathEmail());
                    Zend_Mail::setDefaultTransport($mailTransport);
                }
                 */
                $mailer = new Zend_Mail('utf-8');
                
                foreach ($message->getRecipients() as $recipient) {
                    list($email, $name, $type) = $recipient;
                    switch ($type) {
                        case self::EMAIL_TYPE_BCC:
                            $mailer->addBcc($email, '=?utf-8?B?' . base64_encode($name) . '?=');
                            break;
                        case self::EMAIL_TYPE_TO:
                        case self::EMAIL_TYPE_CC:
                        default:
                            $mailer->addTo($email, '=?utf-8?B?' . base64_encode($name) . '?=');
                            break;
                    }
                }

                if ($parameters->getIsPlain()) {
                    $mailer->setBodyText($message->getMessageBody());
                } else {
                    $mailer->setBodyHTML($message->getMessageBody());
                }

                $mailer->setSubject('=?utf-8?B?' . base64_encode($parameters->getSubject()) . '?=');
                $mailer->setFrom($parameters->getFromEmail(), $parameters->getFromName());
                if ($parameters->getReplyTo() !== null) {
                    $mailer->setReplyTo($parameters->getReplyTo());
                }
                if ($parameters->getReturnTo() !== null) {
                    $mailer->setReturnPath($parameters->getReturnTo());
                }

                try {
                    //$mailer->send();
                    $mailer->send( $helper->getMailTransport() );
                    unset($mailer);
                    $message->setProcessedAt(Varien_Date::formatDate(true));
                    $message->save();
                    ++$num_mail ;
                }
                catch (Exception $e) {
                    unset($mailer);
                    $oldDevMode = Mage::getIsDeveloperMode();
                    Mage::setIsDeveloperMode(true);
                    Mage::logException($e);
                    Mage::setIsDeveloperMode($oldDevMode);

                    return false;
                }
            }
        }        
        if ($num_mail > 0 )
             Mage::log('My log - ' . $num_mail . ' email(s) in queue sent!');
        else
             Mage::log('My log - no email in queue!');
         
         return $this;
    }

}
?>
