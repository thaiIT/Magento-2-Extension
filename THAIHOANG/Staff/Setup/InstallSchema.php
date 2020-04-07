<?php
namespace THAIHOANG\Staff\Setup;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface {
	public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
		$setup->startSetup();
		$conn = $setup->getConnection();
		$tableName = $setup->getTable('staff');
		if($conn->isTableExists($tableName) != TRUE) {
			$table = $conn->newTable($tableName);
			$column=[
				"id"=> [
					"type" => Table::TYPE_INTEGER,
					"size" => null,
					"options" => [
						"identity" => true,
						"usigned" => true,
						"nullable" => false,
						"primary" => true
					]
				],
				"name"=> [
					"type" => Table::TYPE_TEXT,
					"size" => 255,
					"options" => [
						"nullable" => false,
						"default" => ""
					]
				],
				"email"=> [
					"type" => Table::TYPE_TEXT,
					"size" => 255,
					"options" => [
						"nullable" => false,
						"default" => ""
					]
				],
				"phone"=> [
					"type" => Table::TYPE_TEXT,
					"size" => 255,
					"options" => [
						"nullable" => false,
						"default" => ""
					]
				],
				"position"=> [
					"type" => Table::TYPE_TEXT,
					"size" => 255,
					"options" => [
						"nullable" => false,
						"default" => ""
					]
				],
				"status"=> [
					"type" => Table::TYPE_BOOLEAN,
					"size" => null,
					"options" => [
						"nullable" => false,
						"default" => 0
					]
				],
				"avatar"=> [
					"type" => Table::TYPE_TEXT,
					"size" => 255,
					"options" => [
						"nullable" => false,
						"default" => ""
					]
				]
			];
			foreach ($column as $name => $value) {
				$table->addColumn(
					$name,
					$value['type'],
					$value['size'],
					$value['options']
				);
			}
			$table->setOption("charset","utf8");
			$conn->createTable($table);
		}
		$setup->endSetup();
	}
}