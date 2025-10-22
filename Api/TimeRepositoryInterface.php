<?php
/**
 * Created by Yevhen Sviet
 */
namespace Ys\RuleTime\Api;

use Ys\RuleTime\Api\Data\TimeInterface;

interface TimeRepositoryInterface
{
    public function save(TimeInterface $entity): TimeInterface;

    public function getById(int $ruleId): TimeInterface;

    public function getByRuleId(int $ruleId): TimeInterface;

    public function delete(TimeInterface $entity): bool;

    public function deleteById(int $ruleId): bool;
}