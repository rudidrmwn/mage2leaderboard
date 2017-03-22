<?php
 
namespace AppName\RewardPoints\Block\Points;
 
use AppName\RewardPoints\Block\Points;
use AppName\RewardPoints\Model\System\Config\Points\Position;
 
class Left extends Points
{
   public function _construct()
   {
      $position = $this->_dataHelper->getPointsBlockPosition();
      // Check this position is applied or not
      if ($position == Position::LEFT) {
         $this->setTemplate('AppName_RewardPoints::rewardpoints.phtml');
      }
   }
}