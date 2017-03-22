<?php
 
namespace AppName\RewardPoints\Block\Points;
 
use AppName\RewardPoints\Block\Points;
use AppName\RewardPoints\Model\System\Config\Points\Position;
 
class Right extends Points
{
   public function _construct()
   {
      $position = $this->_dataHelper->getPointsBlockPosition();
      // Check this position is applied or not
      if ($position == Position::RIGHT) {
         $this->setTemplate('AppName_RewardPoints::rewardpoints.phtml');
      }
   }
}