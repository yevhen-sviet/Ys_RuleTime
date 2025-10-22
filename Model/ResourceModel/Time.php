<?php
/**
 * Created by Yevhen Sviet
 */
namespace Ys\RuleTime\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Time extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('ys_salesrule_time', 'rule_id');
    }
}
