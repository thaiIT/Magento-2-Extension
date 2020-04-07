<?php

namespace Astir\FavList\Setup;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface 
{
	public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
		$installer = $setup;
		$installer->startSetup();
		if (!$installer->tableExists('amlist_list')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('amlist_list')
			)
			->addColumn(
				'list_id',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				null,
				[
					'identity' => true,
					'primary'  => true,
					'nullable' => false,
					'unsigned' => true,
				],
				'Amlist List ID'
			)
			->addColumn(
				'title',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				255,
				['nullable => false'],
				'Amlist List Title'
			)
			->addColumn(
				'customer_id',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				10,
				[
					'nullable => false',
					'unsigned' => true,
					"default" => 0
				],
				'Amlist List Customer Id'
			)
			->addColumn(
				'created_at',
				\Magento\Framework\DB\Ddl\Table::TYPE_DATE,
				null,
				[
					'nullable' => false,
				],
				'Amlist List Created At'
			)
			->addColumn(
				'is_default',
				\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
				5,
				[
					'nullable => false',
				],
				'Amlist List Is Default'
			)
			->addForeignKey(
                $installer->getFkName(
                    'amlist_list',
                    'list_id',
                    'customer_entity',
                    'entity_id'
                ),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE,
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
			->setComment('FavList List Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('amlist_list'),
				$setup->getIdxName(
					$installer->getTable('amlist_list'),
					['title'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['title'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}
		if (!$installer->tableExists('amlist_item')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('amlist_item')
			)
			->addColumn(
				'item_id',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				null,
				[
					'identity' => true,
					'primary'  => true,
					'nullable' => false,
					'unsigned' => true,
				],
				'Amlist Item ID'
			)
			->addColumn(
				'list_id',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				null,
				[
					'nullable' => false,
					'unsigned' => true,
				],
				'Amlist List Id'
			)
			->addColumn(
				'product_id',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				10,
				[
					'nullable' => false,
					'unsigned' => true,
					"default" => 0
				],
				'Amlist Product Id'
			)
			->addColumn(
				'qty',
				\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
				5,
				[
					'nullable' => false,
					"default" => 0
				],
				'Amlist Item Qty'
			)
			->addColumn(
				'descr',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				null,
				['nullable' => false],
				'Amlist Item Description'
			)
			->addColumn(
				'buy_request',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				null,
				[
					'nullable' => false
				],
				'Amlist Item Buy Request'
			)
			->addForeignKey(
                $installer->getFkName(
                    'amlist_item',
                    'list_id',
                    'amlist_list',
                    'list_id'
                ),
                'list_id',
                $installer->getTable('amlist_list'),
                'list_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE,
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName(
                    'amlist_item',
                    'product_id',
                    'catalog_product_entity',
                    'entity_id'
                ),
                'product_id',
                $installer->getTable('catalog_product_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE,
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
			->setComment('FavList Amlist Item Table');
			$installer->getConnection()->createTable($table);
			$installer->getConnection()->addIndex(
				$installer->getTable('amlist_item'),
				$setup->getIdxName(
					$installer->getTable('amlist_item'),
					['descr','buy_request'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['descr','buy_request'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}
		$installer->endSetup();
	}
}