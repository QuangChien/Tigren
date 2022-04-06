<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomCheckout\Api;

/**
 * Interface CustomManagementInterface
 * @package ViMagento\CustomApi\Api
 */
interface GetProductInterface
{
    /**
     * Get multiple order status
     * @return mixed
     */
    public function getMultipleOrder();
}
