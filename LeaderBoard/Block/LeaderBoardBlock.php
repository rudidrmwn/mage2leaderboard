<?php
 
namespace AppName\LeaderBoard\Block;

use Magento\Framework\View\Element\Template;
use AppName\LeaderBoard\Block\LeaderBoardBlock;
use AppName\LeaderBoard\Helper\Data;
use AppName\LeaderBoard\Model\LeaderBoardModelFactory;
use AppName\LeaderBoard\Model\System\Config\Status;
use Magento\Framework\Controller\ResultFactory;

class LeaderBoardBlock extends Template
{
   /**
    * @var \AppName\LeaderBoard\Helper\Data
    */
   protected $_dataHelper;
 
  
   /**
    * @var \AppName\LeaderBoard\Model\LeaderBoardModelFactory
    */
   protected $_leaderBoardFactory; 

   /**
    * @var Magento\Framework\App\ResourceConnection
    */

   protected $_resource;
   
   /**
    * @param Template\Context $context
    * @param Data $dataHelper
    */

   public function __construct(Template\Context $context,ResultFactory $resultFactory, LeaderBoardModelFactory $leaderBoardFactory
   ) {
      // $this->_dataHelper = $dataHelper;
      $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $this->_resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
      $this->_resultFactory = $resultFactory;
      $this->_leaderBoardFactory = $leaderBoardFactory;
      parent::__construct($context);

   }

  
  public function getHeadTitle(){
      $configPath = 'leaderboard/general/head_title';
      $scopeConfig = $this->_objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');

      $value =  $scopeConfig->getValue(
          $configPath,
          \Magento\Store\Model\ScopeInterface::SCOPE_STORE
      );
      return $value;
  }

  public function getDescription(){
      $configPath = 'leaderboard/general/description';
      $scopeConfig = $this->_objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');

      $value =  $scopeConfig->getValue(
          $configPath,
          \Magento\Store\Model\ScopeInterface::SCOPE_STORE
      );
      return $value;
  }

  public function getTopBuyerList(){
      
      $connection= $this->_resource->getConnection();

      $select = $connection->select()
          ->from(['a' => 'sales_order'],['CONCAT_WS(" ",b.firstname, b.lastname) AS CustomerName','SUM(a.total_paid) AS TotalPaid'])
          ->join(['b' => 'customer_address_entity'],'b.parent_id=a.customer_id')
          ->where('a.status =?', "complete")
          ->group('a.customer_id')
          ->order('TotalPaid DESC')
          ->limit('10');
      $data = $connection->fetchAll($select);

      return $data;
  }

  public function getTopBuyerToday(){
      $connection=$this->_resource->getConnection();
      $currentDate=date("Y-m-d");
      $select = $connection->select()
          ->from(['a' => 'sales_order'],['CONCAT_WS(" ",b.firstname, b.lastname) AS CustomerName','SUM(a.total_paid) AS TotalPaid'])
          ->join(['b' => 'customer_address_entity'],'b.parent_id=a.customer_id')
          ->where('a.updated_at LIKE ?',$currentDate.'%')
          ->where('a.status =?', "complete")
          ->group('a.customer_id')
          ->order('TotalPaid DESC')
          ->limit('10');
      $data = $connection->fetchAll($select);
      
      if(empty($data)){
        $data ="There is no data";
      }
      return $data;
  }

  public function getTopBuyerWeekly(){
      $connection=$this->_resource->getConnection();

      $select=$connection->select()->from(['a'=>'sales_order'], ['CONCAT_WS(" ",b.firstname,  b.lastname) AS CustomerName', 'SUM(a.total_paid) AS TotalPaid'])
          ->join(['b'=>'customer_address_entity'],'b.parent_id=a.customer_id')
          ->where('a.status=?','complete')
          ->where('DATE(a.updated_at)>=?','DATE_SUB(CURDATE(), INTERVAL 7 DAY)')
          ->group('a.customer_id')
          ->order('TotalPaid DESC')
          ->limit('10');
      $data=$connection->fetchAll($select);
      if(empty($data)){
        $data ="There is no data";
      }
      return $data;
  }

  public function getTopBuyerMonthly(){
      $connection=$this->_resource->getConnection();

      $select=$connection->select()->from(['a'=>'sales_order'],['CONCAT_WS(" ",b.firstname,b.lastname) AS CustomerName', 'SUM(a.total_paid) AS TotalPaid'])
      ->join(['b'=>'customer_address_entity'],'b.parent_id=a.customer_id')
      ->where('a.status=?','complete')
      ->where('DATE(a.updated_at)>=?','DATE_SUB(CURDATE(), INTERVAL 30 DAY)')
      ->group('a.customer_id')
      ->order('TotalPaid DESC')
      ->limit('10');
      $data=$connection->fetchAll($select);
      
      if(empty($data)){
        $data ="There is no data";
      }

      return $data;
  }
}