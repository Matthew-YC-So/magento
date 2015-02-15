<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Softwareforce
 * @package     Softwareforce_CatalogMembersonly
 * @copyright  Copyright (c) 2015 Software Force
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Product list with members logged in checking 
 *
 * @category   Softwareforce
 * @package    Softwareforce_CatalogMembersonly
 * @author      Software Force Team <info@software-force.com>
 */
class Softwareforce_CatalogMembersonly_Block_Product_List extends Mage_Catalog_Block_Product_List 
{

    protected function _getProductCollection() {
		$this->_productCollection = parent::_getProductCollection();
		
		$this->_productCollection =  Mage::helper('catalogMembersonly/productsFilter')
										->filterProductCollection($this->_productCollection);
										
		return $this->_productCollection;
    }

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
/*
    public function getLoadedProductCollection()
    { 
		$collection = parent::getLoadedProductCollection();
		$collection =  Mage::helper('catalogMembersonly/productsFilter')->filterProductCollection($collection);
		return $collection;        
    }
*/

}
