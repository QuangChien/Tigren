<?php

namespace Amasty\ShippingTableRates\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Shipping method status options provider
 */
class Statuses implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            '0' => __('Inactive'),
            '1' => __('Active'),
        ];
    }
}
