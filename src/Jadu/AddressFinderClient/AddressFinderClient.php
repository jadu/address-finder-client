<?php
   namespace Jadu\AddressFinderClient; 

  class AddressFinderClient {
    
        private $configuration;
        
        function __construct() {
            $strJsonFileContents = file_get_contents("Configuration/Configuration.json");
            $this->configuration = $strJsonFileContents;
        }

        public function getAllProperties() {
            return "TODO : Get All Properties";
        }
      
        public function getSpecificProperty($identifier) {
            return "TODO: Get Spefific Property ";
        }
    }
