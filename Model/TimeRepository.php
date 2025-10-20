<?php
namespace Ys\RuleTime\Model;

use Magento\Framework\App\ResourceConnection;
use Ys\RuleTime\Api\TimeRepositoryInterface;

class TimeRepository implements TimeRepositoryInterface
{
    private const TABLE = 'ys_salesrule_time';

    public function __construct(private ResourceConnection $resource) {}

    public function getByRuleId(int $ruleId): ?\stdClass
    {
        $connection = $this->resource->getConnection();
        $select = $connection->select()
            ->from(self::TABLE, ['from_time','to_time'])
            ->where('rule_id = ?', $ruleId);
        $row = $connection->fetchRow($select);
        if (!$row) {
            return null;
        }
        $time = new \stdClass();
        $time->from_time = $row['from_time'];
        $time->to_time = $row['to_time'];

        return $time;
    }


    public function saveTimes(int $ruleId, string $fromTime, string $toTime): void
    {
        $connection = $this->resource->getConnection();
        $data = [
            'rule_id' => $ruleId,
            'from_time' => $fromTime,
            'to_time' => $toTime,
        ];
        $connection->insertOnDuplicate(self::TABLE, $data, ['from_time','to_time']);
    }


    public function deleteIfExists(int $ruleId): void
    {
        $this->resource->getConnection()
            ->delete(self::TABLE, ['rule_id = ?' => $ruleId]);
    }
}