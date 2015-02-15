<?php
class Softwareforce_CatalogMembersonly_Helper_ProductsFilter extends Mage_Core_Helper_Abstract
{
     public function filterProductCollection($collection)
     {
        //Mage::log('My log - Softwareforce_CatalogMembersonly_Helper_ProductsFilter -> filterProductCollection()');

		if ($this->is_requiredFiltering())
        {
			$memberonly_product_ids = $this -> getMemberonlyProductIDs();
			if (count($memberonly_product_ids) > 0) {
				$collection->addAttributeToFilter('entity_id', array( 'nin' => $memberonly_product_ids  )) ;
			}
        }
        return $collection;
    }
	
	public function is_ProductID_Allowed($productID){
		$allowed = true ;

		var_dump($productID);

		if ($this->is_requiredFiltering()){
			$memberonly_product_ids = $this -> getMemberonlyProductIDs() ;
			$allowed = count($memberonly_product_ids) == 0 || !in_array($productID, $memberonly_product_ids) ;
		}
		var_dump($allowed);
		return $allowed;
	}
	
	protected function is_requiredFiltering(){
        $is_logged_on = Mage::getSingleton('customer/session')->isLoggedIn();

        $roleId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        $role = Mage::getSingleton('customer/group')->load($roleId)->getData('customer_group_code');
        $role = strtolower($role);
        
		$config_membersonly_categories = Mage::getStoreConfig('catalog/membersonly/categories');

		return (!$is_logged_on || $role == 'general') && isset( $config_membersonly_categories ) && $config_membersonly_categories != '';
	}
	
	protected function getMemberonlyProductIDs(){
		
		$memberonly_product_ids = array();
		
		$config_membersonly_categories = explode(',',Mage::getStoreConfig('catalog/membersonly/categories')) ;
		
		if ( count($config_membersonly_categories)  > 0 ) {
			foreach ($config_membersonly_categories as $category_id)
			{
				if ($category_id != '') {

					$category = Mage::getModel('catalog/category')->load($category_id);
					$products = Mage::getResourceModel('catalog/product_collection')
							->setStoreId(Mage::app()->getStore()->getId())
							->addCategoryFilter($category);
					
					// Merge into existing product ids 
					$memberonly_product_ids = array_merge($memberonly_product_ids, $products->getColumnValues('entity_id'));
				}
			}
		}
		
        return array_unique($memberonly_product_ids) ;
	}

}
?>