<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Observer;


class FilterCategoryRule implements \Magento\Framework\Event\ObserverInterface
{
    protected $ruleHelper;

    public function __construct
    (
        \Tigren\CustomerGroupCatalogRule\Helper\GetRuleCatalog $getRuleCatalog
    )
    {
        $this->ruleHelper = $getRuleCatalog;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
//        echo "<pre>";
//        print_r($this->ruleHelper->getRule()->getData());die();
        $collection = $observer->getData('category_collection');
        if ($this->ruleHelper->checkRuleInStore()) {
            $collection->addFieldToFilter('entity_id', array('nin' => $this->ruleHelper->getEntityInRule('category_hide')));
        }
    }
}
