<?php
 
namespace AppName\RewardPoints\Controller\Index;
 
use AppName\RewardPoints\Controller\Points;
 
class Index extends Points
{
    public function execute()
    {
        $pageFactory = $this->_pageFactory->create();
 
        // Add title which is got by the configuration via backend
        $pageFactory->getConfig()->getTitle()->set(
            $this->_dataHelper->getHeadTitle()
        );
 
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
                'label' => __('Reward Points'),
                'title' => __('Reward Points')
            ]
        );

        return $pageFactory;
    }

}
 