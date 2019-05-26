<?php

namespace MGS\RealReview\Plugin\Admin\Edit;
class Form extends \Magento\Review\Block\Adminhtml\Edit\Form
{
    protected $_editor;
    protected $_dataHelper;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Review\Helper\Data $reviewData,
        \Magento\Cms\Model\Wysiwyg\Config $editor,
        \MGS\RealReview\Helper\Data $dataHelper,
        array $data = [])
    {
        $this->_editor = $editor;
        $this->_dataHelper = $dataHelper;
        parent::__construct($context, $registry, $formFactory, $systemStore, $customerRepository, $productFactory, $reviewData, $data);
    }

    public function beforeSetForm(\Magento\Review\Block\Adminhtml\Edit\Form $object, $form)
    {
        $review = $object->_coreRegistry->registry('review_data');
        if ($this->_dataHelper->isEnable()) {
            $fieldset = $form->addFieldset(
                'review_details_extra',
                ['legend' => __('Review Answer'), 'class' => 'fieldset-wide-image']
            );
            $editorConfig = $this->_editor->getConfig(['add_variables' => false,'add_widgets' => false,'add_directives' => true]);
            $fieldset->addField(
                'review_answer',
                'editor',
                ['label' => __('Answer'), 'required' => false, 'name' => 'review_answer', 'style' => 'height:24em;', 'config' => $editorConfig ]
            );
        }

        $form->setValues($review->getData());
        return [$form];
    }
}