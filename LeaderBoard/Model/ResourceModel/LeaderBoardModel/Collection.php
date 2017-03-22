<?php
 
namespace AppName\LeaderBoard\Model\ResourceModel\LeaderBoardModel;
 
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
 
class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'AppName\LeaderBoard\Model\LeaderBoardModel',
            'AppName\LeaderBoard\Model\ResourceModel\LeaderBoardModel'
        );
    }
}