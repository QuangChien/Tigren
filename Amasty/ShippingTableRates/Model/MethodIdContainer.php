<?php
declare(strict_types=1);

namespace Amasty\ShippingTableRates\Model;

class MethodIdContainer
{
    /**
     * @var int|null
     */
    private $methodId;

    /**
     * @return int|null
     */
    public function getMethodId(): ?int
    {
        return $this->methodId;
    }

    /**
     * @param int $methodId
     */
    public function setMethodId(int $methodId): void
    {
        $this->methodId = $methodId;
    }
}
