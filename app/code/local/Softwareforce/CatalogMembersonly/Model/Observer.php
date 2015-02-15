<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Softwareforce_Catalogmembersonly_Model_Observer {

    /**
     * Adds additional links to the top menu
     *
     * @param Varien_Event_Observer $observer
     */
    public function changeTopMenuItems(Varien_Event_Observer $observer) {
        $nodeId = "home";
        $menu = $observer->getMenu();
        $tree = $observer->getMenu()->getTree();
        
        $data = array(
            "name" => "Home",
            " id" => $nodeId,
            " url" => Mage::getBaseUrl(),
            " is_active" => Mage::getSingleton('cms/page')->getIdentifier() == 'home' && Mage::app()->getFrontController()->getRequest()->getRouteName() == 'cms'
        );
        $homeNode = new Varien_Data_Tree_Node($data, 'id ', $tree, $menu);
        $this->_prependNode($homeNode, $menu);
 

    }

    protected function _prependNode($node, $menu) {
        $menu->addChild($node);
        $nodeId = $node->getId();
        $readded = array();
        foreach ($menu->getChildren()->getNodes() as $n) {
            if ($n->getId() != $nodeId) {
                $readded[] = $n;
                $menu->getChildren()->delete($n);
            }
        }
        foreach ($readded as $r) {
            $menu->getChildren()->add($r);
        }
    }

}
