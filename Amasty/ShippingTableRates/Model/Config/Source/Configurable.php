<?php

namespace Amasty\ShippingTableRates\Model\Config\Source;

/**
 * Shipping Behavior options for Configurable Products
 */
class Configurable implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        $vals = [
            '0' => __('From associated products'),
            '1' => __('From parent product'),
        ];

        $options = [];
        foreach ($vals as $k => $v) {
            $options[] = [
                'value' => $k,
                'label' => $v
            ];
        }

        return $options;
    }
}
