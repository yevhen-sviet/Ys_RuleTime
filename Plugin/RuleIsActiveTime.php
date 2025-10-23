<?php
/**
 * Created by Yevhen Sviet
 */
namespace Ys\RuleTime\Plugin;

use Magento\SalesRule\Model\Rule;
use Ys\RuleTime\Api\TimeRepositoryInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Ys\RuleTime\Helper\Data as Helper;

class RuleIsActiveTime
{
    /**
     * @param TimeRepositoryInterface $repo
     * @param TimezoneInterface $tz
     * @param Helper $helper
     */
    public function __construct(
        private TimeRepositoryInterface $repo,
        private TimezoneInterface $tz,
        private Helper $helper
    ) {}

    /**
     * @param Rule $rule
     * @param bool $result
     * @return bool
     */
    public function afterGetIsActive(Rule $rule, $result)
    {
        if (!$result) {
            return false;
        }

        if (!$this->helper->isEnabled(null)) {
            return $result;
        }

        try {
            $time = $this->repo->getByRuleId((int)$rule->getId());
        } catch (NoSuchEntityException $e) {
            return $result;
        }

        $now = $this->tz->date();
        $today = $now->format('Y-m-d');
        $nowTime = $now->format('H:i:s');

        $fromDate = $rule->getFromDate();
        $toDate = $rule->getToDate();

        if ($fromDate 
            && $fromDate === $today 
            && $time->getFromTime() 
            && $nowTime < $time->getFromTime()
        ) {
            return false;
        }
        if ($toDate   
            && $toDate === $today 
            && $time->getToTime() 
            && $nowTime > $time->getToTime()
        ) {
            return false;
        }

        if ($fromDate 
            && $toDate 
            && $fromDate === $toDate 
            && ($time->getFromTime() || $time->getToTime())
        ) {
            $from = $time->getFromTime() ?: '00:00:00';
            $to = $time->getToTime() ?: '23:59:59';
            if (!($from <= $nowTime && $nowTime <= $to)) {
                return false;
            }
        }

        return $result;
    }
}
