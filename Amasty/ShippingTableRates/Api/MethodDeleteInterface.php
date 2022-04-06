<?php
declare(strict_types=1);

namespace Amasty\ShippingTableRates\Api;

interface MethodDeleteInterface
{
    /**
     * @param \Amasty\ShippingTableRates\Api\Data\MethodInterface $method
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function execute(\Amasty\ShippingTableRates\Api\Data\MethodInterface $method): bool;
}
