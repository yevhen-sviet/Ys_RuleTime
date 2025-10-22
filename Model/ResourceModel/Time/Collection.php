<?php
/**
 * Created by Yevhen Sviet
 */
namespace Ys\RuleTime\Model\ResourceModel\Time;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Ys\RuleTime\Model\Time as Model;
use Ys\RuleTime\Model\ResourceModel\Time as ResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
