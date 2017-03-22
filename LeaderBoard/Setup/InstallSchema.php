<?php
 
namespace AppName\RewardPoints\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
 
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
 
        // Get AppName Reward Points table
        $tableName = $installer->getTable('appname_rewardpoints');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            // Create appname_rewardpoints table
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id_reward_points',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'customer_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'nullable' => false
                    ],
                    'ID Customer'
                    )
                ->addColumn(
                    'reward_points',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'reward_points'
                )
                ->addColumn(
                    'date_activity',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Date Activity'
                )
                ->addColumn(
                    'status_points',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'default' => 0],
                    'Status Points'
                )
                ->addColumn(
                    'status_note',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Status Note'
                )
                ->setComment('Reward Points Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
 
        $installer->endSetup();
    }
}