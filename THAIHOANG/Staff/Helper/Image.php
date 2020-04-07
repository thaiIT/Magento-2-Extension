<?php
namespace THAIHOANG\Staff\Helper;
use Magento\Framework\App\Filesystem\Directorylist;
class Image extends \Magento\Framework\App\Helper\AbstractHelper
{
	const MEDIA_PATH = 'staff';
	const MAX_FILE_SIZE = 1048576;
	const MIN_HEIGHT = 50;
	const MAX_HEIGHT = 800;
	const MIN_WIDTH = 50;
	const MAX_WIDTH = 1024;
	protected $_imageSize = array(
		'minheight' => self::MIN_HEIGHT,
		'minwidth' => self::MIN_WIDTH,
		'maxheight' => self::MAX_HEIGHT,
		'maxwidth' => self::MAX_WIDTH,
	);
	protected $mediaDirectory;
	protected $filesystem;
	protected $httpFactory;
	protected $_fileUploaderFactory;
	protected $_ioFile;
	protected $_storeManager;
	public function __construct(
		\Magento\Framework\App\Helper\Context $context,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Framework\Filesystem $filesystem,
		\Magento\Framework\File\Size $fileSize,
		\Magento\Framework\HTTP\Adapter\FileTransferFactory $httpFactory,
		\Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
		\Magento\Framework\Filesystem\Io\File $ioFile,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\Image\Factory $imageFactory
	){
		$this->_scopeConfig = $scopeConfig;
		$this->filesystem = $filesystem;
		$this->mediaDirectory = $filesystem->getDirectoryWrite(Directorylist::MEDIA);
		$this->httpFactory = $httpFactory;
		$this->_fileUploaderFactory = $fileUploaderFactory;
		$this->_ioFile = $ioFile;
		$this->_storeManager = $storeManager;
		$this->_imageFatory = $imageFactory;
		parent::__construct($context);
	}

	public function removeImage($imageFile)
	{
		$io = $this->_ioFile;
        $io->open(array('path' => $this->getBaseDir().'/../'));
        if ($io->fileExists($imageFile)) {
            return $io->rm($imageFile);
        }
        return false;
	}
	public function uploadImage($scope)
	{
		$adapter = $this->httpFactory->create();
		$adapter->addValidator(new \Zend_Validate_File_ImageSize($this->_imageSize));
		$adapter->addValidator(
            new \Zend_Validate_File_FilesSize(['max' => self::MAX_FILE_SIZE])
        );
        if($adapter->isUploaded($scope)) {
        	if(!$adapter->isValid($scope)) {
        		return false;
        	}
        	$uploader = $this->_fileUploaderFactory->create(['fileId' => $scope]);
        	$uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc','docx']);
        	$uploader->setAllowRenameFiles(true);
        	$uploader->setFilesDispersion(false);
        	$uploader->setAllowCreateFolders(true);
        	if($uploader->save($this->getBaseDir())) {
        		return $uploader->getUploadedFileName();
        	}
        }
        return false;
	}
	public function getBaseDir() {
		$path = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath(self::MEDIA_PATH);
        return $path;
	}
	public function getBaseUrl() {
		 return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . '/' . self::MEDIA_PATH;
	}
}