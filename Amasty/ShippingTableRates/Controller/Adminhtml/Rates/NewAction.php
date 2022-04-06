<?php
declare(strict_types=1);

namespace Amasty\ShippingTableRates\Controller\Adminhtml\Rates;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Create Rate Action
 */
class NewAction extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Amasty_ShippingTableRates::amstrates';

    public function execute()
    {
        $this->_forward('edit');
    }
}
