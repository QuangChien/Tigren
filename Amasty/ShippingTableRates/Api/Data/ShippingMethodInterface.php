<?php

namespace Amasty\ShippingTableRates\Api\Data;

/**
 * Interface ShippingMethodInterface
 * @api
 */
interface ShippingMethodInterface extends \Magento\Quote\Api\Data\ShippingMethodInterface
{
    /**
     * Sets the shipping carrier comment.
     *
     * @param string $comment
     * @return \Amasty\ShippingTableRates\Api\Data\ShippingMethodInterface
     */
    public function setComment($comment);

    /**
     * Sets the shipping carrier comment.
     *
     * @return \Amasty\ShippingTableRates\Api\Data\ShippingMethodInterface
     */
    public function getComment();
}
