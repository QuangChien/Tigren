<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tigren\CustomerGroupCatalogRule\Pricing\Render;

use Magento\Framework\Pricing\Amount\AmountInterface;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template;

/**
 * Price amount renderer
 *
 * @method string getAdjustmentCssClasses()
 * @method string getDisplayLabel()
 * @method string getPriceId()
 * @method bool getIncludeContainer()
 * @method bool getSkipAdjustments()
 */
class Amount extends Template implements \Magento\Framework\Pricing\Render\AmountRenderInterface
{
    /**
     * @var SaleableInterface
     */
    protected $saleableItem;

    /**
     * @var PriceInterface
     */
    protected $price;

    /**
     * @var AdjustmentRenderInterface[]
     */
    protected $adjustmentRenders;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var RendererPool
     */
    protected $rendererPool;

    /**
     * @var AmountInterface
     */
    protected $amount;

    /**
     * @var null|float
     */
    protected $displayValue;

    /**
     * @var string[]
     */
    protected $adjustmentsHtml = [];

    protected $ruleHelper;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Product
     */
    private $product;

    /**
     * @param Template\Context $context
     * @param AmountInterface $amount
     * @param PriceCurrencyInterface $priceCurrency
     * @param RendererPool $rendererPool
     * @param SaleableInterface $saleableItem
     * @param \Magento\Framework\Pricing\Price\PriceInterface $price
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        AmountInterface $amount,
        PriceCurrencyInterface $priceCurrency,
        RendererPool $rendererPool,
        SaleableInterface $saleableItem = null,
        PriceInterface $price = null,
        \Tigren\CustomerGroupCatalogRule\Helper\GetRuleCatalog $getRuleCatalog,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->amount = $amount;
        $this->saleableItem = $saleableItem;
        $this->price = $price;
        $this->priceCurrency = $priceCurrency;
        $this->rendererPool = $rendererPool;
        $this->ruleHelper = $getRuleCatalog;
        $this->registry = $registry;
    }

    /**
     * @return Product
     */
//    private function getProduct()
//    {
//        if (is_null($this->product)) {
//            $this->product = $this->registry->registry('product');
//
//            if (!$this->product->getId()) {
//                throw new LocalizedException(__('Failed to initialize product'));
//            }
//        }
//
//        return $this->product;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getProductSku()
//    {
//        return $this->getProduct()->getSku();
//    }

    /**
     * @return bool
     */
    public function isHideProduct()
    {
        return $this->ruleHelper->isHide('hide_product_status');
    }

    /**
     * @return bool
     */
    public function isHidePrice()
    {
        return $this->ruleHelper->isHide('hide_product_price_status');
    }

    /**
     * @return array|false|string[]
     */
    public function getProductInRule()
    {
        return $this->ruleHelper->getEntityInRule('product_select');
    }

//    public function getRuleId()
//    {
//        return $this->ruleHelper->getRule()->getData();
//    }

    /**
     * @return string
     */
//    public function statusPrice()
//    {
//        var_dump(in_array($this->getProductSku(), $this->getProductInRule()));
//        if(in_array($this->getProductSku(), $this->getProductInRule())){
//            if($this->isHideProduct() == false){
//                if($this->isHidePrice()){
//                    return "display_none";
//                }
//                return "d";
//            }
//            return "d";
//        }
//        return "d";
//    }

    /**
     * @param float $value
     * @return void
     */
    public function setDisplayValue($value)
    {
        $this->displayValue = $value;
    }


    /**
     * @return float
     */
    public function getDisplayValue()
    {
        if ($this->displayValue !== null) {
            return $this->displayValue;
        } else {
            return $this->getAmount()->getValue();
        }
    }

    /**
     * @return AmountInterface
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return SaleableInterface
     */
    public function getSaleableItem()
    {
        return $this->saleableItem;
    }

    /**
     * @return \Magento\Framework\Pricing\Price\PriceInterface
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getDisplayCurrencyCode()
    {
        return $this->priceCurrency->getCurrency()->getCurrencyCode();
    }

    /**
     * @return string
     */
    public function getDisplayCurrencySymbol()
    {
        return $this->priceCurrency->getCurrencySymbol();
    }

    /**
     * @return bool
     */
    public function hasAdjustmentsHtml()
    {
        return (bool) count($this->adjustmentsHtml);
    }

    /**
     * @return string
     */
    public function getAdjustmentsHtml()
    {
        return implode('', $this->adjustmentsHtml);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $adjustmentRenders = $this->getAdjustmentRenders();
        if ($adjustmentRenders) {
            $adjustmentHtml = $this->getAdjustments($adjustmentRenders);
            if (!$this->hasSkipAdjustments() ||
                ($this->hasSkipAdjustments() && $this->getSkipAdjustments() == false)) {
                $this->adjustmentsHtml = $adjustmentHtml;
            }
        }
        $html = parent::_toHtml();
        return $html;
    }

    /**
     * @return AdjustmentRenderInterface[]
     */
    protected function getAdjustmentRenders()
    {
        return $this->rendererPool->getAdjustmentRenders($this->saleableItem, $this->price);
    }

    /**
     * @param AdjustmentRenderInterface[] $adjustmentRenders
     * @return array
     */
    protected function getAdjustments($adjustmentRenders)
    {
        $this->setAdjustmentCssClasses($adjustmentRenders);
        $data = $this->getData();
        $adjustments = [];
        foreach ($adjustmentRenders as $adjustmentRender) {
            if ($this->getAmount()->getAdjustmentAmount($adjustmentRender->getAdjustmentCode()) !== false) {
                $html = $adjustmentRender->render($this, $data);
                if (trim($html)) {
                    $adjustments[$adjustmentRender->getAdjustmentCode()] = $html;
                }
            }
        }
        return $adjustments;
    }

    /**
     * Format price value
     *
     * @param float $amount
     * @param bool $includeContainer
     * @param int $precision
     * @return float
     */
    public function formatCurrency(
        $amount,
        $includeContainer = true,
        $precision = PriceCurrencyInterface::DEFAULT_PRECISION
    ) {
        return $this->priceCurrency->format($amount, $includeContainer, $precision);
    }

    /**
     * @param AdjustmentRenderInterface[] $adjustmentRenders
     * @return array
     */
    protected function setAdjustmentCssClasses($adjustmentRenders)
    {
        $cssClasses = $this->hasData('css_classes') ? explode(' ', $this->getData('css_classes')) : [];
        $cssClasses = array_merge($cssClasses, array_keys($adjustmentRenders));
        $this->setData('adjustment_css_classes', join(' ', $cssClasses));
        return $this;
    }
}

//<?php
///**
// * @author  Tigren Solutions <info@tigren.com>
// * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
// * @license  Open Software License (“OSL”) v. 3.0
// */
//
//namespace Tigren\CustomerGroupCatalogRule\Pricing\Render;
//
//use Magento\Framework\Pricing\Render\Amount as AmountCore;
//
///**
// * @api
// * @since 100.0.2
// */
//class Amount extends AmountCore
//{
//    public function __construct(
//        \Magento\Framework\View\Element\Template\Context $context,
//        \Magento\Framework\Pricing\Render\AmountInterface $amount,
//        PriceCurrencyInterface $priceCurrency,
//        RendererPool $rendererPool,
//        SaleableInterface $saleableItem = null,
//        PriceInterface $price = null,
//    ) {
//        parent::__construct($context, $amount, $saleableItem, $price, $priceCurrency, $rendererPool);
//    }
//
//    public function test()
//    {
//        return "test";
//    }
//}