<?php
declare(strict_types=1);

namespace Amasty\ShippingTableRates\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class StatusesOptions implements OptionSourceInterface
{
    const INACTIVE = 0;
    const ACTIVE = 1;

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => self::INACTIVE,
                'label' => __('Inactive')
            ],
            [
                'value' => self::ACTIVE,
                'label' => __('Active')
            ],
        ];
    }
}
