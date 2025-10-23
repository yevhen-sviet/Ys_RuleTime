<?php
/**
 * Created by Yevhen Sviet
 */
namespace Ys\RuleTime\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data
{
    /**
     * Path to enabled config
     */
    public const XML_PATH_ENABLED = 'ys_ruletime/general/enable';

    /**
     * @param ScopeConfigInterface $cfg
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        private ScopeConfigInterface $cfg,
        private StoreManagerInterface $storeManager
    ) {}

    /**
     * Check if module is enabled
     *
     * @param int|null $websiteId
     * @return bool
     */
    public function isEnabled(?int $websiteId = null): bool
    {
        if ($websiteId === null) {
            try {
                $websiteId = (int)$this->storeManager->getStore()->getWebsiteId();
            } catch (\Throwable) {
                $websiteId = null;
            }
        }

        if ($websiteId !== null) {
            return $this->cfg->isSetFlag(
                self::XML_PATH_ENABLED,
                ScopeInterface::SCOPE_WEBSITE,
                $websiteId
            );
        }

        return $this->cfg->isSetFlag(self::XML_PATH_ENABLED);
    }
}
