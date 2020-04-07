<?php

namespace MGS\FShippingBar\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Filesystem\DirectoryList;

class Data extends AbstractHelper
{   
    const FSHIPPING_GOAL = 'mgs_fshippingbar/general/fshipping_goal';
    const FONT_FAMILY = 'mgs_fshippingbar/design_fshipping/font';
    const FONT_SIZE = 'mgs_fshippingbar/design_fshipping/font_size';
    const FONT_WEIGHT = 'mgs_fshippingbar/design_fshipping/font_weight';
    const FONT_COLOR = 'mgs_fshippingbar/design_fshipping/font_color';
    const FONT_COLOR_OF_GOAL = 'mgs_fshippingbar/design_fshipping/font_color_of_goal';
    const BACKGROUND_COLOR = 'mgs_fshippingbar/design_fshipping/background_color';
    const TEXT_ALIGN = 'mgs_fshippingbar/design_fshipping/text_align';
    const CUSTOM_CSS = 'mgs_fshippingbar/design_fshipping/custom_css';
    const CUSTOMER_GROUP = 'mgs_fshippingbar/general/customer_groups';

    protected $_scopeConfig;
    protected $_request;
    protected $_filesystem;
    protected $_ioFile;
    
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigObject,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Io\File $ioFile
    ) {
        $this->_scopeConfig = $scopeConfigObject;
        $this->_request = $request;
        $this->_filesystem = $filesystem;
        $this->_ioFile = $ioFile;
    }

	public function getFshippingGoal(){
        return $this->_scopeConfig->getValue(
            self::FSHIPPING_GOAL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getFontFamily(){
        return $this->_scopeConfig->getValue(
            self::FONT_FAMILY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getFontSize(){
        return $this->_scopeConfig->getValue(
            self::FONT_SIZE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getFontWeight(){
        return $this->_scopeConfig->getValue(
            self::FONT_WEIGHT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getFontColor(){
        return $this->_scopeConfig->getValue(
            self::FONT_COLOR,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getFontColorOfGoal(){
        return $this->_scopeConfig->getValue(
            self::FONT_COLOR_OF_GOAL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getBackgroundColor(){
        return $this->_scopeConfig->getValue(
            self::BACKGROUND_COLOR,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getTextAlign(){
        return $this->_scopeConfig->getValue(
            self::TEXT_ALIGN,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getCustomCss() {
        return $this->_scopeConfig->getValue(
            self::CUSTOM_CSS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getConfig($configPath){
        return $this->_scopeConfig->getValue(
            $configPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getCustomerGroup() {
        return $this->_scopeConfig->getValue(
            self::CUSTOMER_GROUP,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getLinksFont(){
        $_font = $this->getFontFamily();
        $link = "@import url('//fonts.googleapis.com/css?family=" . $_font . ":300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,900,900italic');";
        return $link;
    }

    public function generateCss(){
        $html = '';
        $html .= $this->getLinksFont();
        $html .= '.fsb-header-mgs {';
        $html .= 'font-family:'.str_replace('+', ' ',$this->getFontFamily()).';';
        $html .= 'font-size:'.$this->getFontSize().'px;';
        $html .= 'font-weight:'.$this->getFontWeight().';';
        $html .= 'color:'.$this->getFontColor().';';
        $html .= 'background:'.$this->getBackgroundColor().';';
        $html .= 'text-align:'.$this->getTextAlign().';';
        $html .= 'padding: 10px;';
        $html .= '}';
        $html .= '.fsb-header-mgs .goal { color: '.$this->getFontColorOfGoal().';}';
        $html .= $this->getCustomCss();
        $this->generateFile($html);
        return;
    }
    public function generateFile($content) {
        $filePath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('freeshipingbar/css/');
        $io = $this->_ioFile;
        $file = $filePath . 'custom_config.css';
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $filePath));
        $io->write($file, $content, 0644);
        $io->streamClose();
    }

}