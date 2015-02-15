<?php

class Softwareforce_MemberApplication_IndexController extends Mage_Core_Controller_Front_Action {        
    
    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function applyAction() {
          //Fetch submited params
        $params = $this->getRequest()->getParams();
        
        $helper = Mage::helper('Softwareforce_MemberApplication');
        
        $helper->setFullName($params["fullname"]);
        $helper->setNickName($params["nickname"]);
        $helper->setEmail($params["email"]);
        $helper->setTel($params["tel"]);
        $helper->setGender($params["gender"]);
        $helper->setBirthdate($params["birthdate"]);
        $helper->setBirthtime($params["birthtime"]);
        $helper->setComment($params["comment"]);
        $helper->setInvoiceNo1($params["invoiceno1"]);
        $helper->setInvoiceNo2($params["invoiceno2"]);
        $helper->setBirthtime($params["birthtime"]);
        $helper->setBirthdaygift($params["birthday_gift"]);
        
        
        if (!empty($params["item1_services"])){
            $ServicesChecked = [];
            foreach($params["item1_services"] as $itemservice) 
                $ServicesChecked[] = $itemservice;
            $helper->item1ServicesChecked = $ServicesChecked ;
        }
        
        if (!empty($params["item2_services"])){
            $ServicesChecked = [];
            foreach($params["item2_services"] as $itemservice) 
                $ServicesChecked[] = $itemservice;
            $helper->item2ServicesChecked = $ServicesChecked ;
        }
         
        if (!empty($params["interestedin"])){
            $itemschecked = [];
            foreach($params["interestedin"] as $itemck) 
                $itemschecked[] = $itemck;
            $helper->interestedInChecked = $itemschecked;
        }

        $check_fields = ["fullname", "email", "gender"];
        $is_ok = true ;
        foreach($check_fields as $check_field) {
            if (empty($params[$check_field])){
               $is_ok = FALSE; 
            }
        }
        if ($is_ok) {

            try {
                $message = array(
                    "subject" => $helper->__("會員申請") ,
                    "fromEmail" => $params["email"],
                    "fromName" => $params["fullname"],
                    "body" => empty( $params["comment"] ) ? "" : $params["comment"] 
                );
                $helper->send($message);
                $helper->ResetData(); // Clear posted data 
                Mage::getSingleton('core/session')->addSuccess($helper->__("謝謝! 我們將會處理你的會員申請。"));
            } catch (Exception $ex) {
                Mage::getSingleton('core/session')->addError('Unable to send email.' . nl2br($ex . message));
            }
        }
        else {
            Mage::getSingleton('core/session')->addError($helper->__("資料不足! 請補填。"));
        }

        $this->_forward('index');
    }
    


}
                
