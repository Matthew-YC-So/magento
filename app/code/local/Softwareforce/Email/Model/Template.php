<?php
/**
 * Created : Matthew So (Software Force) 2015-01-18
 * Description : Override the existing core GetMail() function 
 *
 * @category    Softwareforce
 * @package     Softwareforce
 * @copyright  Copyright (c) 2015 
 * @license    GPL2
 */

class Softwareforce_Email_Model_Template extends Mage_Core_Model_Email_Template 
{
   // const MODULE_SETTINGS_PATH = 'smtp';

   /**
     * Send mail to recipient
     *
     * @param   array|string       $email        E-mail(s)
     * @param   array|string|null  $name         receiver name(s)
     * @param   array              $variables    template variables
     * @return  boolean
     **/
    public function send($email, $name = null, array $variables = array())
    {
                Mage::log('My log - start sending email');

                if (!$this->isValidForSend()) {
                   Mage::logException(new Exception('This letter cannot be sent.')); // translation is intentionally omitted
                   return false;
                }

                $emails = array_values((array)$email);
                $names = is_array($name) ? $name : (array)$name;
                $names = array_values($names);
                foreach ($emails as $key => $email) {
                   if (!isset($names[$key])) {
                       $names[$key] = substr($email, 0, strpos($email, '@'));
                   }
                }

                $variables['email'] = reset($emails);
                $variables['name'] = reset($names);

                $this->setUseAbsoluteLinks(true);
                $text = $this->getProcessedTemplate($variables, true);
                $subject = $this->getProcessedTemplateSubject($variables);

                $setReturnPath = Mage::getStoreConfig(self::XML_PATH_SENDING_SET_RETURN_PATH);
                switch ($setReturnPath) {
                   case 1:
                       $returnPathEmail = $this->getSenderEmail();
                       break;
                   case 2:
                       $returnPathEmail = Mage::getStoreConfig(self::XML_PATH_SENDING_RETURN_PATH_EMAIL);
                       break;
                   default:
                       $returnPathEmail = null;
                       break;
                }

                $helper = Mage::helper('smtpmail/smtpSetup');
                $helper->setSmtpDefault();      
                /*        
                ini_set('SMTP', Mage::getStoreConfig('system/smtp/host'));
                ini_set('smtp_port', Mage::getStoreConfig('system/smtp/port'));

                $host = Mage::getStoreConfig('system/smtp/host');
                $port = Mage::getStoreConfig('system/smtp/port');
                $smtpuser = Mage::getStoreConfig('system/smtp/username') ;
                $smtpuserpass =  Mage::getStoreConfig('system/smtp/password') ;
                $auth = strtolower(Mage::getStoreConfig('system/smtp/auth'));
                $ssl = strtolower(Mage::getStoreConfig('system/smtp/ssl')) ;
                */

                $mail = $this->getMail();

                if ($returnPathEmail !== null) {
                //            $mailTransport = new Zend_Mail_Transport_Sendmail("-f".$returnPathEmail);
                //            Zend_Mail::setDefaultTransport($mailTransport);

                       $mail->setReturnPath($returnPathEmail);
                       /*
                       $emailSmtpConf = array(
                               'auth' => $auth, 
                                'port' => $port ,
                               'username' => $smtpuser  , 
                               'password' => $smtpuserpass, 
                       ); 
                       if (!is_null($ssl) && $ssl != '') $emailSmtpConf['ssl'] = $ssl;

                     $mailTransport = new Zend_Mail_Transport_Smtp($host, $emailSmtpConf);        
                   */
                  }

                foreach ($emails as $key => $email) {
                   $mail->addTo($email, '=?utf-8?B?' . base64_encode($names[$key]) . '?=');
                }

                if ($this->isPlain()) {
                   $mail->setBodyText($text);
                } else {
                   $mail->setBodyHTML($text);
                }

                $mail->setSubject('=?utf-8?B?' . base64_encode($subject) . '?=');
                $mail->setFrom($this->getSenderEmail(), $this->getSenderName());

                try {
                   $mail->send($helper->getMailTransport());
                   $this->_mail = null;
                   Mage::log('My log - Email sent OK!');
                }
                catch (Exception $e) {
                   $this->_mail = null;
                   Mage::logException($e);
                   return false;
                }

                return true;
        
        }

}

?>
 
