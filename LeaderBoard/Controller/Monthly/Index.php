<?php
 
namespace AppName\LeaderBoard\Controller\Monthly;
 
use AppName\LeaderBoard\Controller\LeaderBoardController;
 
class Index extends LeaderBoardController
{
    public function execute()
    {
	    $pageFactory = $this->_pageFactory->create();

         $pageFactory->getConfig()->getTitle()->set(
            $this->_dataHelper->getHeadTitle()
        );
        
        // Add breadcrumb
        /** @var \Magento\Theme\Block\Html\Breadcrumbs */
        $breadcrumbs = $pageFactory->getLayout()->getBlock('breadcrumbs');
       //  echo "title page = ".$this->_dataHelper->getHeadTitle();
       // var_dump($pageFactory->getLayout()->getBlock('breadcrumbs'));
       //  exit();
        
        $breadcrumbs->addCrumb('home',
            [
                'label' => __('Home'),
                'title' => __('Home'),
                'link' => $this->_url->getUrl('')
            ]
        );
        $breadcrumbs->addCrumb('leaderboard',
            [
                'label' => __('Leader Board'),
                'title' => __('Leader Board')
            ]
        );

        return $pageFactory;
    }
}