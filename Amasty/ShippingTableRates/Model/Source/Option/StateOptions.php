<?php
declare(strict_types=1);

namespace Amasty\ShippingTableRates\Model\Source\Option;

use Amasty\ShippingTableRates\Helper\Data;
use Magento\Framework\Data\OptionSourceInterface;

class StateOptions implements OptionSourceInterface
{
    const ALL_STATES = 0;

    /**
     * @var Data
     */
    private $helper;

    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = [
            ['value' => self::ALL_STATES, 'label' => __('All')]
        ];

        $states = $this->helper->getStates();
        array_shift($states);
        asort($states);

        return array_merge($options, $states);
    }
}
