<?php
	class Curl
	{
		private $_curl, $_isResource, $_isMulti;
	
		/**
		* Constructor, creates a new CURL resource, with option to pass opts
		*/
		public function __construct(array $opts=null, $multi=false)
		{
			if($multi){
				$this->_curl = @curl_multi_init();
			}else{
				$this->_curl = @curl_init();
			}
			
		
			if(is_resource($this->_curl)){
				$this->_isResource 	= ( bool ) true;
				$this->_isMulti 	= ( bool ) $multi;
				return;
			}
			
			throw new Exception('Failed to create CURL Resource, is CURL installed ?');
		}
		
		/**
		* Destructor, closes the resource, "Yeah! Extra Memory"
		*/		
		public function __destruct()
		{
			if ($this->_isResource) {
				if($this->_isMulti){
					curl_multi_close($this->_curl);
				}else{
					curl_close($this->_curl);
				}
			}
		}
		
		/**
		* Clone, upon clone of the object we copy the CURL resource
		*/		
		public function __clone()
		{
			if ($this->_isResource) {
				$this->_curl = curl_copy_handle($this->_curl);
				return;
			}
			
			throw new Exception('Trying to clone an empty CURL Object.');
		}
		
		/**
		* Returns the CURL version, simples :)
		* @return array
		*/	
		public function getVersion()
		{
			return ( array ) curl_version();
		}
		
		/**
		* Returns error string
		* @return string/null
		*/			
		public function getError()
		{
			if ($this->_isResource) {
				return (curl_error($this->_curl)) ?: null;
			}
		}
		
		/**
		* Returns error number, full list of error codes here
		* http://curl.haxx.se/libcurl/c/libcurl-errors.html
		* @return int/null
		*/			
		public function getErrno()
		{
			if ($this->_isResource) {
				return curl_errno($this->_curl);
			}
		}
	}