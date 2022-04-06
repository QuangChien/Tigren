<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\ShippingRateApi\Model;


use Tigren\ShippingRateApi\Api\Data\ShippingRateInterface;
use Magento\Framework\Model\AbstractModel;

class ShippingRate extends AbstractModel implements ShippingRateInterface
{

    protected function _construct()
    {
        $this->_init(\Magento\Catalog\Model\ResourceModel\Product::class);
    }

    /**
     * @inheritDoc
     */
    public function getSku()
    {
        return $this->_getData(ShippingRateInterface::SKU);
    }

    /**
     * @inheritDoc
     */
    public function setSku($sku)
    {
        $this->setData(ShippingRateInterface::SKU, $sku);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getQty()
    {
        return $this->_getData(ShippingRateInterface::QTY);
    }

    /**
     * @inheritDoc
     */
    public function setQty($qty)
    {
        $this->setData(ShippingRateInterface::QTY, $qty);
        return $this;
    }

}
