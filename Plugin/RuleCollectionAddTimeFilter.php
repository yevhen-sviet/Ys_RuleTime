<?php
/**
 * Created by Yevhen Sviet
 */
namespace Ys\RuleTime\Plugin;

use Magento\SalesRule\Model\ResourceModel\Rule\Collection as RuleCollection;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Ys\RuleTime\Helper\Data as Helper;

class RuleCollectionAddTimeFilter
{
    /**
     * @param TimezoneInterface $tz
     * @param Helper $helper
     */
    public function __construct(
        private TimezoneInterface $tz,
        private Helper $helper
    ) {}
    
    /**
     * @param RuleCollection $subject
     * @param \Closure $proceed
     * @param int $websiteId
     * @param int $customerGroupId
     * @param string|null $now
     * @return RuleCollection
     */
    public function aroundAddWebsiteGroupDateFilter(
        \Magento\SalesRule\Model\ResourceModel\Rule\Collection $subject,
        \Closure $proceed,
        $websiteId,
        $customerGroupId,
        $now = null
    ) {
        $result = $proceed($websiteId, $customerGroupId, $now);

        if (!$this->helper->isEnabled((int)$websiteId)) {
            return $result;
        }

        // Current website/store-local time
        $nowDt   = $this->tz->date();
        $nowTime = $nowDt->format('H:i:s');

        $conn   = $subject->getConnection();
        $select = $subject->getSelect();

        // Join ys_salesrule_time once (skip if already present)
        $fromParts = $select->getPart(\Zend_Db_Select::FROM);
        if (!isset($fromParts['ysrt'])) {
            $select->joinLeft(
                ['ysrt' => $subject->getTable('ys_salesrule_time')],
                'ysrt.rule_id = main_table.rule_id',
                []
            );
        }

        // Pre-quote scalars to avoid adapter quirks with array binds
        $qNow     = $conn->quote($nowTime);
        $qMinTime = $conn->quote('00:00:00');
        $qMaxTime = $conn->quote('23:59:59');

        // Daily window guard: if a rule has any time bound, exclude it when NOW is outside [from..to]
        $select->where(sprintf(
            'NOT (
                (ysrt.from_time IS NOT NULL OR ysrt.to_time IS NOT NULL)
                AND NOT (
                    COALESCE(ysrt.from_time, %1$s) <= %2$s
                    AND %2$s <= COALESCE(ysrt.to_time, %3$s)
                )
            )',
            $qMinTime, // %1$s
            $qNow,     // %2$s
            $qMaxTime  // %3$s
        ));

        // Optional debugging:
        // @file_put_contents(BP.'/var/log/ys_ruletime_sql.log', '['.date('H:i:s')."] now=$nowTime\n".$select."\n\n", FILE_APPEND);

        return $result;
    }
}
