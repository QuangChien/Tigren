<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Tigren\CustomerGroupCatalogRule\Model\ResourceModel\Rule\CollectionFactory;
use Tigren\CustomerGroupCatalogRule\Model\RuleFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Backend\Model\View\Result\RedirectFactory;

class Delete extends Action
{
    private $_rule;
    private $_filter;
    private $_collection;
    private $_resultRedirect;

    public function __construct(
        Action\Context    $context,
        RuleFactory       $ruleFactory,
        Filter            $filter,
        CollectionFactory $collectionFactory,
        RedirectFactory   $redirectFactory
    )
    {
        parent::__construct($context);
        $this->_rule = $ruleFactory;
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        $this->_resultRedirect = $redirectFactory;
    }

    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $total = 0;
        $err = 0;

        foreach ($collection->getItems() as $item) {
            $deleteField = $this->_rule->create()->load($item->getData('rule_id'));
            try {
                $deleteField->delete();
                $total++;
            } catch (LocalizedException $exception) {
                $err++;
            }
        }

        if ($total) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $total)
            );
        }

        if ($err) {
            $this->messageManager->addErrorMessage(
                __(
                    'A total of %1 record(s) haven\'t been deleted. Please see server logs for more details.',
                    $err
                )
            );
        }

        return $this->redirectToIndex();
    }

    /**
     * Redirect to Rule list
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function redirectToIndex()
    {
        return $this->_resultRedirect->create()->setPath('customergr_catalogrule/rule/index');
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
