<?php
namespace Tigren\CustomCheckout\Controller\Test;

use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    protected $cart;

    protected $_productRepository;
    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(Context $context,
                                \Magento\Checkout\Model\Cart $cart,
                                \Magento\Catalog\Model\ProductRepository $productRepository
    )
    {
        parent::__construct($context);
        $this->cart = $cart;
        $this->_productRepository = $productRepository;
    }

    /**
     * Index Action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $arr = [];
        $items = $this->cart->getProductIds();
        foreach($items as $item) {
            $arr[] = $this->_productRepository->getById($item)->getData('multiple_order');
        }
    }
}
