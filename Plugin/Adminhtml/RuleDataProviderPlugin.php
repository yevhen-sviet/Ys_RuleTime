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
        foreach ($result as $ruleId => &$ruleData) {
            $time = $this->repository->getByRuleId((int)$ruleId);
            if (!$time) {
                continue;
            }
            $ruleData['rule']['ys_ruletime_from_time'] = $time->from_time 
                ? substr($time->getFromTime(), 0, 5) : null;
            $ruleData['rule']['ys_ruletime_to_time'] = $time->to_time 
                ? substr($time->getFromTime(), 0, 5) : null;
        }

        return $result;
    }

    /**
     * @param DataProvider $subject
     * @param array $meta
     * @return array
     */
    public function afterGetMeta(DataProvider $subject, array $meta): array
    {
        $status = $this->helper->isEnabled(null);

        $fieldset = 'rule_information';
        $fields   = ['ys_ruletime_from_time', 'ys_ruletime_to_time', 'ys_ruletime_note'];

        foreach ($fields as $field) {
            if (isset($meta[$fieldset]['children'][$field]['arguments']['data']['config'])) {
                $config =& $meta[$fieldset]['children'][$field]['arguments']['data']['config'];
                $config['visible']  = $status;
                $config['disabled'] = !$status;
                if (!$status) {
                    $config['notice'] = __(
                        'Disabled by configuration: 
                        Stores > Configuration > Sales > Rule Time > General');
                }
            }
        }

        return $meta;
    }
}