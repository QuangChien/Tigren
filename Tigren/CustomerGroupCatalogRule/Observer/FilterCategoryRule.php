<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Observer;

class FilterCategoryRule implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
//        $categories = $observer->getData('menu');
        $menu = $observer->getMenu();
        $tree = $menu->getTree();
        $children = $menu->getChildren();
        echo "<pre>";
        print_r($menu->getName());die();
//        print_r(getType($tree));die();
//        print_r($children->lastNode()->getData());die();
        return $this;
    }
}
