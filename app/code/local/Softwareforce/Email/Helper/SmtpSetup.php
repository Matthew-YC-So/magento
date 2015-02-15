<?php
class Softwareforce_Email_Helper_SmtpSetup extends Mage_Core_Helper_Abstract {

    protected $_mailTransport;

    const CONFIG_SMTP_HOST = 'system/smtp/host';
    const CONFIG_SMTP_PORT = 'system/smtp/port';
    const CONFIG_SMTP_USERNAME = 'system/smtp/username';
    const CONFIG_SMTP_PASSWORD = 'system/smtp/password';
    const CONFIG_SMTP_AUTH = 'system/smtp/auth';
    const CONFIG_SMTP_SSL = 'system/smtp/ssl';

    /**
     * Configuration path for default email templates
     */
    const XML_PATH_TEMPLATE_EMAIL = 'global/template/email';
    const XML_PATH_SENDING_SET_RETURN_PATH = 'system/smtp/set_return_path';
    const XML_PATH_SENDING_RETURN_PATH_EMAIL = 'system/smtp/return_path_email';

    public function setSmtpDefault() {
        $host = Mage::getStoreConfig(self::CONFIG_SMTP_HOST);
        $port = Mage::getStoreConfig(self::CONFIG_SMTP_PORT);
        $smtpuser = Mage::getStoreConfig(self::CONFIG_SMTP_USERNAME);
        $smtpuserpass = Mage::getStoreConfig(self::CONFIG_SMTP_PASSWORD);
        $auth = strtolower(Mage::getStoreConfig(self::CONFIG_SMTP_AUTH));
        $ssl = strtolower(Mage::getStoreConfig(self::CONFIG_SMTP_SSL));

        // Set correct SMTP host and port for script session - just for compatability
        ini_set('SMTP', $host);
        ini_set('smtp_port', $port);

        $emailSmtpConf = array(
            'auth' => $auth,
            'port' => $port,
            'username' => $smtpuser,
            'password' => $smtpuserpass,
        );

        if (!is_null($ssl) && $ssl != '')
            $emailSmtpConf['ssl'] = $ssl;

        $this->_mailTransport = new Zend_Mail_Transport_Smtp($host, $emailSmtpConf);
        // Set default SMTP transportation settings 
        Zend_Mail::setDefaultTransport($this->_mailTransport);
        return $this;
    }

    public function getMailTransport() {
        return $this->_mailTransport;
    }

    public function getReturnPathMail() {
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
        return $returnPathEmail;
    }

}

?>
