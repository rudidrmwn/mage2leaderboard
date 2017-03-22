<?php
 
namespace AppName\RewardPoints\Block;

use Magento\Framework\View\Element\Template;
use AppName\RewardPoints\Block\Points;
use AppName\RewardPoints\Helper\Data;
use AppName\RewardPoints\Model\PointsFactory;
use AppName\RewardPoints\Model\System\Config\Status;
use Magento\Framework\Controller\ResultFactory;

class Points extends Template
{
   /**
    * @var \AppName\RewardPoints\Helper\Data
    */
   protected $_dataHelper;
 
   /**
    * @var \AppName\RewardPoints\Model\PointsFactory
    */
   protected $_pointsFactory; 
 
   /**
    * @var Magento\Framework\App\ResourceConnection
    */

   protected $_resource;
   
   /**
    * @param Template\Context $context
    * @param Data $dataHelper
    * @param PointsFactory $pointsFactory
    */

   public function __construct(Template\Context $context, Data $dataHelper,PointsFactory $pointsFactory, ResultFactory $resultFactory
   ) {
      $this->_dataHelper = $dataHelper;
      $this->_pointsFactory = $pointsFactory;
      $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $this->_resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
      $this->_resultFactory = $resultFactory;
      parent::__construct($context);

   }

   // Check customer has login or not login
    public function chkLoginCustomer(){
      $appContext = $this->_objectManager->get('Magento\Framework\App\Http\Context');
      
      $status = false;

      if( $isLoggedIn = $appContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH)) {
        $status = true;
      }else{
        $status= false;
      }
      return $status;
    }

    public function getSessionCustomerID(){
      if(!empty(@$_SESSION['customer_base'])){
        $customerID=@$_SESSION['customer_base']['customer_id'];
        return $customerID;
      }else{
        $resultRedirect=$this->_resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('customer/account/login');
      } 
    }
    public function customerLogin(){
      
      if($this->chkLoginCustomer()==true){
        $customerID = @$_SESSION['customer_base']['customer_id'];
        $configPath = 'rewardpoints/general/registrationpoints';
        $scopeConfig = $this->_objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');

        $value =  $scopeConfig->getValue(
            $configPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        date_default_timezone_set("Asia/Jakarta");

        $reward_points=$value;
        $date_activity= date("Y-m-d h:i:s");
        $current_date= date("Y-m-d");
        $status_points=0;
        $status_note="Customer Login";
         
        //Define Daily Rewards Type
        $arrRewardType = array('dailyreward/general/sunday_reward_type','dailyreward/general/monday_reward_type','dailyreward/general/tuesday_reward_type','dailyreward/general/wednesday_reward_type','dailyreward/general/thursday_reward_type','dailyreward/general/friday_reward_type','dailyreward/general/saturday_reward_type');

        //Define Daily Rewards Value
        $arrRewardValue = array('dailyreward/general/sunday_reward_value','dailyreward/general/monday_reward_value','dailyreward/general/tuesday_reward_value','dailyreward/general/wednesday_reward_value','dailyreward/general/thursday_reward_value','dailyreward/general/friday_reward_value','dailyreward/general/saturday_reward_value');

        $currentDate=$mydate=getdate(date("U"));
        // echo "current date:"."$currentDate[weekday]";
        // exit();
        //Check customer login point per day
        $count = self::getNewCustomerPoints($customerID);
        if($count==0){
           $status_note="Registration";
           self::addPoints($customerID, $reward_points, $date_activity, $status_points, $status_note);
        }

        $currentDay = date("w");
        // echo "hari ini:".$currentDay;

        // print "<pre>";
        // print_r($arrRewardType);
        // print_r($arrRewardValue);
        // print "</pre>";
        
        //get Configuration 
        $configPathType=self::getDailyRewardType($customerID,$currentDay, $arrRewardType);
        $configPathValue=self::getDailyRewardValue($customerID,$currentDay,$arrRewardValue);
        
        //  //if value type is 1 (Points) and value type is 2 (Discount)
        // echo "valueType =".$configPathType."<br/>";
        // echo "dailyreward=".$configPathValue."<br/>";

        $dailyRewardType=$scopeConfig->getValue($configPathType,\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        // echo "type :".$dailyRewardType."<br/>";
        $dailyRewardValue=$scopeConfig->getValue($configPathValue,\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        // echo "value :".$dailyRewardValue;

          //check first when the customer has register and automatically login to get the points
          $chkSumPoints=self::getChkSumPoints($customerID, $current_date);
          $chkCustomerRegistered=self::getCustomerRegistration($customerID);

          //Daily Rewards after The Customer login
          switch($dailyRewardType){
            case 1:
                $countDailyRewardPoints=self::chkDailyReward($customerID,$current_date,'Daily Reward Points');
                if($countDailyRewardPoints==0){
                    //The Customer login get points
                    self::addPoints($customerID, $dailyRewardValue, $date_activity, $status_points, 'Daily Reward Points');
                }
            break;
            case 2:
                $countDailyRewardDiscount=self::chkDailyReward($customerID, $current_date,'Daily Reward Discount');
                if($countDailyRewardDiscount==0){
                    //The customer login get discount
                    self::addPoints($customerID, $dailyRewardValue, $date_activity, $status_points, 'Daily Reward Discount');
                }
            break;
          }
          
          if($chkSumPoints==0 && self::countCustomerLogin($customerID,$current_date)==0){
              //get summary points from reward points
              if($chkCustomerRegistered==0){
                  $getLastPoints=self::getSummaryPoints($customerID);
              }
              else{
                  $getLastPoints=$dailyRewardValue;
              }

              self::addSumPoints($customerID,$getLastPoints,$date_activity);
          }
          
           
      }
      elseif($this->chkLoginCustomer()==false){
          $resultRedirect = $this->_resultFactory->create(ResultFactory::TYPE_REDIRECT);
          $resultRedirect->setPath('customer/account/login');
      }else{}
    }

    //Get points when the customer has been login per day
    public function addPoints($customerID, $reward_points, $date_activity, $status_points, $status_note){
      
      $current_date=date("Y-m-d");
      $connection= $this->_resource->getConnection();

      $tableName = $connection->getTableName('AppName_rewardpoints');
      $insertPoints = "INSERT INTO $tableName (customer_id, reward_points, date_activity,status_points,status_note) VALUES ($customerID, $reward_points, '".$date_activity."',$status_points, '".$status_note."')";
      $connection->query($insertPoints);   

      //get summary points from reward points
      $getSummaryPoints=self::getSummaryPoints($customerID);
      if(self::getCustomerRegistration($customerID)>0){
          $rewardPoints=$reward_points;
      }else{
          $rewardPoints=$getSummaryPoints;
      }
      self::addSumPoints($customerID,$rewardPoints,$date_activity);    
      
     return true;
    }

    //Calculate to get count of the customer login per day
    public function countCustomerLogin($customerID, $current_date){
         
      $connection= $this->_resource->getConnection();

      $tableName = $connection->getTableName('AppName_rewardpoints');
      
      $select = $connection->select()->from(
          $tableName,
          "COUNT(*)"
      )->where("{$tableName}.date_activity LIKE ?",$current_date. '%'
      )->where("{$tableName}.status_points = ?",0
      )->where("{$tableName}.customer_id = ?",(int)$customerID);

      $result = (int)$connection->fetchOne($select);
  
      return $result;
    }

    //Get customer points by customer id
    public function getCustomerPoints($customerID){  
      $connection=$this->_resource->getConnection();
      $tableName=$connection->getTableName('AppName_summary_points');
      $select=$connection->select()->from(
        $tableName,"last_reward_points")
      ->where("customer_id=?",$customerID)
      ->order("id_summary_points DESC")
      ->limit(1);
        
        $result=(int)$connection->fetchOne($select);
        
        return $result;
    }

    //Get customer detail points in datagrid listing 
    public function getDetailPoints($customerID){
        
        $connection=$this->_resource->getConnection();
        $tableName=$connection->getTableName('AppName_rewardpoints');

        $select=$connection->select()->from(
          $tableName,"*"
        )->where("{$tableName}.customer_id=?",(int)$customerID)->order("date_activity DESC");
        $result=$connection->fetchAll($select);
        return $result;
    }

    //Get the first registration from table customer
    public function getCustomerRegistration($customerID){
        $connection=$this->_resource->getConnection();
        $tableName=$connection->getTableName('customer_entity');

        $select="SELECT COUNT(*) as Customer_Register FROM $tableName WHERE entity_id=$customerID AND is_active=1";
        $result=$connection->fetchOne($select);
        return $result;
    }
    
    // check when the new customer got points in the table reward points
    public function getNewCustomerPoints($customerID){
         $connection=$this->_resource->getConnection();
         $tableName=$connection->getTableName('AppName_rewardpoints');

         $select = $connection->select()->from(
            $tableName,
            "COUNT(*)"
        )->where(
            "{$tableName}.status_note = ?",'Registration'
        )->where(
            "{$tableName}.customer_id = ?",
            (int)$customerID
        );

        $result = (int)$connection->fetchOne($select);
        return $result;
    }

    //check the sale order status is complete by customer id  
    public function getCustomerFirstPurchased($customerID){
        $connection=$this->_resource->getConnection();
        $tableName=$connection->getTableName('sales_order');

        $select=$connection->select()->from(
          $tableName,"COUNT(*)"
        )->where("{$tableName}.status=?",'complete'
        )->where("{$tableName}.customer_id=?", (int)$customerID);
       
        $result = (int)$connection->fetchOne($select);
        return $result;
    }

    //checking the reward points when the customer got the points of first purchased
    public function chkCustomerFirstPurchased($customerID){
        $connection=$this->_resource->getConnection();
        $tableName=$connection->getTableName('AppName_rewardpoints');

        $select=$connection->select()->from(
          $tableName,"COUNT(*)"
        )->where("{$tableName}.status_note=?",'First Purchased'
        )->where("{$tableName}.customer_id=?", (int)$customerID);
       
        $result = (int)$connection->fetchOne($select);
        return $result;
    }

    //add points when the customer has been purchased and the sales order status is completed  
    public function firstPurchasedPoints($customerID){
        $configPath = 'rewardpoints/general/firstpurchasedpoints';
        $scopeConfig = $this->_objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');

        $value =  $scopeConfig->getValue(
            $configPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $reward_points=$value;
        date_default_timezone_set("Asia/Jakarta");
        $date_activity= date("Y-m-d h:i:s");
        $status_points=0;
        $status_note="First Purchased";
        $countFP=self::getCustomerFirstPurchased($customerID);
        $countRP=self::chkCustomerFirstPurchased($customerID);//Reward Points
        if($countFP==1){
          if($countRP==0){
            self::addPoints($customerID, $reward_points, $date_activity, $status_points, $status_note);
          }
        }
    }

    //add points when the customer has shared to social media
    public function socialMediaShared($customerID, $status_note){
      $configPath = 'rewardpoints/general/socialmedia';
      $scopeConfig = $this->_objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');

        $value = $scopeConfig->getValue($configPath);

        $rewardpoints=$value;
        date_default_timezone_set("Asia/Jakarta");
        $date_activity=date("Y-m-d h:i:s");
        $status_points=0;
        $countSocmedShared=self::chkSocialMediaShared($customerID,$status_note);
        
        //checks if the customer already shared in current day
        if($countSocmedShared==0){ 
            //add points when the customer shared the points to social media
            self::addPoints($customerID, $rewardpoints, $date_activity, $status_points, $status_note);
        }
    }

    //checking the customer has shared in current day
    public function chkSocialMediaShared($customerID, $status_note){
        $connection=$this->_resource->getConnection();
        $tableName=$connection->getTableName('AppName_rewardpoints');
        date_default_timezone_set("Asia/Jakarta");
        $current_date=date("Y-m-d");

        $select=$connection->select()->from($tableName,"COUNT(*)")->where("{$tableName}.status_note=?",$status_note)->where("{$tableName}.customer_id=?",(int)$customerID)->where("{$tableName}.date_activity LIKE '?%'",$current_date);
        $result=(int)$connection->fetchOne($select);
        return $result;     
    }

     //checking the reward points when the customer got the points of first purchased
    protected function chkDailyReward($customerID,$current_date,$status_note){
        $connection=$this->_resource->getConnection();
        $tableName=$connection->getTableName('AppName_rewardpoints');

        $select=$connection->select()->from(
          $tableName,"COUNT(*)"
        )->where("{$tableName}.status_note=?",$status_note
        )->where("{$tableName}.customer_id=?", (int)$customerID
        )->where("{$tableName}.date_activity LIKE ?",$current_date."%");
       
        $result = (int)$connection->fetchOne($select);
        return $result;
    }

    protected function getDailyRewardType($customerID,$currentDay, $dailyreward_type=array()){
        //numeric in day (0 is sunday till 6 is saturday) 
        foreach ($dailyreward_type as $TypeKey => $TypeValue) {
           if($TypeKey==$currentDay){
              $dailyRewardType= $TypeValue;
           }
        }

        return $dailyRewardType;
    }

    protected function getDailyRewardValue($customerID,$currentDay, $dailyreward_value=array()){
        //numeric in day (0 is sunday till 6 is saturday) 
        
        foreach ($dailyreward_value as $ValueKey => $DailyValue) {
           if($ValueKey==$currentDay){
              $dailyRewardValue= $DailyValue;
           }
        }

        return $dailyRewardValue;
    }

    protected function addSumPoints($customerID, $reward_points, $current_date,$points_used=null,$voucher=null,$voucher_image=null){
      $summaryPoints=self::getSummaryPoints($customerID);
       
       if(self::getCustomerRegistration($customerID)>0){
          $last_reward_points=($summaryPoints+$reward_points);
        }else{
          $last_reward_points=$summaryPoints;
        }
            
        $connection= $this->_resource->getConnection();
        $tableName = $connection->getTableName('AppName_summary_points');
        $summaryPoints = "INSERT INTO $tableName (last_reward_points, points_used, voucher,customer_id,date_sum_activity,status,voucher_image) VALUES ('$last_reward_points', '$points_used', '$voucher',$customerID, '$current_date',0,'$voucher_image')";
        $connection->query($summaryPoints);       
    }


    protected function getChkSumPoints($customerID,$current_date){
        $connection=$this->_resource->getConnection();
        $tableName=$connection->getTableName('AppName_summary_points');

        $select=$connection->select()->from(
            $tableName,"COUNT(*)"
        )->where("{$tableName}.customer_id=?",(int)$customerID
        )->where("{$tableName}.date_sum_activity LIKE ?",$current_date."%");
        $result=(int)$connection->fetchOne($select);
        return $result;
    }

    protected function getSumLastPoints($customerID){
      $connection=$this->_resource->getConnection();
        $tableName=$connection->getTableName('AppName_summary_points');
        $select=$connection->select()->from(
        $tableName,"last_reward_points")
          ->where("customer_id=?",$customerID)
          ->order("id_summary_points DESC")
          ->limit(1);
          $result=$connection->fetchOne($select);
          return $result;
    }

    protected function getSummaryPoints($customerID){
      $chkPoints=self::chkPoints($customerID);
      if(!empty($customerID) && $chkPoints==0){
        $connection=$this->_resource->getConnection();
        $tableName=$connection->getTableName('AppName_rewardpoints');
        $select=$connection->select()->from(
          $tableName,"SUM(reward_points)")
        ->where("status_points=?",0)
        ->where("customer_id=?",$customerID);
         
        $result=$connection->fetchOne($select);
        return $result;
      }else{
        $lastPoints=self::getSumLastPoints($customerID);
        return $lastPoints;
      }  
    }

     protected function chkPoints($customerID){
        $connection=$this->_resource->getConnection();
        $tableName=$connection->getTableName('AppName_summary_points');

        $select=$connection->select()->from(
            $tableName,"COUNT(*)"
        )->where("{$tableName}.customer_id=?",(int)$customerID);
        $result=(int)$connection->fetchOne($select);
        return $result;
    }
}