<?php

namespace Tigren\ShippingRateApi\Api\Data;

interface ErrorGetRateInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const STATUS = 'status';
    const MESSAGE = 'message';
//    const SHIPPING_METHOD = 'shipping_method';
//    const RATE = 'info';
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

}
