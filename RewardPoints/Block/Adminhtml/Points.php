<?php
 
namespace AppName\RewardPoints\Block\Adminhtml;
 
use Magento\Backend\Block\Widget\Grid\Container;
 
class Points extends Container
{
   /**
     * Constructor
     *
     * @return void
     */
   protected function _construct()
    {
        $this->_controller = 'adminhtml_points';
        $this->_blockGroup = 'AppName_RewardPoints';
        $this->_headerText = __('Manage Reward Points');
        $this->_addButtonLabel = __('Add Points');
        parent::_construct();
    }
}