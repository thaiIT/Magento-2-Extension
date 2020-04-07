<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace TH\ImportAttribute\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
* @codeCoverageIgnore
*/
class InstallData implements InstallDataInterface
{
    /**
     * Eav setup factory
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;
    protected $attributeManagement;

    /**
     * Init
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,\Magento\Eav\Model\AttributeManagement $attributeManagement)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeManagement = $attributeManagement;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        // $attributeGroup = 'General';
        // $attributes = [
        //     'clothing_material' => [
        //         'group'                         => $attributeGroup,
        //         'input'                         => 'select',
        //         'type'                          => 'int',
        //         'label'                         => 'Clothing Material',
        //         'visible'                       => true,
        //         'required'                      => false,
        //         'user_defined'                  => true,
        //         'searchable'                    => false,
        //         'filterable'                    => false,
        //         'comparable'                    => false,
        //         'visible_on_front'              => false,
        //         'visible_in_advanced_search'    => false,
        //         'is_html_allowed_on_front'      => false,
        //         'used_for_promo_rules'          => false,
        //         'source'                        => 'TH\ImportAttribute\Model\Attribute\Source\Material',
        //         'frontend_class'                => '',
        //         'global'                        =>  \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
        //         'unique'                        => false,
        //         'apply_to'                      => 'simple,grouped,configurable,downloadable,virtual,bundle'
        //     ]
        // ];


        // $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        // foreach ($attributes as $attribute_code => $attributeOptions) { 
        //     $eavSetup->addAttribute(
        //         \Magento\Catalog\Model\Product::ENTITY,
        //         $attribute_code,
        //         $attributeOptions
        //     );
        // }

        // foreach ($attributes as $attribute_code => $attributeOptions) {
        //     $this->attributeManagement->assign(
        //         'catalog_product',
        //         $attributeSet->getId(),
        //         $groupId,
        //         $attribute_code,
        //         $attributeSet->getCollection()->getSize() * 10
        //     );
        // }

        $attributes = [
            'my_custom_size_3' => [
                'group' => '',
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'user_defined' => true,
                'default' => '0',
                'label' => 'My Custom Size 3',
                'input' => 'select',
                'required' => true,
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'unique' => true,
                'is_used_in_grid' => true,
                'is_filterable_in_grid' => true,
                'visible' => true,
                'searchable' => true,
                'visible_in_advanced_search' => true,
                'comparable' => true,
                'filterable' => 2,
                'filterable_in_search' => true,
                'used_for_promo_rules'          => true,
                'is_html_allowed_on_front'      => true,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'used_for_sort_by' => true,
                'option' => [
                    'values' => [
                        'Option1',
                        'Option2',
                        'Option3',
                        'Option4',
                        'Option5',
                        'Option6'
                    ]
                ]
            ]
        ];
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        foreach ($attributes as $attribute_code => $attributeOptions) { 
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                $attribute_code,
                $attributeOptions
            );
        }
    }
}
