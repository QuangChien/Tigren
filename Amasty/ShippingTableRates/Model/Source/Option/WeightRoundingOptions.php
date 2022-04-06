<?php
declare(strict_types=1);

namespace Amasty\ShippingTableRates\Model\Source\Option;

use Magento\Framework\Data\OptionSourceInterface;

class WeightRoundingOptions implements OptionSourceInterface
{
    const NONE = 0;
    const UP = 1;
    const DOWN = 2;

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => self::NONE, 'label' => 'None'],
            ['value' => self::UP, 'label' => 'Up'],
            ['value' => self::DOWN, 'label' => 'Down']
        ];
    }
}
