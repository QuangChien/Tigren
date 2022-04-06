<?php

declare(strict_types=1);

namespace Amasty\ShippingTableRates\Api;

interface MethodSaveInterface
{
    /**
     * @param \Amasty\ShippingTableRates\Api\Data\MethodInterface $method
     * @return \Amasty\ShippingTableRates\Api\Data\MethodInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(
        \Amasty\ShippingTableRates\Api\Data\MethodInterface $method
    ): \Amasty\ShippingTableRates\Api\Data\MethodInterface;
}
