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
        RuleCollection $subject,
        \Closure $proceed,
        $websiteId,
        $customerGroupId,
        $now = null
    ) {
        $result = $proceed($websiteId, $customerGroupId, $now);

        if (!$this->helper->isEnabled((int)$websiteId)) {
            return $result;
        }

        $nowDt   = $this->tz->date();
        $today   = $nowDt->format('Y-m-d');
        $nowTime = $nowDt->format('H:i:s');

        $conn   = $subject->getConnection();
        $select = $subject->getSelect();

        $select->joinLeft(
            ['ysrt' => $subject->getTable('ys_salesrule_time')],
            'ysrt.rule_id = main_table.rule_id',
            []
        );

        $sameDay = $conn->quoteInto(
            "NOT (
                main_table.from_date = ?
                AND main_table.to_date = ?
                AND (ysrt.from_time IS NOT NULL OR ysrt.to_time IS NOT NULL)
                AND NOT (
                    COALESCE(ysrt.from_time,'00:00:00') <= ?
                    AND ? <= COALESCE(ysrt.to_time,'23:59:59')
                )
            )",
            [$today, $today, $nowTime, $nowTime]
        );
        $from = $conn->quoteInto(
            'NOT (
                main_table.from_date = ? 
                AND ysrt.from_time IS NOT NULL 
                AND ? < ysrt.from_time
            )',
            [$today, $nowTime]
        );
        $to = $conn->quoteInto(
            'NOT (
                main_table.to_date = ? 
                AND ysrt.to_time IS NOT NULL 
                AND ? > ysrt.to_time
            )',
            [$today, $nowTime]
        );

        $select->where($sameDay . ' AND ' . $from . ' AND ' . $to);

        return $result;
    }
}
