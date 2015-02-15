<?php
class Softwareforce_MemberApplication_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getVersion()
    {
        return "1.0";
    }
    
    private $_fullName = "";
    private $_nickName = "";
    private $_email = "";
    private $_tel = "";
    private $_gender = "";
    private $_comment = "";
    private $_birthdate = "";
    private $_birthtime = "";
    private $_invoiceno1 = "";
    private $_invoiceno2 = "";
    private $_birthdaygift = "";
    
    public $gifts = array(
        "1" => "儂依佛牙膏",
        "2" => "十全十美風味咖啡",
        "3" => "豆漿－草本口味",
        "4" => "杏仁茶",
        "5" => "拒吻",
        "6" => "開運沐浴乳*（依五行，分「木」、「火」、「土」、「金」、「水」）",
        "7" => "開運香水包(5獨立小包)* （依五行，分「木」、「火」、「土」、「金」、「水」）");

    public $interests = array (
            "1" => "八字命理",
            "2" => "開運方法",
            "3" => "養生保健",
            "4" => "美容護膚",
            "5" => "兩性關係" );
    
    public $item1services = ["1" => "八字命盤基本分析 (HK$150)",  "2" => "兩性關係私人諮詢 (HK$300)"  ];
    public $item2services = ["1" => "八字命盤基本分析 (免費)",  "2" => "兩性關係私人諮詢 (HK$300)"  ];
    
    public $item1ServicesChecked = [];
    public $item2ServicesChecked = [];
    public $interestedInChecked = [];
    
    public function ResetData() {
        $this->_fullName = "";
        $this->_nickName = "";
        $this->_email = "";
        $this->_tel = "";
        $this->_gender = "";
        $this->_comment = "";
        $this->_birthdate = "";
        $this->_birthtime = "";
        $this->_invoiceno1 = "";
        $this->_invoiceno2 = "";
        $this->_birthdaygift = "";
        $this->item1ServicesChecked = [];
        $this->item2ServicesChecked = [];
        $this->interestedInChecked = [];
    }

    public function getFullName(){
        return $this->_fullName;
    }
    public function setFullName($val){
        $this->_fullName = $val;
    }
    
    public function getNickName(){
        return $this->_nickName ;
    }
    public function setNickName($val){
        $this->_nickName = $val;
    }
    
    public function getEmail(){
        return $this->_email ;
    }
    public function setEmail($val){
        $this->_email = $val;
    }
    
    public function getTel(){
        return $this->_tel ;
    }
    public function setTel($val){
        $this->_tel = $val;
    }
    
    public function getGender(){
        return $this->_gender;
    }
    public function setGender($val){
         $this->_gender = $val;
    }
    
    public function getBirthdate(){
        return $this->_birthdate;
    }
    public function setBirthdate($val){
         $this->_birthdate = $val;
    }    
    
    public function getBirthtime(){
        return $this->_birthtime;
    }
    public function setBirthtime($val){
         $this->_birthtime = $val;
    }     
    
    public function getComment(){
        return $this->_comment;
    }
    public function setComment($val){
         $this->_comment = $val;
    }

    public function getInvoiceNo1(){
        return $this->_invoiceno1 ;
    }
    public function setInvoiceNo1($val){
        $this->_invoiceno1 = $val;
    }
    public function getInvoiceNo2(){
        return $this->_invoiceno2 ;
    }
    public function setInvoiceNo2($val){
        $this->_invoiceno2 = $val;
    }
    
    public function getBirthdaygift(){
        return $this->_birthdaygift;
    }
    public function setBirthdaygift($val){
         $this->_birthdaygift = $val;
    }      
    
    /**
     * Send all email
     *
     */
    public function send($message) {
        // Mage::log('My log - start sending email');

        $helper = Mage::helper('smtpmail/smtpSetup');
        $helper->setSmtpDefault();

        $mailer = new Zend_Mail('utf-8');

        $smtpuser = Mage::getStoreConfig($helper::CONFIG_SMTP_USERNAME);
        
        $admin_email = Mage::getStoreConfig('contacts/email/recipient_email');
        $mailer->addTo($admin_email,"General contact");
        $mailer->setBodyHTML($this->getBodyHtml());
        $mailer->setSubject('=?utf-8?B?' . base64_encode($message["subject"]) . '?=');
        $mailer->setFrom($smtpuser, "Member");
        
        $returnPathMail = $helper->getReturnPathMail();
        if (!empty($returnPathMail))
            $mailer->setReturnPath($returnPathMail);

        try {
            $mailer->send($helper->getMailTransport());
            unset($mailer);

        } catch (Exception $e) {
            unset($mailer);
            $oldDevMode = Mage::getIsDeveloperMode();
            Mage::setIsDeveloperMode(true);
            Mage::logException($e);
            Mage::setIsDeveloperMode($oldDevMode);
            throw $e;
            // return false;
        }
        
        Mage::log('My log - email sent!');
    }

    public function getBodyHtml(){
        $gender = $this->_gender == "M" ? "男" : "女";
        
        $services1 = "";
        foreach($this->item1ServicesChecked as $checked) {
            $services1 = $services1.'<br />'.$this->item1services[$checked];
        }
        
        $services2 = "";
        foreach($this->item2ServicesChecked as $checked) {
            $services2 = $services2.'<br />'.$this->item2services[$checked];
        }        
        
        $interestedin = "" ;
        foreach($this->interestedInChecked as $checked) {
            $interestedin  = $interestedin .'<br />'.$this->interests[$checked];
        }
        
        $bd_gift = empty($this->_birthdaygift) ? "" : $this->gifts[$this->_birthdaygift];
        $today = date("Y/m/d") ;
        
        $body = <<< EOT
                <style>
                    td {
                        border:solid 1px #333;
                    }
                    td.header {
                        font-weight : bold;
                        background-colo : #999;
                    }
                    table {
                        border-collapse: collapse;
                    }
                </style>
                <table>
                    <tr><td class="header">姓名:</td><td>$this->_fullName</td></tr>
                    <tr><td class="header">昵稱:</td><td>$this->_nickName</td></tr>
                    <tr><td class="header">性別:</td><td>$gender</td></tr>
                    <tr><td class="header">出生日期:</td><td>$this->_birthdate</td></tr>
                    <tr><td class="header">出生時間:</td><td>$this->_birthtime</td></tr>
                    <tr><td class="header">電郵:</td><td>$this->_email</td></tr>
                    <tr><td class="header">電話:</td><td>$this->_tel</td></tr>
                    <tr><td class="header" rowspan="2">滿HK$500要求及發票:</td><td>$this->_invoiceno1</td></tr>
                    <tr><td>$services1</td></tr>
                    <tr><td class="header" rowspan="2">滿HK$2000要求及發票:</td><td>$this->_invoiceno2</td></tr>
                    <tr><td>$services2</td></tr>
                    <tr><td class="header">生日禮物:</td><td>$bd_gift</td></tr>
                    <tr><td class="header">較感興趣</td><td>$interestedin</td></tr>
                    <tr><td class="header">意見:</td><td>$this->_comment</td></tr>
                    <tr><td class="header">申請日期:</td><td>$today</td></tr>
                </table>
EOT;
             return $body;   
    }    
    
}
?>