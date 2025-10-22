<?php
namespace Ys\RuleTime\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    public const XML_PATH_ENABLED = 'ys_ruletime/general/enable';

    public function __construct(private StoreManagerInterface $storeManager) {}

    public function isEnabled(?int $websiteId = null): bool
    {
        if ($websiteId === null) {
            try {
                $websiteId = (int)$this->storeManager->getStore()->getWebsiteId();
            } catch (\Throwable $e) {
                $websiteId = null;
            }
        }

        if ($websiteId !== null) {
            return $this->scopeConfig->isSetFlag(
                self::XML_PATH_ENABLED,
                ScopeInterface::SCOPE_WEBSITE,
                $websiteId
            );
        }

        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED);
    }
}
