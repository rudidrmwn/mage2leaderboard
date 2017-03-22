<?php
 
namespace AppName\RewardPoints\Controller\Index;
 
use AppName\RewardPoints\Controller\Points;
 
class View extends Points
{
    public function execute()
    {
	// Get Reward Point ID
        $Id = $this->getRequest()->getParam('id_reward_points');
	// Get Reward Points data
        $points = $this->_pointsFactory->create()->load($Id);
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
        $breadcrumbs->addCrumb('rewardpoints',
            [
                'label' => __('Rewards Points'),
                'title' => __('Rewards Points'),
                'link' => $this->_url->getUrl('points')
            ]
        );
        $breadcrumbs->addCrumb('points',
            [
                'label' => $news->getTitle(),
                'title' => $news->getTitle()
            ]
        );
 
        return $pageFactory;
    }
}