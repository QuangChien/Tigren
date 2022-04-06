<?php

namespace Amasty\ShippingTableRates\Controller\Adminhtml;

use Magento\Backend\App\Action;

abstract class AbstractImport extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Amasty_ShippingTableRates::rates_import';
}
