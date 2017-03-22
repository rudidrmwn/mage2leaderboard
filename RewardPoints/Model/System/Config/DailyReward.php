<?php
/**
 * Select Options of Daily Reward
 *
 */
namespace AppName\RewardPoints\Model\System\Config;
class DailyReward implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('Choose One')],
            ['value' => '1', 'label' => __('Point')],
            ['value' => '2', 'label' => __('Discount')]
        ];
    }
}
 
?>