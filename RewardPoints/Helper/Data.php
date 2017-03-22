<?php
 
namespace AppName\RewardPoints\Helper;
 
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
 
class Data extends AbstractHelper
{
   const XML_PATH_ENABLED      = 'rewardpoints/general/enable_in_frontend';
   const XML_PATH_HEAD_TITLE   = 'rewardpoints/general/head_title';
   const XML_PATH_REWARD_POINTS = 'rewardpoints/general/latest_points_block_position';
 
   /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
 
    /**
     * 
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
        ) {
        $this->_scopeConfig = $scopeConfig;
      }
 
   /**
     * Check for module is enabled in frontend
     *
     * @return bool
     */
   public function isEnabledInFrontend($store = null)
   {
      return $this->_scopeConfig->getValue(
         self::XML_PATH_ENABLED,
         ScopeInterface::SCOPE_STORE
      );
   }
 
   /**
     * Get head title for reward points page
     *
     * @return string
     */
   public function getHeadTitle()
   {
      return $this->_scopeConfig->getValue(
         self::XML_PATH_HEAD_TITLE,
         ScopeInterface::SCOPE_STORE
      );
   }
 
   /**
     * Get latest points block position (Left, Right, Disabled)
     *
     * @return int
     */
   public function getLatestPointsBlockPosition()
   {
      return $this->_scopeConfig->getValue(
         self::XML_PATH_REWARD_POINTS,
         ScopeInterface::SCOPE_STORE
      );
   }
}