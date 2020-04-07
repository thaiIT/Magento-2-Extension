<?php
namespace THAIHOANG\Staff\Setup;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend\Console\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $conn = $setup->getConnection();
        $tableName = $setup->getTable('staff');
        if (version_compare($context->getVersion(), '0.1.1') < 0) {
            $fullTextIndex=array("name","email","phone");
            $conn->addIndex(
                $tableName,
                $setup->getIdxName($tableName,$fullTextIndex,\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT),
                $fullTextIndex,
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }
        if (version_compare($context->getVersion(), '0.1.2', '<')) {
            if ($conn->isTableExists($tableName) == true) {
                // Declare data
                $columns = [
                    'profile' => [
                        'type' => Table::TYPE_TEXT,
                        'nullable' => false,
                        'comment' => 'profile',
                    ],
                ];

                foreach ($columns as $name => $value) {
                    $conn->addColumn($tableName, $name, $value);
                }

            }
        }

        $setup->endSetup();
    }
}