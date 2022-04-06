<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\ShippingRateApi\Model;

use Tigren\ShippingRateApi\Api\Data\SuccessGetRateInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class SuccessGetRate extends AbstractExtensibleModel implements SuccessGetRateInterface
{

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return $this->_getData(SuccessGetRateInterface::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus($status)
    {
        $this->setData(SuccessGetRateInterface::STATUS, $status);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMessage()
    {
        return $this->_getData(SuccessGetRateInterface::MESSAGE);
    }

    /**
     * @inheritDoc
     */
    public function setMessage($message)
    {
        $this->setData(SuccessGetRateInterface::MESSAGE, $message);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRate()
    {
        return $this->getData(self::RATE);
    }

    /**
     * @inheritdoc
     */
    public function setRate(array $rate)
    {
        return $this->setData(self::RATE, $rate);
    }

}
