<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\ShippingRateApi\Api\Data;

interface SuccessGetRateInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const STATUS = 'status';
    const MESSAGE = 'message';
//    const SHIPPING_METHOD = 'shipping_method';
    const RATE = 'rate';
    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getMessage();


    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message);

    /**
     * @return \Amasty\ShippingTableRates\Api\Data\ShippingTableRateInterface[]
     */
    public function getRate();

    /**
     * @param \Amasty\ShippingTableRates\Api\Data\ShippingTableRateInterface[] $rate
     * @return $this
     */
    public function setRate(array $rate);

}
