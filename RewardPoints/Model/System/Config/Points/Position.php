<?php
 
namespace AppName\RewardPoints\Model\System\Config\Points;
 
use Magento\Framework\Option\ArrayInterface;
 
class Position implements ArrayInterface
{
    const LEFT      = 1;
    const RIGHT     = 2;
    const DISABLED  = 0;
 
    /**
     * Get positions of Reward Points block
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            self::LEFT => __('Left'),
            self::RIGHT => __('Right'),
            self::DISABLED => __('Disabled')
        ];
    }
}