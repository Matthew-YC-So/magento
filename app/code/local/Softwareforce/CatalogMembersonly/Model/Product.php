<?php
 /**
 * Membersonly Catalog product model
 *
 * @category   Softwareforce
 * @package    Softwareforce_CatalogMembersonly
 * @author     Software Force Team <info@software-force.com>
 */
class Softwareforce_CatalogMembersonly_Model_Product extends Mage_Catalog_Model_Product
{

    /**
     * Check Product visilbe in catalog
     *
     * @return bool
     */
    public function isVisibleInCatalog()
    {
        return parent::isVisibleInCatalog() 
			&& Mage::helper('catalogMembersonly/productsFilter')->is_ProductID_Allowed($this->getId());
    }
	
}
?>