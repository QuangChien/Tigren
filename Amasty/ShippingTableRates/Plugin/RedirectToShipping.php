<?php

namespace Amasty\ShippingTableRates\Plugin;

use Magento\Config\Block\System\Config\Tabs;
use Magento\Config\Model\Config\Structure\Element\Section;

/**
 * Replace URL of Amasty Shipping Table Rate Configuration tab
 */
class RedirectToShipping
{
    public function aroundGetSectionUrl(
        Tabs $subject,
        callable $proceed,
        Section $section
    ) {
        $url = $proceed($section);
        if ($section->getId() === 'amstrates_amasty_tab') {
            $url = $subject->getUrl('*/*/*', ['_current' => true, 'section' => 'carriers']);
        }

        return $url;
    }
}
