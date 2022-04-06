<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\ShippingRateApi\Api\Data;

interface ShippingRateInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const SKU = 'sku';
    const QTY = 'qty';

    /**
     * @return string
     */
    public function getSku();

    /**
     * @param string $sku
     * @return \Tigren\ShippingRateApi\Api\Data\ShippingRateInterface[]
     */
    public function setSku($sku);

    /**
     * @return string
     */
    public function getQty();

    /**
     * @param string $qty
     * @return \Tigren\ShippingRateApi\Api\Data\ShippingRateInterface[]
     */
    public function setQty($qty);
}
