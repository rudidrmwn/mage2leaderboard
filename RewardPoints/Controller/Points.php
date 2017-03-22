<?php
 
namespace AppName\RewardPoints\Controller;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;
use AppName\RewardPoints\Helper\Data;
use AppName\RewardPoints\Model\PointsFactory;
 
 abstract class Points extends \Magento\Framework\App\Action\Action
{
   /**
    * @var \Magento\Framework\View\Result\PageFactory
    */
   protected $_pageFactory;
 
   /**
    * @var \AppName\RewardPoints\Helper\Data
    */
   protected $_dataHelper;
 
   /**
    * @var \AppName\RewardPoints\Model\PointsFactory
    */
   protected $_pointsFactory;
 
   /**
    * @param Context $context
    * @param PageFactory $pageFactory
    * @param Data $dataHelper
    * @param PointsFactory $pointsFactory
    */
   public function __construct(
      Context $context,
      PageFactory $pageFactory,
      Data $dataHelper,
      PointsFactory $pointsFactory
   ) {
      parent::__construct($context);
      $this->_pageFactory = $pageFactory;
      $this->_dataHelper = $dataHelper;
      $this->_pointsFactory = $pointsFactory;
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