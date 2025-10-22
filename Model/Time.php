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
    /**
     * Links resource model
     */
    protected function _construct()
    {
        $this->_init(TimeResource::class);
        $this->setIdFieldName(self::RULE_ID);
    }

    /**
     * @return int
     */
    public function getRuleId(): int
    {
        return (int)$this->_getData(self::RULE_ID);
    }

    /**
     * @param int $ruleId
     * @return TimeInterface
     */
    public function setRuleId(int $ruleId): TimeInterface
    {
        return $this->setData(self::RULE_ID, $ruleId);
    }

    /**
     * @return string|null
     */
    public function getFromTime(): ?string
    {
        $value = $this->_getData(self::FROM_TIME);
        return $value !== null && $value !== '' ? (string)$value : null;
    }

    /**
     * @param string|null $time
     * @return TimeInterface
     */
    public function setFromTime(?string $time): TimeInterface
    {
        return $this->setData(self::FROM_TIME, $time);
    }

    /**
     * @return string|null
     */
    public function getToTime(): ?string
    {
        $value = $this->_getData(self::TO_TIME);
        return $value !== null && $value !== '' ? (string)$value : null;
    }

    /**
     * @param string|null $time
     * @return TimeInterface
     */
    public function setToTime(?string $time): TimeInterface
    {
        return $this->setData(self::TO_TIME, $time);
    }
}
