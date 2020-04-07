<?php

namespace Astir\FavList\Controller\Index;

use Magento\Framework\App\Action\Context;
use Astir\FavList\Model\FavListFactory;

class Reminder extends \Magento\Framework\App\Action\Action
{
    protected $_listFactory;

    public function __construct (Context $context, FavListFactory $listFactory){
        parent::__construct($context);
        $this->_listFactory=$listFactory;
    }
    
    public function execute()
    {
        $listId = $this->getRequest()->getParam('id');
        $listModel = $this->_listFactory->create();

        $data = $this->getRequest()->getPost();

        $send_reminder = '';
        if($data['reminder'] == 1){
            $send_reminder = 1;
        }
        if($data['reminder'] == 2){
            $send_reminder = 7;
        }
        if($data['reminder'] == 3){
            $send_reminder = 15;
        }
        if($data['reminder'] == 4){
            $send_reminder = 30;
        }

        $date_format = date("Y-m-d");
        $from = strtotime("$send_reminder day", strtotime($date_format));
        $to_send = date('Y-m-d H:i:s', $from);

        if ($send_reminder > 0) {
            try {
                $listModel->load($listId);
                $listModel->setToSend($to_send);
                $listModel->setSendReminder($data['reminder']);
                $listModel->save();
                if ( $send_reminder == 1 ) {
                    $this->messageManager->addSuccess(__('Reminder time will be sent after %1 day!', $send_reminder));
                } else {
                    $this->messageManager->addSuccess(__('Reminder time will be sent after %1 days!', $send_reminder));
                }
                
                return $this->_redirect('*/*/editList', array('id' => $listId));
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                // $this->messageManager->setListFormData($data);
                return $this->_redirect('*/*/editList', array('id' => $listId));
            }
        }

        $this->messageManager->addError(__('Please select a reminder time!'));
        return $this->_redirect('*/*/editList', array('id' => $listId));
    }
}