<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Observer;

class OptionCategorytRule implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Tigren\CustomerGroupCatalogRule\Helper\GetRuleCatalog
     */
    protected $ruleHelper;

    /**
     * @var PageRepositoryInterface
     */
    private $_cmsPage;

    /**
     * @var SearchCriteriaBuilder
     */
    private $_search;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\UrlInterface $urlInterface
     */
    protected $_urlInterface;

    protected $response;

    public function __construct
    (
        \Tigren\CustomerGroupCatalogRule\Helper\GetRuleCatalog $getRuleCatalog,
        \Magento\Cms\Api\PageRepositoryInterface $pageRepositoryInterface,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\App\Response\Http $response
    )
    {
        $this->ruleHelper = $getRuleCatalog;
        $this->_cmsPage = $pageRepositoryInterface;
        $this->_search = $searchCriteriaBuilder;
        $this->_storeManager = $storeManager;
        $this->_urlInterface = $urlInterface;
        $this->response = $response;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCmsPages()
    {
        $pages = [];
        foreach($this->_cmsPage->getList($this->_getSearchCriteria())->getItems() as $page) {
            $pages[] = [
                'identifier' => $page->getIdentifier(),
                'pageId' => $page->getPageId()
            ];
        }
        return $pages;
    }

    /**
     * @param $pageId
     * @return mixed|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getIdentifierCmsPageRedirect($pageId)
    {
        foreach ($this->getCmsPages() as $cmsPage){
            if($cmsPage['pageId'] == $pageId ){
                return $cmsPage['identifier'];
            }
        }
        return '';
    }

    protected function _getSearchCriteria()
    {
        return $this->_search->addFilter('is_active', '1')->create();
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get404UrlIdentifier()
    {
        foreach ($this->getCmsPages() as $cmsPage){
            if($cmsPage['pageId'] == 1 || $cmsPage['identifier'] == 'no-route'){
                return $cmsPage['identifier'];
            }
        }
        return '';
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $request = $observer->getRequest();
        $id = $request->getParams()['id'];
        $directLinkStatus = $this->ruleHelper->isHide('direct_link_status');
        $categoryInRule = $this->ruleHelper->getEntityInRule('category_hide');

        $actionOnForbidStatus = $this->ruleHelper->isHide('action_on_forbid');
        $hideCategoryStatus = $this->ruleHelper->isHide('hide_category_status');
        $cmsPageUrl = $this->ruleHelper->getRule()->getData('cms_pages_url');
        $hideCategoryStatus = $this->ruleHelper->isHide('hide_category_status');

        if(in_array($id, $categoryInRule)){
            if($hideCategoryStatus){
                if($directLinkStatus !== true){
                    if($actionOnForbidStatus == false){
                        $this->response->setRedirect($this->getBaseUrl() . $this->get404UrlIdentifier());
                    }else{
                        $this->response->setRedirect($this->getBaseUrl() . $this->getIdentifierCmsPageRedirect($cmsPageUrl));
                    }
                }
            }
        }
//                echo "<pre>";
//        print_r($this->getRule()->getData());die();
//        $this->getProductInRule();
//        echo "<pre>";
//        var_dump($this->getProductInRule()); die();

//        var_dump($this->getRules()->getData()); die();
//        echo "<pre>";
//        print_r($collection->getData());die();


//        echo "<pre>";
//        print_r($collection->getData());
//        die();
////        $categories = $observer->getData('menu');
////        die();
//        $writer = new \Laminas\Log\Writer\Stream(BP . '/var/log/custom.log');
//        $logger = new \Laminas\Log\Logger();
//        $logger->addWriter($writer);
//        $logger->info('Hello Quang Chien');
    }
}
