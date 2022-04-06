<?php

namespace Tigren\ShippingRateApi\Model;

use Tigren\ShippingRateApi\Api\Data\ShippingRateInterface;
use Tigren\ShippingRateApi\Api\Data\SuccessGetRateInterface;
use Tigren\ShippingRateApi\Api\Data\ErrorGetRateInterface;
use Tigren\ShippingRateApi\Api\ShippingRateRepositoryInterface;
use Amasty\ShippingTableRates\Model\ResourceModel\Rate\CollectionFactory;
use Amasty\ShippingTableRates\Model\ResourceModel\Method\CollectionFactory as MethodCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session;
use Tigren\ShippingRateApi\Model\SuccessGetRateFactory;
use Tigren\ShippingRateApi\Model\MethodShippingFactory;
use Tigren\ShippingRateApi\Model\ErrorGetRateFactory;

class ShippingRateRepository implements ShippingRateRepositoryInterface
{
    /**
     * @var \Amasty\ShippingTableRates\Model\ResourceModel\Rate\CollectionFactory
     */
    protected $rateCollection;

    /**
     * @var CollectionFactory
     */
    protected $methodCollection;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Session
     */
    protected $_customerSession;


    /**
     * @var \Tigren\ShippingRateApi\Model\SuccessGetRateFactory
     */
    protected $successGetRate;

    /**
     * @var \Tigren\ShippingRateApi\Model\ErrorGetRateFactory
     */
    protected $errorGetRate;

    public function __construct(
        CollectionFactory       $rateCollection,
        StoreManagerInterface   $storeManager,
        Session                 $customerSession,
        MethodCollectionFactory $methodCollection,
        SuccessGetRateFactory   $successGetRate,
        ErrorGetRateFactory     $errorGetRate
    )
    {
        $this->rateCollection = $rateCollection;
        $this->storeManager = $storeManager;
        $this->_customerSession = $customerSession;
        $this->methodCollection = $methodCollection;
        $this->successGetRate = $successGetRate;
        $this->errorGetRate = $errorGetRate;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAllMethodIds(): array
    {
        $methods = $this->methodCollection->create()
            ->addFieldToFilter('is_active', 1)
            ->addStoreFilter($this->getStoreId())
            ->addCustomerGroupFilter($this->getGroupId())
            ->getAllIds();
        return $methods;
    }

    /**
     * @return \Tigren\ShippingRateApi\Api\Data\SuccessGetRateInterface | \Tigren\ShippingRateApi\Api\Data\ErrorGetRateInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData($postcode, $qty)
    {
        try {
            $data = [];
            $successRate = $this->successGetRate->create();
            foreach ($this->getAllMethodIds() as $methodId) {
                $rates = $this->rateCollection->create()->addFieldToFilter('method_id', $methodId);
                if ($rates->count() > 0) {
                    $rates = $rates->addFieldToFilter('qty_from', array(
                        array('lteq' => $qty)
                    ))
                        ->addFieldToFilter('qty_to', array(
                            array('gteq' => $qty),
                        ))
                        ->addFieldToFilter('zip_from', array(
                            array('lteq' => $postcode),
                            array('zip_from' => ''),
                        ))
                        ->addFieldToFilter('zip_to', array(
                            array('gteq' => $postcode),
                            array('zip_to' => ''),
                        ));

                    //get cost_base min
                    $minCostBase = 10000000000;
                    foreach ($rates->addFieldToFilter('cost_base') as $rate) {
                        if ($rate->getCostBase() < $minCostBase ) {
                            $minCostBase = $rate->getCostBase();
                        }
                    }

                    $rateArray = $rates->addFieldToFilter('cost_base', $minCostBase)->getData();
                    foreach ($rateArray as $rateItem){
                        $data[] = $rateItem;
                        break;
                    }
                }
            }
            $successRate->setRate($data);
            $successRate->setStatus("200");
            $successRate->setMessage("Success");

        } catch (\Exception $e) {
            $errorRate = $this->errorGetRate->create();
            $errorRate->setStatus("500");
            $errorRate->setMessage("The product that was requested doesn’t exist. Verify the product and try again.");
            return $errorRate;
        }
        return $successRate;
    }

    /**
     * @return int
     */
    public function getGroupId()
    {
        if ($this->_customerSession->isLoggedIn()) {
            $customerGroup = $this->_customerSession->getCustomer()->getGroupId();
        } else {
            $customerGroup = 0;
        }
        return $customerGroup;
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreId()
    {
        $storeId = $this->storeManager->getStore()->getId();
        return $storeId;
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingRate($postcode, array $products)
    {
        $qty = 0;
        foreach ($products as $product) {
            $qty += $product['qty'];
        }
        return $this->getData($postcode, $qty);
    }
}
