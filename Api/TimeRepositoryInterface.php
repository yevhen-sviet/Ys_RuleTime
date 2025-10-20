<?php
/**
 * Created by Yevhen Sviet
 */
namespace Ys\RuleTime\Api;

interface TimeRepositoryInterface
{
    public function getByRuleId(int $ruleId): ?\stdClass;
    public function saveTimes(int $ruleId, string $fromTime, string $toTime): void;
    public function deleteIfExists(int $ruleId): void;
}