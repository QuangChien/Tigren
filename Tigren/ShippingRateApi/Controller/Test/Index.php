<?php

namespace Tigren\ShippingRateApi\Controller\Test;

use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Amasty\ShippingTableRates\Model\ResourceModel\Rate\CollectionFactory;
use Amasty\ShippingTableRates\Model\ResourceModel\Method\CollectionFactory as MethodCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var CollectionFactory
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
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context                 $context,
        PageFactory             $pageFactory,
        CollectionFactory       $rateCollection,
        StoreManagerInterface   $storeManager,
        Session                 $customerSession,
        MethodCollectionFactory $methodCollection

    )
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->rateCollection = $rateCollection;
        $this->storeManager = $storeManager;
        $this->_customerSession = $customerSession;
        $this->methodCollection = $methodCollection;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAllMethodIds()
    {
        $methods = $this->methodCollection->create()
            ->addFieldToFilter('is_active', 1)
            ->addStoreFilter($this->getStoreId())
            ->addCustomerGroupFilter($this->getGroupId())
            ->getAllIds();
        return $methods;
    }

    /**
     * @param $name
     * @return string
     */
    public function formatName($name)
    {
        return strtolower(preg_replace('#[^0-9a-z]+#i', '_', $name));
    }

    /**
     * Index Action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {

        $this->getData();
        die();


        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->pageFactory->create();
        return $resultPage;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {
        $data = [];
        $methods = $this->methodCollection->create()
            ->addFieldToFilter('is_active', 1)
            ->addStoreFilter($this->getStoreId())
            ->addCustomerGroupFilter($this->getGroupId());
        foreach ($this->getAllMethodIds() as $methodId) {
            $rates = $this->rateCollection->create()->addFieldToFilter('method_id', $methodId);
            if ($rates->count() > 0) {
                $rates = $rates->addFieldToFilter('qty_from', array(
                    array('lteq' => 5)
                ))
                    ->addFieldToFilter('qty_to', array(
                        array('gteq' => 5),
                    ))
                    ->addFieldToFilter('zip_from', array(
                        array('lteq' => 2000),
                        array('zip_from' => ''),
                    ))
                    ->addFieldToFilter('zip_to', array(
                        array('gteq' => 2000),
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
        return $data;
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

    public function getRate()
    {
        $rates = $this->rateCollection->create()
            ->addFieldToFilter('qty_from', array(
                array('lteq' => 2)
            ))
            ->addFieldToFilter('qty_to', array(
                array('gteq' => 2),
            ))
            ->addFieldToFilter('zip_from', array(
                array('lteq' => 1200),
                array('zip_from' => ''),
            ))
            ->addFieldToFilter('zip_to', array(
                array('gteq' => 1200),
                array('zip_to' => ''),
            ))
            ->addFieldToFilter('method_id', ['in' => $this->getAllMethodIds()])
            ->setOrder('cost_base', 'ESC');

        return $rates;
    }
}
