<?php
/*
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomCheckout\Controller\Quote;

use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

class GetMultipleOrderOfQuoteItem extends Action
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    protected $cart;

    protected $_productRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context                                  $context,
        \Magento\Checkout\Model\Cart             $cart,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        parent::__construct($context);
        $this->cart = $cart;
        $this->_productRepository = $productRepository;
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
        $arrMultipleOrder = [];
        $items = $this->cart->getProductIds();
        foreach ($items as $item) {
            $arrMultipleOrder[] = $this->_productRepository->getById($item)->getData('multiple_order');
        }
        return $resultJson->setData(['data' => $arrMultipleOrder]);
    }
}
