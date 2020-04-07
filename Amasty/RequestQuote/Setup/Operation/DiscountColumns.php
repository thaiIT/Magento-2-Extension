<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Setup\Operation;

use Amasty\RequestQuote\Api\Data\QuoteInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;

class DiscountColumns
{
    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $setup->startSetup();
        $table = $setup->getTable('requestquote');

        if (!$setup->getConnection()->tableColumnExists($table, QuoteInterface::DISCOUNT)) {
            $setup->getConnection()->addColumn(
                $table,
                QuoteInterface::DISCOUNT,
                [
                    'type' => Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length'   => '4,2',
                    'comment' => 'Discount applied for all items'
                ]
            );
        }
        if (!$setup->getConnection()->tableColumnExists($table, QuoteInterface::SURCHARGE)) {
            $setup->getConnection()->addColumn(
                $table,
                QuoteInterface::SURCHARGE,
                [
                    'type' => Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length'   => '4,2',
                    'comment' => 'Surcharge applied for all items'
                ]
            );
        }

        $setup->endSetup();
    }
}
