<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Controller\Test;

use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;


    protected $_ruleCollection;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    protected $date;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context                                                                     $context,
        PageFactory                                                                 $pageFactory,
        \Tigren\CustomerGroupCatalogRule\Model\ResourceModel\Rule\CollectionFactory $ruleCollection,
        \Magento\Customer\Model\Session                                             $customerSession,
        \Magento\Framework\Stdlib\DateTime\DateTime                                 $date
    )
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->_ruleCollection = $ruleCollection;
        $this->_customerSession = $customerSession;
        $this->date = $date;
    }

    /**
     * Index Action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        echo "<pre>";
//        echo $this->getDateCurrent();die();
        print_r($this->getRule());
        die();
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->pageFactory->create();
        return $resultPage;
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
            ->setOrder('priority')
            ->addFieldToFilter('time_rule_start', ['lteq' => $this->getDateCurrent()])
            ->addFieldToFilter('time_rule_end', ['gteq' => $this->getDateCurrent()])
            ->addFieldToFilter('customer_group', array(
                array('like' => '%/' . 2 . '/%'),
                array('like' => '%/' . 2),
                array('like' => 2 . '/%')
            ))->load()
            ->getFirstItem();
        return $rule;
    }
}


