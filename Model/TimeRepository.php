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
    /**
     * @param TimeResource $resource
     * @param \Ys\RuleTime\Model\TimeFactory $factory
     */
    public function __construct(
        private TimeResource $resource,
        private \Ys\RuleTime\Model\TimeFactory $factory
    ) {}

    /**
     * @param TimeInterface $entity
     * @return TimeInterface
     * @throws CouldNotSaveException
     */
    public function save(TimeInterface $entity): TimeInterface
    {
        try {
            if ($this->getById($entity->getRuleId()) === null) {
                $connection  = $this->resource->getConnection();
                $table = $this->resource->getMainTable();

                $connection->insertOnDuplicate(
                    $table,
                    [
                        'rule_id'   => $entity->getRuleId(),
                        'from_time' => $entity->getFromTime(),
                        'to_time'   => $entity->getToTime(),
                    ],
                    ['from_time', 'to_time']
                );
                $model = $this->factory->create();
                $this->resource->load($model, $entity->getRuleId());
                return $model;
            }

            /** @var \Ys\RuleTime\Model\Time $model */
            $model = ($entity instanceof \Ys\RuleTime\Model\Time)
                ? $entity
                : $this->factory->create()->setData([
                    TimeInterface::RULE_ID   => $entity->getRuleId(),
                    TimeInterface::FROM_TIME => $entity->getFromTime(),
                    TimeInterface::TO_TIME   => $entity->getToTime()
                ]);
            $this->resource->save($model);
            return $model;
        } catch (\Throwable $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Could not save time entity: %1', $e->getMessage()),
                $e
            );
        }
    }

    /**
     * @param int $ruleId
     * @return TimeInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $ruleId): ?TimeInterface
    {
        $model = $this->factory->create();
        $this->resource->load($model, $ruleId);
        return $model->getId() ? $model : null;
    }

    /**
     * @param int $ruleId
     * @return TimeInterface
     * @throws NoSuchEntityException
     */
    public function getByRuleId(int $ruleId): ?TimeInterface
    {
        return $this->getById($ruleId);
    }

    /**
     * @param TimeInterface $entity
     * @return bool
     * @throws CouldNotDeleteException
     */
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

    /**
     * @param int $ruleId
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $ruleId): bool
    {
        return $this->delete($this->getById($ruleId));
    }
}
