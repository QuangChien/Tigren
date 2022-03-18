<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Plugin\Catalog;

use Magento\Catalog\Model\Product;

class HideButton
{
    protected $ruleHelper;

    public function __construct
    (
        \Tigren\CustomerGroupCatalogRule\Helper\GetRuleCatalog $getRuleCatalog
    )
    {
        $this->ruleHelper = $getRuleCatalog;
    }


    public function afterIsSaleable(\Magento\Catalog\Model\Product $subject, $result)
    {
//        if($this->ruleHelper->isHideProduct() == false){
//            foreach ($subject as $item){
//                if (in_array($item->getSku(), $this->ruleHelper->getEntityInRule('product_select'))) {
//                    return false;
//                } else {
//                    return true;
//                }
//            }
//        }
        return true;
    }
}
