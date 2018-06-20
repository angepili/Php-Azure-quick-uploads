<?php

require_once 'vendor/autoload.php';
use WindowsAzure\Common\ServicesBuilder;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
use MicrosoftAzure\Storage\Common\ServiceException;


class Blob {

        function __construct($name){
            $this->accountName = ''; // account name
            $this->accountKey = ''; // account key
            $this->containerName = $name; // set name of container
        }
        
        // create container
		public function createCont() {
            $connectionString = "DefaultEndpointsProtocol=https;AccountName=".$this->accountName.";AccountKey=".$this->accountKey;
            
            $blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);
            
            $createContainerOptions = new CreateContainerOptions();
            
            $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
            
            try {
                $blobRestProxy->createContainer($this->containerName, $createContainerOptions);
            }
            catch(ServiceException $e){
                $code = $e->getCode();
                $error_message = $e->getMessage();
                echo $code.": ".$error_message."<br />";
            }
            
            return "Container created";
        }

        // upload blob
        public function createBlob($filepath){
            $connectionString = "DefaultEndpointsProtocol=https;AccountName=".$this->accountName.";AccountKey=".$this->accountKey;
            $blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);
            $file = $filepath;
            if(is_readable($file)){
                $content = fopen($file, "r");
            }else{
                return "File not found";
            }
            try {
                $blobRestProxy->createBlockBlob($this->containerName, $file, $content);
            }
            catch(ServiceException $e){
                $code = $e->getCode();
                $error_message = $e->getMessage();
                echo $code.": ".$error_message."<br />";
            }
        }

        // get blob list
        public function listBlobs(){
            try {
                $connectionString = "DefaultEndpointsProtocol=https;AccountName=".$this->accountName.";AccountKey=".$this->accountKey;
                $blobClient = ServicesBuilder::getInstance()->createBlobService(
                        $connectionString);
                $blobRestProxy = ServicesBuilder::getInstance()->createBlobService(
                        $connectionString);
                
                $blob_list = $blobRestProxy->listBlobs($this->containerName);
                $blobs = $blob_list->getBlobs();
                $n = 0;
                foreach($blobs as $blob) {
                    $list[$n]['filename'] = $blob->getName();
                    $list[$n]['uri'] = $blob->getUrl();
                    $n++;
                }
                print json_encode($list);
            }
            catch(ServiceException  $e){
                print_r($e->getMessage());
            }
        }
}