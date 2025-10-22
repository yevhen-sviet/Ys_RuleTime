<?php
/**
 * Created by Yevhen Sviet
 */
namespace Ys\RuleTime\Model;

use Magento\Framework\Model\AbstractModel;
use Ys\RuleTime\Api\Data\TimeInterface;
use Ys\RuleTime\Model\ResourceModel\Time as TimeResource;

class Time extends AbstractModel implements TimeInterface
{
    protected function _construct()
    {
        $this->_init(TimeResource::class);
        $this->setIdFieldName(self::RULE_ID);
    }

    public function getRuleId(): int
    {
        return (int)$this->_getData(self::RULE_ID);
    }

    public function setRuleId(int $ruleId): TimeInterface
    {
        return $this->setData(self::RULE_ID, $ruleId);
    }

    public function getFromTime(): ?string
    {
        $value = $this->_getData(self::FROM_TIME);
        return $value !== null && $value !== '' ? (string)$value : null;
    }

    public function setFromTime(?string $time): TimeInterface
    {
        return $this->setData(self::FROM_TIME, $time);
    }

    public function getToTime(): ?string
    {
        $value = $this->_getData(self::TO_TIME);
        return $value !== null && $value !== '' ? (string)$value : null;
    }

    public function setToTime(?string $time): TimeInterface
    {
        return $this->setData(self::TO_TIME, $time);
    }
}
