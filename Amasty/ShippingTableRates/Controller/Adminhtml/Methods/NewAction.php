<?php

namespace Amasty\ShippingTableRates\Controller\Adminhtml\Methods;

/**
 * Create Shipping Method Action
 */
class NewAction extends \Amasty\ShippingTableRates\Controller\Adminhtml\Methods
{
    public function execute()
    {
        $this->_forward('edit');
    }
}
