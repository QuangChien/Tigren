<?php
/*
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomCheckout\Model;

/**
 * Class CustomManagement
 * @package ViMagento\CustomApi\Model
 */
class CustomManagement implements \Tigren\CustomCheckout\Api\GetProductInterface
{
    protected $request;
    protected $_productRepository;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Catalog\Model\ProductRepository $productRepository
    ) {
       $this->request = $request;
        $this->_productRepository = $productRepository;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->request->getParam('prdId');
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface|mixed|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductById()
    {
        return $this->_productRepository->getById($this->getProductId());
    }


    /**
     * {@inheritdoc}
     */
    public function getMultipleOrder()
    {
        try{
            $multipleOrder = [
                'multiple_order' => $this->getProductById()->getData('multiple_order')
            ];
        } catch (\Exception $e) {
            $multipleOrder=['error' => $e->getMessage()];
        }
        return json_encode($multipleOrder);

    }
}
