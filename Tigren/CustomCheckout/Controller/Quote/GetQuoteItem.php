<?php

namespace Tigren\CustomCheckout\Controller\Quote;

use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

class Index extends Action
{

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

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
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory

    )
    {
        parent::__construct($context);
        $this->_checkoutSession = $checkoutSession;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Index Action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $countQuoteItem = count($this->_checkoutSession->getQuote()->getAllVisibleItems());
        return $resultJson->setData(['data' => $countQuoteItem]);
    }
}
