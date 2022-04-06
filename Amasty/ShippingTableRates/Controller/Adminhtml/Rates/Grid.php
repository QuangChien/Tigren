<?php

namespace Amasty\ShippingTableRates\Controller\Adminhtml\Rates;

/**
 * Grid Rates of Method Action
 */
class Grid extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
