<?php
 
namespace AppName\LeaderBoard\Helper;
 
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
 
class Data extends AbstractHelper
{
   const XML_PATH_ENABLED      = 'leaderboard/general/enable_in_frontend';
   const XML_PATH_HEAD_TITLE   = 'leaderboard/general/head_title';
   const XML_PATH_LEADER_BOARD_DESC = 'leaderboard/general/description';
 
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
     * Get head title for leader board page
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
     * Get Leader Board Description
     *
     * @return int
     */
   public function getLeaderBoardDesc()
   {
      return $this->_scopeConfig->getValue(
         self::XML_PATH_LEADER_BOARD_DESC,
         ScopeInterface::SCOPE_STORE
      );
   }
}
