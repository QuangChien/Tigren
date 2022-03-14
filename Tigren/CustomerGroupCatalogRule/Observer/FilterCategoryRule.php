<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Observer;

class FilterCategoryRule implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Tigren\CustomerGroupCatalogRule\Model\ResourceModel\Rule\CollectionFactory
     */
    protected $_ruleCollection;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    public function __construct
    (
        \Tigren\CustomerGroupCatalogRule\Model\ResourceModel\Rule\CollectionFactory $ruleCollection,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    )
    {
        $this->_ruleCollection = $ruleCollection;
        $this->_customerSession = $customerSession;
        $this->date = $date;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
//        echo "<pre>";
//        print_r($this->getCategoryInRule()); die();
        $collection = $observer->getData('category_collection');
        $collection->addFieldToFilter('entity_id', array('nin' => $this->getCategoryInRule()));
//        echo "<pre>";
//        print_r($collection->getData()); die();
////        $categories = $observer->getData('menu');
////        die();
//        $writer = new \Laminas\Log\Writer\Stream(BP . '/var/log/custom.log');
//        $logger = new \Laminas\Log\Logger();
//        $logger->addWriter($writer);
//        $logger->info('Hello');
    }

    /**
     * @return false|string
     */
    public function getDateCurrent()
    {
        $date = $this->date->gmtDate('Y-m-d');
        return $date;
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    public function getRule()
    {
        $rule = $this->_ruleCollection->create()
            ->addFieldToFilter('status', 1)
            ->setOrder('priority')->load()
            ->addFieldToFilter('time_rule_start', ['lteq' => $this->getDateCurrent()])
            ->addFieldToFilter('time_rule_end', ['gteq' => $this->getDateCurrent()])
            ->addFieldToFilter('customer_group', array(
                array('like' => '%/' . $this->getGroupId() . '/%'),
                array('like' => '%/' . $this->getGroupId()),
                array('like' => $this->getGroupId() . '/%'),
                array('like' => $this->getGroupId())
            ))
//            ->getSelect()->__toString();
            ->getFirstItem();
        return $rule;
    }

    /**
     * @return array|false|string[]
     */
    public function getCategoryInRule()
    {
        if(!$this->getRule()->isEmpty()){
            $category = explode( '/', $this->getRule()->getData('category_hide'));
            return $category;
        }
        return [];
    }

    /**
     * @return int|null
     */
    public function getGroupId(){
        if($this->_customerSession->isLoggedIn()){
            $customerGroup = $this->_customerSession->getCustomer()->getGroupId();
        }else{
            $customerGroup = 0;
        }
        return $customerGroup;
    }
}
