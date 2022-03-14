<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Controller\Adminhtml\Rule;

use Tigren\CustomerGroupCatalogRule\Model\RuleFactory;
use Tigren\CustomerGroupCatalogRule\Model\ResourceModel\Rule\CollectionFactory;
use Magento\Backend\App\Action;

class Save extends Action
{
    protected $_rule;
    protected $data;
//    protected $_collectionFactory;

    public function __construct(
        Action\Context $context,
        RuleFactory $ruleFactory
    )
    {
        parent::__construct($context);
        $this->_rule = $ruleFactory;
//        $this->_collectionFactory = $collectionFactory;
        $this->data = $this->getRequest()->getPostValue();
//        echo "<pre>";
//        print_r($this->data);die();
    }

    public function hasId()
    {
        return !empty($this->data['rule_id']) ? 1 : 0;
    }

    public function getId()
    {
        if($this->hasId()){
            return $this->data['rule_id'];
        }
    }

    /**
     * @return array
     */
    public function dataRule()
    {
        $data = [
            'rule_name' => $this->data['rule_name'],
            'priority' => $this->data['priority'],
            'status' => $this->data['status'],
            'date_range_status' => $this->data['date_range_status'],
            'hide_category_status' => $this->data['hide_category_status'],
            'hide_product_status' => $this->data['hide_product_status'],
            'hide_product_price_status' => $this->data['hide_product_price_status'],
            'hide_add_to_cart_status' => $this->data['hide_add_to_cart_status'],
            'hide_add_to_wishlist_status' => $this->data['hide_add_to_wishlist_status'],
            'hide_add_to_compare_status' => $this->data['hide_add_to_compare_status'],
            'direct_link_status' => $this->data['direct_link_status'],
            'time_rule_start' => $this->data['time_rule_start'],
            'time_rule_end' => $this->data['time_rule_end'],
            'action_on_forbid' => $this->data['action_on_forbid'],
            'cms_pages_url' => $this->data['cms_pages'],
            'store_views' => $this->convertToString('store_views', 'store_views'),
            'customer_group' => $this->convertToString('customer_group'),
            'category_hide' => $this->convertToString('category_hide'),
            'product_hide' => $this->convertToString('product_hide')
        ];
        return $data;
    }

    /**
     * @return mixed|string|null
     */
    public function convertToString($field, $condition = '')
    {
        if (isset($this->data[$field])) {
            if (count($this->data[$field]) < 2) {
                $fieldValue = $this->data[$field][0];
            } else {
                if('store_views' == $condition){
                    $fieldValue = 1;
                }else{
                    $fieldValue = implode("/", $this->data[$field]);
                }
            }
            return $fieldValue;
        }
        return null;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
//        echo "<pre>";
//        print_r($this->dataRule()); die();
        $rule = $this->_rule->create();
        if ($this->hasId()) {
            $rule->load($this->getId());
        }
        try {
            //save data
            $rule->addData($this->dataRule());
            $rule->save();
            $this->messageManager->addSuccessMessage(__('Save Success'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }

        return $this->redirectToIndex();
    }

    /**
     * Redirect to rule list
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function redirectToIndex()
    {
        return $this->resultRedirectFactory->create()->setPath('customergr_catalogrule/rule/index');
    }


    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Customer::customer_group_rule');
    }
}
