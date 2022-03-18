<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Observer;

class FilterProductRule implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Tigren\CustomerGroupCatalogRule\Helper\GetRuleCatalog
     */
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
//        var_dump($this->ruleHelper->isEnableModule());die();
        $collection = $observer->getData('collection');
        $hideProductStatus = $this->ruleHelper->isHide('hide_product_status');
        $checkRuleInStore = $this->ruleHelper->checkRuleInStore();
        if($hideProductStatus && $checkRuleInStore){
            $collection->addAttributeToFilter('sku', array('nin' => $this->ruleHelper->getEntityInRule('product_select')));
        }
//                echo "<pre>";
//        print_r($this->getRule()->getData());die();
//        $this->getProductInRule();
//        echo "<pre>";
//        var_dump($this->getProductInRule()); die();

//        var_dump($this->getRules()->getData()); die();
//        echo "<pre>";
//        print_r($collection->getData());die();


//        echo "<pre>";
//        print_r($collection->getData());
//        die();
////        $categories = $observer->getData('menu');
////        die();
//        $writer = new \Laminas\Log\Writer\Stream(BP . '/var/log/custom.log');
//        $logger = new \Laminas\Log\Logger();
//        $logger->addWriter($writer);
//        $logger->info('Hello');
    }
}
