<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\ShippingRateApi\Model;

use Tigren\ShippingRateApi\Api\Data\ErrorGetRateInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class ErrorGetRate extends AbstractExtensibleModel implements ErrorGetRateInterface
{

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return $this->_getData(ErrorGetRateInterface::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus($status)
    {
        $this->setData(ErrorGetRateInterface::STATUS, $status);
        return $this;
    }

        /**
     * @inheritDoc
     */
    public function getMessage()
    {
        return $this->_getData(ErrorGetRateInterface::MESSAGE);
    }

    /**
     * @inheritDoc
     */
    public function setMessage($message)
    {
        $this->setData(ErrorGetRateInterface::MESSAGE, $message);
        return $this;
    }

}
