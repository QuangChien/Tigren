<?php

namespace Amasty\ShippingTableRates\Block\Adminhtml;

/**
 * Shipping Methods Grid Container
 */
class Methods extends \Magento\Backend\Block\Widget\Grid\Container
{
    public function _construct()
    {
        $this->_controller = 'adminhtml_methods';
        $this->_headerText = __('Shipping Table Rates');
        $this->_blockGroup = 'Amasty_ShippingTableRates';
        parent::_construct();
    }
}
