<?php
/**
 * Created by Yevhen Sviet
 */
namespace Ys\RuleTime\Api\Data;

interface TimeInterface
{
    /**#@+
     * DB field names
     */
    public const RULE_ID   = 'rule_id';
    public const FROM_TIME = 'from_time';
    public const TO_TIME   = 'to_time';
    /**#@-*/

    /**
     * @return int
     */
    public function getRuleId(): int;

    /**
     * @param int $ruleId
     * @return self
     */
    public function setRuleId(int $ruleId): self;

    /**
     * @return string|null
     */
    public function getFromTime(): ?string;
    
    /**
     * @param string|null $time
     * @return self
     */
    public function setFromTime(?string $time): self;

    /**
     * @return string|null
     */
    public function getToTime(): ?string;
    
    /**
     * @param string|null $time
     * @return self
     */
    public function setToTime(?string $time): self;
}
