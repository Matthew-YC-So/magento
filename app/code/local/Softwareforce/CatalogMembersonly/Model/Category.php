<?php
class Softwareforce_CatalogMembersonly_Model_Category extends Mage_Catalog_Model_Category
{
     public function getProductCollection()
     {
        $collection =  parent::getProductCollection();
		$helper = Mage::helper('catalogMembersonly/productsFilter');
		$collection =  $helper->filterProductCollection($collection);

        return $collection;
    }
}
?>
