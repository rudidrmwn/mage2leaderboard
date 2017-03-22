<?php
 
namespace AppName\Points\Model;
 
use Magento\Framework\Model\AbstractModel;
 
class Points extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('AppName\RewardPoints\Model\ResourceModel\Points');
    }
}