<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Plugin\Catalog;

class FinalPriceBox
{
    protected $ruleHelper;

    public function __construct
    (
        \Tigren\CustomerGroupCatalogRule\Helper\GetRuleCatalog $getRuleCatalog
    )
    {
        $this->ruleHelper = $getRuleCatalog;
    }

    function aroundToHtml(\Magento\Catalog\Pricing\Render\FinalPriceBox $subject, callable $proceed)
    {
        return true;
//        echo "<pre>";
//        var_dump(get_class_methods($subject)); die();
//        if($subject->getIdentities()){
//            return '';
//        }else{
//            return $proceed();
//        }
    }
}
