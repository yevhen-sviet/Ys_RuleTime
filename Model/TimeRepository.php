<?php
/**
 * Created by Yevhen Sviet
 */
namespace Ys\RuleTime\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Ys\RuleTime\Api\Data\TimeInterface;
use Ys\RuleTime\Api\TimeRepositoryInterface;
use Ys\RuleTime\Model\ResourceModel\Time as TimeResource;

class TimeRepository implements TimeRepositoryInterface
{
    public function __construct(
        private TimeResource $resource,
        private \Ys\RuleTime\Model\TimeFactory $factory
    ) {}

    public function save(TimeInterface $entity): TimeInterface
    {
        try {
            /** @var \Ys\RuleTime\Model\Time $model */
            $model = ($entity instanceof \Ys\RuleTime\Model\Time)
                ? $entity
                : $this->factory->create()->setData([
                    TimeInterface::RULE_ID   => $entity->getRuleId(),
                    TimeInterface::FROM_TIME => $entity->getFromTime(),
                    TimeInterface::TO_TIME   => $entity->getToTime(),
                ]);

            $this->resource->save($model);
            return $model;
        } catch (\Throwable $e) {
            throw new CouldNotSaveException(__('Could not save time entity: %1', $e->getMessage()), $e);
        }
    }

    public function getById(int $ruleId): TimeInterface
    {
        $model = $this->factory->create();
        $this->resource->load($model, $ruleId);
        if (!$model->getId()) {
            throw new NoSuchEntityException(__('Time entity with rule_id "%1" does not exist.', $ruleId));
        }
        return $model;
    }

    public function getByRuleId(int $ruleId): TimeInterface
    {
        return $this->getById($ruleId);
    }

    public function delete(TimeInterface $entity): bool
    {
        try {
            $model = ($entity instanceof \Ys\RuleTime\Model\Time)
                ? $entity
                : $this->getById($entity->getRuleId());
            $this->resource->delete($model);
            return true;
        } catch (\Throwable $e) {
            throw new CouldNotDeleteException(__('Could not delete time entity: %1', $e->getMessage()), $e);
        }
    }

    public function deleteById(int $ruleId): bool
    {
        return $this->delete($this->getById($ruleId));
    }
}
