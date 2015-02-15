<?php
class Softwareforce_CatalogMembersonly_Block_Product_Widget_New extends Mage_Catalog_Block_Product_Widget_New
    implements Mage_Widget_Block_Interface
{

    /**
     * Product collection initialize process
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection|Object|Varien_Data_Collection
     */
    protected function _getProductCollection()
    {
        $collection =  parent::_getProductCollection();
		$helper = Mage::helper('catalogMembersonly/productsFilter');
		$collection =  $helper->filterProductCollection($collection);

        return $collection;		
    }
}

?>