<?php
declare(strict_types=1);

namespace Amasty\ShippingTableRates\Model\Source\Option;

use Amasty\ShippingTableRates\Helper\Data;
use Magento\Framework\Data\OptionSourceInterface;

class CountryOptions implements OptionSourceInterface
{
    const ALL_COUNTRIES = 0;

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
            ['value' => self::ALL_COUNTRIES, 'label' => __('All')]
        ];

        return array_merge($options, $this->helper->getCountries());
    }
}
