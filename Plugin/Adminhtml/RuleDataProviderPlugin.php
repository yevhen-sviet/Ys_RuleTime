<?php
/**
 * Created by Yevhen Sviet
 */
namespace Ys\RuleTime\Plugin\Adminhtml;

use Magento\SalesRule\Model\Rule\DataProvider;
use Ys\RuleTime\Api\TimeRepositoryInterface;
use Ys\RuleTime\Helper\Data as Helper;

class RuleDataProviderPlugin
{
    /**
     * @param TimeRepositoryInterface $repository
     * @param Helper $helper
     */
    public function __construct(
        private TimeRepositoryInterface $repository,
        private Helper $helper) 
    {
    }

    /**
     * @param DataProvider $subject
     * @param array $result
     * @return array
     */
    public function afterGetData(DataProvider $subject, array $result): array
    {
        $enabled = (int)$this->helper->isEnabled(null);
        foreach ($result as $ruleId => &$ruleData) {
            $ruleData['rule']['ys_ruletime_enabled'] = $enabled;
            $time = $this->repository->getByRuleId((int)$ruleId);
            if (!$time) {
                continue;
            }
            $ruleData['rule']['ys_ruletime_from_time'] = $time->getFromTime() 
                ? substr($time->getFromTime(), 0, 5) : null;
            $ruleData['rule']['ys_ruletime_to_time'] = $time->getToTime() 
                ? substr($time->getToTime(), 0, 5) : null;
        }

        return $result;
    }
}