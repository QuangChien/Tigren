<?php
namespace Tigren\ShippingRateApi\Model;
use Tigren\ShippingRateApi\Api\Data\ShippingRateInterface;

class ShippingRate
{
    protected $request;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request

    )
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingRate($postcode, ShippingRateInterface $shippingRate)
    {
        return $postcode;
    }
}
