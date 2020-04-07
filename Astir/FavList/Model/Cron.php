<?php
namespace Astir\FavList\Model;

use Astir\FavList\Model\FavListFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Cron
{
	protected $_listFactory;
	protected $_customerFactory;
	protected $_transportBuilder;
	protected $_storeManager;
	protected $_logger;

	public function __construct(FavListFactory $listFactory, CustomerFactory $customerFactory, TransportBuilder $transportBuilder,StoreManagerInterface $storeManager, LoggerInterface $logger) {
		$this->_listFactory = $listFactory;
		$this->_customerFactory = $customerFactory;
		$this->_transportBuilder = $transportBuilder;
		$this->_storeManager = $storeManager;
		$this->_logger = $logger;
	}

 	/**
	 * Send reminder email notification
	*/
 	public function getcronreminder() 
 	{
 		$currentDateTime = date('Y-m-d');
 		$amsLists = $this->_listFactory->create()->getCollection()
				 		->addFieldToFilter('to_send', ['notnull'=>'notnull'])
				 		->addFieldToFilter('send_reminder', ['from'=>'1', 'to'=>'4']);
 		foreach ($amsLists as $amsList)
 		{
 			$send_reminder = '';
 			if($amsList['send_reminder'] == 1){
 				$send_reminder = 1;
 			}
 			if($amsList['send_reminder'] == 2){
 				$send_reminder = 7;
 			}
 			if($amsList['send_reminder'] == 3){
 				$send_reminder = 15;
 			}
 			if($amsList['send_reminder'] == 4){
 				$send_reminder = 30;
 			}

 			$diff = strtotime($amsList['to_send']) - strtotime($currentDateTime);
 			$days = floor($diff / (60*60*24));
 			$checkdays = $days / $send_reminder;

 			if(($amsList['to_send'] == $currentDateTime) && $checkdays)
 			{
 				$customer = $this->_customerFactory->create()->load($amsList->getCustomerId());

 				$recepientName = $customer['firstname'].' '.$customer['lastname'];
 				$recepientEmail = $customer['email'];
				$receiverInfo = [
		            'name' => $recepientName,
		            'email' => $recepientEmail
		        ];
		        
		        $store = $this->_storeManager->getStore();
		        $templateParams = ['store' => $store, 'customer' => $customer, 'administrator_name' => $receiverInfo['name']];

		        $transport = $this->_transportBuilder->setTemplateIdentifier('favlist_transactional_email_reminder')
		        					->setTemplateOptions(['area' => 'frontend', 'store' => $store->getId()])
		        					->addTo($receiverInfo['email'], $receiverInfo['name'])
		        					->setTemplateVars($templateParams)
		        					->setFrom('general')
		        					->getTransport();
		        try {
		            $transport->sendMessage();
		        } catch (\Exception $e) {
		            $this->_logger->critical($e->getMessage());
		        }

 			}
 		}
 	}
 }