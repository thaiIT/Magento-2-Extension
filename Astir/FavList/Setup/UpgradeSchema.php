<?php
namespace Astir\FavList\Setup;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if(version_compare($context->getVersion(), '1.0.3', '<')) {
            $installer->getConnection()->addColumn(
                $installer->getTable( 'amlist_item' ),
                'parent_id',
                [
                    'type' => Table::TYPE_INTEGER,
                    'nullable' => false,
                    'unsigned' => true,
                    'default' => 0,
                    'comment' => 'Amlist Item Parent Id',
                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable( 'amlist_item' ),
                'sort_order',
                [
                    'type' => Table::TYPE_INTEGER,
                    'nullable' => false,
                    'unsigned' => true,
                    'default' => 0,
                    'comment' => 'Amlist Item Sort Order',
                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable( 'amlist_list' ),
                'send_reminder',
                [
                    'type' => Table::TYPE_INTEGER,
                    'nullable' => false,
                    'unsigned' => true,
                    'default' => 0,
                    'comment' => 'Amlist List Send Reminder',
                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable( 'amlist_list' ),
                'from_send',
                [
                    'type' => Table::TYPE_DATETIME,
                    'comment' => 'Amlist List From Send',

                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable( 'amlist_list' ),
                'to_send',
                [
                    'type' => Table::TYPE_DATETIME,
                    'comment' => 'Amlist List To Send',
                ]
            );
        }
        $installer->endSetup();
    }
}