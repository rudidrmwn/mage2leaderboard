<?php
 
namespace AppName\LeaderBoard\Model\ResourceModel;
 
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
 
class LeaderBoardModel extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('appname_rewardpoints', 'id_reward_points');
    }
}