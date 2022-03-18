<?php
/**
 * @author  Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2022 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license  Open Software License (“OSL”) v. 3.0
 */

namespace Tigren\CustomerGroupCatalogRule\Pricing\Render;

use Magento\Framework\Pricing\Render\RendererPool as RendererPoolCore;

/**
 * @api
 * @since 100.0.2
 */
class RendererPool extends RendererPoolCore
{

    public function test()
    {
        return "test";
    }
}
