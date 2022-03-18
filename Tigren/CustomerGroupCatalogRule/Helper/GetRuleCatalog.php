<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Helper;

class GetRuleCatalog extends \Magento\Framework\App\Helper\AbstractHelper
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

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

//    /**
//     * @var \Magento\Store\Model\ScopeInterface
//     */
//    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context                                       $context,
        \Tigren\CustomerGroupCatalogRule\Model\ResourceModel\Rule\CollectionFactory $ruleCollection,
        \Magento\Customer\Model\Session                                             $customerSession,
        \Magento\Framework\Stdlib\DateTime\DateTime                                 $date,
        \Magento\Store\Model\StoreManagerInterface                                  $storeManager
//        \Magento\Store\Model\ScopeInterface $scopeInterface
    )
    {
        $this->_ruleCollection = $ruleCollection;
        $this->_customerSession = $customerSession;
        $this->date = $date;
        $this->storeManager = $storeManager;
//        $this->_scopeConfig = $scopeInterface;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function isEnableModule()
    {
        return $this->scopeConfig->getValue('custom/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function isHide($field)
    {
        if ($this->getRule() !== null) {
            $hideField = $this->getRule()->getData($field);
            if ($hideField == 1) {
                return true;
            }
            return false;
        }
        return false;
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
    public function getRules()
    {
        $rules = $this->_ruleCollection->create()
            ->addFieldToFilter('status', 1)
            ->addFieldToFilter('time_rule_start', array(
                array('lteq' => $this->getDateCurrent()),
                array('null' => true),
            ))
            ->addFieldToFilter('time_rule_end', array(
                array('gteq' => $this->getDateCurrent()),
                array('null' => true),
            ))
            ->addFieldToFilter('customer_group', array(
                array('like' => '%/' . $this->getGroupId() . '/%'),
                array('like' => '%/' . $this->getGroupId()),
                array('like' => $this->getGroupId() . '/%'),
                array('like' => $this->getGroupId())
            ))
            ->addFieldToFilter('store_views', array(
                array('like' => '%/' . $this->getStoreId() . '/%'),
                array('like' => '%/' . $this->getStoreId()),
                array('like' => $this->getStoreId() . '/%'),
                array('like' => $this->getStoreId()),
                array('like' => 0)
            ));
        return $rules;
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
     * @return null|object
     */
    public function getRule()
    {
        if (!empty($this->getRules())) {
            $rule = $this->getRules()->setOrder('priority', 'DESC')->load()->getFirstItem();
            return $rule;
        }
        return null;
    }


    /**
     * @param $field
     * @return array|false|string[]
     */
    public function getEntityInRule($field)
    {
        if ($this->getRule() !== null) {
            $entityValue = explode('/', $this->getRule()->getData($field));
            return $entityValue;
        }
        return [];
    }

    /**
     * @return bool
     */
    public function checkRuleInStore()
    {
        if ($this->getRule() !== null) {
            $storeViews = explode('/', $this->getRule()->getData('store_views'));
            if (in_array($this->getStoreId(), $storeViews) || $this->getRule()->getData('store_views') == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return int|null
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
}
