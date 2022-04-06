<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomCheckout\Controller\Quote;

use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Cart as CustomerCart;

class DeleteQuoteItem extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    /**
     * @param Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param CustomerCart $cart
     */
    public function __construct(
        Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        CustomerCart $cart
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->cart = $cart;

        parent::__construct($context);
    }

    public function execute()
    {
        try{
//            $allItems = $this->checkoutSession->getQuote()->getAllVisibleItems();
//            foreach ($allItems as $item) {
//                $itemId = $item->getItemId();
//                $this->cart->removeItem($itemId)->save();
//            }
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $cartObject = $objectManager->create('Magento\Checkout\Model\Cart')->truncate();
            $cartObject->saveQuote();
            $response = [
                'success' => true,
            ];

        } catch (\Exception $e) {
            $response = [
                'fail' => $e->getMessage()
            ];
        }

        $this->getResponse()->representJson(
            $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($response)
        );
    }
}
