<?php
 
namespace AppName\RewardPoints\Model\ResourceModel\Points;
 
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
 
class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'AppName\RewardPoints\Model\Points',
            'AppName\RewardPoints\Model\ResourceModel\Points'
        );
    }
}