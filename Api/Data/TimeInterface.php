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

    public function getRuleId(): int;
    public function setRuleId(int $ruleId): self;

    public function getFromTime(): ?string;
    public function setFromTime(?string $time): self;

    public function getToTime(): ?string;
    public function setToTime(?string $time): self;
}
