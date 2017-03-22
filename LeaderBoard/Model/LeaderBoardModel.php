<?php
 
namespace AppName\LeaderBoard\Model;
 
use Magento\Framework\Model\AbstractModel;
 
class LeaderBoardModel extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('AppName\LeaderBoard\Model\ResourceModel\LeaderBoardModel');
    }
}