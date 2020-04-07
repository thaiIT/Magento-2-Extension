<?php

namespace Astir\FavList\Block\Adminhtml\FavList\Edit;

use Magento\Backend\Block\Widget\Form\Generic;

class Form extends Generic 
{
	protected function _construct() {
		$this->setId('amlist_form');
		$this->setTitle(__('Favourites List'));
		parent::_construct();
	}
	protected function _prepareForm() {
		$form = $this->_formFactory->create(
            [
                'data' => [
                    'id'    => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                ]
            ]
        );
	    $form->setHtmlIdPrefix('amlist_');
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
	}
}