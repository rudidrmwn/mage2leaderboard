<?php
 
namespace AppName\LeaderBoard\Controller\Index;
 
use AppName\LeaderBoard\Controller\LeaderBoardController;
 
class View extends LeaderBoardController
{
    public function execute()
    {
	  $Id = $this->getRequest()->getParam('id_reward_points');
    // Get Reward Points data
        $points = $this->_attendanceFactory->create()->load($Id);
    // Save Reward Points data into the registry
        $this->_objectManager->get('Magento\Framework\Registry')
            ->register('pointsData', $points);
 
        $pageFactory = $this->_pageFactory->create();

        // Add title
        $pageFactory->getConfig()->getTitle()->set($points->getTitle());
 
        // Add breadcrumb
        /** @var \Magento\Theme\Block\Html\Breadcrumbs */
        $breadcrumbs = $pageFactory->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb('home',
            [
                'label' => __('Home'),
                'title' => __('Home'),
                'link' => $this->_url->getUrl('')
            ]
        );
        $breadcrumbs->addCrumb('customer login',
            [
                'label' => __('LeaderBoard'),
                'link' => $this->_url->getUrl('leaderboard')
            ]
        );
        $breadcrumbs->addCrumb('leaderboard',
            [
                'label' => $pageFactory->getTitle(),
                'title' => $pageFactory->getTitle()
            ]
        );
 
        return $pageFactory;
    }
}