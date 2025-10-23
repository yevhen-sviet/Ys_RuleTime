<?php
/**
 * Created by Yevhen Sviet
 */
namespace Ys\RuleTime\Api;

use Ys\RuleTime\Api\Data\TimeInterface;

interface TimeRepositoryInterface
{
    /**
     * @param TimeInterface $entity
     * @return TimeInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(TimeInterface $entity): TimeInterface;

    /**
     * @param int $ruleId
     * @return TimeInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $ruleId): ?TimeInterface;

    /**
     * @param int $ruleId
     * @return TimeInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByRuleId(int $ruleId): ?TimeInterface;

    /**
     * @param TimeInterface $entity
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(TimeInterface $entity): bool;

    /**
     * @param int $ruleId
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $ruleId): bool;
}