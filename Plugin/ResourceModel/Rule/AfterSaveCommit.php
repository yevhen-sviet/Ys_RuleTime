<?php
/**
 * Created by Yevhen Sviet
 */
namespace Ys\RuleTime\Plugin\ResourceModel\Rule;

use Magento\Framework\Model\AbstractModel;
use Magento\SalesRule\Model\ResourceModel\Rule as RuleResource;
use Ys\RuleTime\Api\Data\TimeInterfaceFactory;
use Ys\RuleTime\Api\TimeRepositoryInterface;
use Ys\RuleTime\Helper\Data as Helper;
use Magento\Framework\App\RequestInterface;

class AfterSaveCommit
{
    /**
     * @param TimeRepositoryInterface $repo
     * @param TimeInterfaceFactory $timeFactory
     * @param Helper $helper
     * @param RequestInterface $request
     */
    public function __construct(
        private TimeRepositoryInterface $repo,
        private TimeInterfaceFactory $timeFactory,
        private Helper $helper,
        private RequestInterface $request
    ) {}

    /**
     * @param RuleResource $subject
     * @param mixed $result
     * @param AbstractModel $object
     * @return mixed
     */
    public function afterSave(RuleResource $subject, $result, AbstractModel $object)
    {
        if (!$this->helper->isEnabled(null)) {
            return $result;
        }

        $ruleId = (int)$object->getId();
        if ($ruleId <= 0) {
            return $result;
        }
        
        $ruleParams = (array)$this->request->getParam('rule', []);
        $from = trim((string)($ruleParams['ys_ruletime_from_time'])) ?? '';
        $to = trim((string)($ruleParams['ys_ruletime_to_time'])) ?? '';

        $validate = static function (?string $value): ?string {
            if (!$value) {
                return null;
            }
            if (preg_match('/^\d{1,2}:\d{2}$/', $value)) {
                return $value . ':00';
            }
            if (preg_match('/^\d{1,2}:\d{2}:\d{2}$/', $value)) {
                return $value;
            }
            return null;
        };
        $from = $validate($from);
        $to = $validate($to);

        $entity = $this->timeFactory->create()
            ->setRuleId($ruleId)
            ->setFromTime($from ?? '00:00:00')
            ->setToTime($to ?? '23:59:59');
        $this->repo->save($entity);

        return $result;
    }
}
