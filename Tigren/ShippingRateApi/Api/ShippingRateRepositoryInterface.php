<?php
namespace Tigren\ShippingRateApi\Api;

interface ShippingRateInterface
{
    /**
     * @param string $postcode
     * @param mixed $product
     * @return bool
     * @api
     */
    public function getShippingRate(string $postcode, $products) : bool;
}
