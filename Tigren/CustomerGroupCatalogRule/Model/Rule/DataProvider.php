<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Model\Rule;

use Tigren\CustomerGroupCatalogRule\Model\RuleFactory;
use Tigren\CustomerGroupCatalogRule\Model\ResourceModel\Rule\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $_loadedData;
    protected $collection;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function covertToArray($field, $ruleItem, $data)
    {
        if(!empty($ruleItem->getData($field))) {
            $arrField = explode("/",$ruleItem->getData($field));
            foreach ($arrField as $key => $arrFieldItem){
                $arrField[$field][$key] = $arrFieldItem;
            }

            $data = array_merge($data, $arrField);
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        $items = $this->collection->getItems();

        foreach ($items as $item) {
            $rule = $item->load($item->getRuleId());
            $fullData = $rule->getData();

            if(!empty($rule->getCustomerGroup())) {
                $arrCustomerGroup = explode("/",$rule->getCustomerGroup());
                foreach ($arrCustomerGroup as $key => $arrCustomerGroupItem){
                    $arrCustomerGroup['customer_group'][$key] = $arrCustomerGroupItem;
                }

                $fullData = array_merge($fullData, $arrCustomerGroup);
            }

            if(!empty($rule->getCategoryHide())) {
                $arrCategoryHide = explode("/",$rule->getCategoryHide());
                foreach ($arrCategoryHide as $key => $arrCategoryHideItem){
                    $arrCustomerGroup['category_hide'][$key] = $arrCategoryHideItem;
                }

                $fullData = array_merge($fullData, $arrCustomerGroup);
            }

            if(!empty($rule->getStoreViews())) {
                $arrStoreViews = explode("/",$rule->getStoreViews());
                foreach ($arrStoreViews as $key => $arrStoreView){
                    $arrStoreViews['store_views'][$key] = $arrStoreView;
                }

                $fullData = array_merge($fullData, $arrStoreViews);
            }

            $this->_loadedData[$rule->getId()] = $fullData;
        }
        return $this->_loadedData;

    }
}
