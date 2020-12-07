<?php 

    namespace Engine\Api;

    use Engine\Config;
    use OWNet;
    use Exception;

	class OneWire {

#########################################

		private string $dir = '';
		public int $attempts = 5;
		public array $alarm = [];
		public OWNet $server;
		
#########################################
		
		function __construct() {
			include_once dirname(__FILE__)."/ownet/ownet.php";
			$this->server = new OWNet(Config::get('OneWire', 'url'));
		}
		
#########################################
		
		public function get($uid, $path, $cached = true, $trim = true) {
			if (!$this->server) return false;
			
			$deviceDir = $this->dir.($cached ? "" : "/uncached")."/".strtoupper($uid);
			$fName = $deviceDir.$path;
			
			try {
				$data = $this->server->read($fName, false);
				if (!isset($data) || $data === false) {
					return false;
				}
			} catch (Exception $e) {
				return false;
			}
			
			if ($trim) $data = trim($data);
			return $data;
		}
		
#########################################
		
		public function set($uid, $path, $value): bool
        {
			if (!$this->server) return false;
			
			$deviceDir = $this->dir."/".strtoupper($uid);
			$fName = $deviceDir.$path;
			
			try {
				$stat = $this->server->set($fName, $value);
				if (!isset($stat) || !$stat) { 
					return false;
				}
			} catch (Exception $e) {
				return false;
			}
			return true;
		}
		
#########################################

		public function getTemp($uid): bool|float {

		    $data = false;
			$match = false;
			for ($i = 1; $i <= $this->attempts; $i++) {
				
				$data = $this->get($uid, "/temperature");
				if (!isset($data) || $data === false) {
					usleep(rand(1, 1000000));
					continue;
				}
				if (!is_numeric($data)) {
					usleep(rand(1, 1000000));
					continue;
				}
				$match = true;
				break;
			}
			
			if (!$match) {
				$this->error("get", $uid."/temperature");
				return false;
			}
			return  $data ? round($data, 1) : false;
		}
		
#########################################

		public function setKey($uid, $value = "1", $channel = "A") {
			
			$match = false;
			for ($i = 1; $i <= $this->attempts; $i++) {
				
				$stat = $this->set($uid, "/PIO.".$channel, $value);
				if (!isset($stat) || $stat === false) {
					usleep(rand(1, 1000000));
					continue;
				}
				$match = true;
				
				break;
			}
			
			if (!$match) {
				$this->error("set", $uid."/PIO.".$channel, $value);
				return false;
			}
			return true;
		}
		
#########################################

		public function getKey($uid, $channel = "A") {

            $data = null;
			$match = false;
			for ($i = 1; $i <= $this->attempts; $i++) {
				
				$data = $this->get($uid, "/PIO.".$channel);
				if (!isset($data) || $data === false) {
					usleep(rand(1, 1000000));
					continue;
				}
				$match = true;
				break;
			}
			
			if (!$match) {
				$this->error("get", $uid."/PIO.".$channel);
				return false;
			}
			
			return $data;
		}
		
#########################################

		public function getLatch($uid, $channel = "A") {

		    $data = null;
			$match = false;
			for ($i = 1; $i <= $this->attempts; $i++) {
				$data = $this->get($uid, "/latch.".$channel);
				if (!isset($data) || $data === false) {
					usleep(rand(1, 1000000));
					continue;
				}
				$match = true;
				break;
			}
			
			if (!$match) {
				$this->error("get", $uid."/latch.".$channel);
				return false;
			}
			
			if ($data) $this->set($uid, "/latch.".$channel, 0);
			
			return $data;
		}
		
#########################################

		public function getSensed($uid, $channel = "A", $cached = true) {

		    $data = null;
			$match = false;
			for ($i = 1; $i <= $this->attempts; $i++) {
				
				$data = $this->get($uid, "/sensed.".$channel, $cached);
				if (!isset($data) || $data === false) {
					usleep(rand(1, 1000000));
					continue;
				}
				$match = true;
				
				break;
			}
			
			if (!$match) {
				$this->error("get", $uid."/sensed.".$channel);
				return false;
			}
			
			return $data;
		}
		
#########################################

		public function loadAlarm() {
			$dir = $this->dir."/alarm";
			$itm = $this->server->get($dir, OWNET_MSG_DIR, false);
			$items = array();
			if (isset($itm) && $itm) {
				$itm = explode(",", $itm);
				foreach($itm as $val) {
					$val = preg_replace('/^'.preg_quote($dir."/", '/').'/', '', $val);
					$items[$val] = $val;
				}
			}
			$this->alarm = $items;
			return $this->alarm;
		}
		
#########################################
				
		public function isAlarm($uid) {
			return isset($this->alarm[$uid]);
		}
		
#########################################
				
		public function error($type, $file, $value = false) {
		}
		
#########################################
					
	}
