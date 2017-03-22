<?php
 
namespace AppName\LeaderBoard\Controller;

use Magento\Framework\App\Action\Context;
use Magento\Framework\ObjectManager\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;
use AppName\LeaderBoard\Helper\Data;
use AppName\LeaderBoard\Model\LeaderBoardModelFactory;
 
 abstract class LeaderBoardController extends \Magento\Framework\App\Action\Action
{
  
   /**
    * @var \Magento\Framework\View\Result\PageFactory
    */
   protected $_pageFactory;
 
 /**
    * @var \AppName\LeaderBoard\Helper\Data
    */
   protected $_dataHelper;
 
   /**
    * @var \AppName\LeaderBoard\Model\LeaderBoardModelFactory
    */
   protected $_leaderBoardFactory;
 
   /**
    * @param Context $context
    * @param PageFactory $pageFactory
    * @param Data $dataHelper
    * @param LeaderBoardModelFactory $leaderBoardFactory
    */
   public function __construct(
      Context $context,
      PageFactory $pageFactory,
      Data $dataHelper,
      LeaderBoardModelFactory $leaderBoardFactory
   ) {
      $this->_context = $context;
      $this->_pageFactory = $pageFactory;
      $this->_dataHelper = $dataHelper;
      $this->_leaderBoardFactory = $leaderBoardFactory;
      parent::__construct($context);
    }
 
   /**
     * Dispatch request
     *
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     */

   
    public function dispatch(RequestInterface $request)
    {
       // Check this module is enabled in frontend
      if ($this->_dataHelper->isEnabledInFrontend()) {
         $result = parent::dispatch($request);
          return $result;
      } else {
         $this->_forward('noroute');
      }
    }

}