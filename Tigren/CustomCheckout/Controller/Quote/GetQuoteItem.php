<?php

namespace Tigren\CustomCheckout\Controller\Quote;

use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

class GetQuoteItem extends Action
{

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    protected $cart;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context                                          $context,
        \Magento\Checkout\Model\Session                  $checkoutSession,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Checkout\Model\Cart             $cart

    )
    {
        parent::__construct($context);
        $this->_checkoutSession = $checkoutSession;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->cart = $cart;
    }

    /**
     * Index Action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $productIds = [];
        $resultJson = $this->resultJsonFactory->create();
        $countQuoteItem = count($this->cart->getQuote()->getAllVisibleItems());
        foreach ($this->cart->getQuote()->getAllVisibleItems() as $item) {
            $productId = $item->getProduct()->getId();
            if ($option = $item->getOptionByCode('simple_product')) {
                $productIds[] = $option->getProduct()->getId();
            }

        }
        return $resultJson->setData(['data' => $countQuoteItem, 'productIds' =>$productIds]);
    }
}
