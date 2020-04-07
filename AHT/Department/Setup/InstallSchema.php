<?php

namespace AHT\Department\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{
	public function install (SchemaSetupInterface $setup, ModuleContextInterface $context) 
	{
		$setup->startSetup();
		$departmentTable = $setup->getConnection()->newTable(
			$setup->getTable('aht_department')
		)->addColumn(
			'entity_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			null,
			['identity'=>true,'unsigned'=>true,'nullable'=>false,'primary'=>true],
			'Entity ID'
		)->addColumn(
			'name',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            [],
            'Name'
		)->setComment(
            'AHT Department Table'
        );
        $setup->getConnection()->createTable($departmentTable);
        $setup->endSetup();
	}
}