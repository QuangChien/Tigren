<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\ShippingRateApi\Api;

use Tigren\ShippingRateApi\Api\Data\ShippingRateInterface;

interface ShippingRateRepositoryInterface
{
    /**
     * @param string $postcode
     * @param ShippingRateInterface[] $products
     * @return \Tigren\ShippingRateApi\Api\Data\SuccessGetRateInterface | \Tigren\ShippingRateApi\Api\Data\ErrorGetRateInterface
     * @api
     */
    public function getShippingRate(string $postcode, array $products);
}
