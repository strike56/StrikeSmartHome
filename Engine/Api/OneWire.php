<?php 

    namespace Engine\Api;

    use Engine\Config;
    use OWNet;

	class OneWire {

#########################################

		private $dir = '';
		public  $attempts = 5;
		public  $alarm = [];
		public $server;
		
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
//					file_put_contents(PUBLIC_DIR."/logs/ow_error_log", date("d.m.Y H:i:s")."[1] - get:".$fName."\n", FILE_APPEND);
					return false;
				}
			} catch (Exception $e) {
//				file_put_contents(PUBLIC_DIR."/logs/ow_error_log", date("d.m.Y H:i:s")."[2] - get:".$fName."\n", FILE_APPEND);
				return false;
			}
			
			if ($trim) $data = trim($data);
			return $data;
		}
		
#########################################
		
		public function set($uid, $path, $value) {
			if (!$this->server) return false;
			
			$deviceDir = $this->dir."/".strtoupper($uid);
			$fName = $deviceDir.$path;
			
			try {
				$stat = $this->server->set($fName, $value);
				if (!isset($stat) || !$stat) { 
//					file_put_contents(PUBLIC_DIR."/logs/ow_error_log", date("d.m.Y H:i:s")."[1] - set:".$fName."\n", FILE_APPEND);
					return false;
				}
			} catch (Exception $e) {
//				file_put_contents(PUBLIC_DIR."/logs/ow_error_log", date("d.m.Y H:i:s")."[2] - set:".$fName."\n", FILE_APPEND);
				return false;
			}
			
			return true;
		}
		
#########################################

		public function get_temp($uid) {
			
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
			$val = round($data, 1);

			return $val;
		}
		
#########################################

		public function set_key($uid, $value = "1", $channel = "A") {
			
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

		public function get_key($uid, $channel = "A") {
			
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
			
			$val = $data;
			return $val;
		}
		
#########################################

		public function get_latch($uid, $channel = "A") {
			
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
			
			// Clear
			if ($data) $this->set($uid, "/latch.".$channel, 0);
			
			$val = $data; 
			return $val;
		}
		
#########################################

		public function get_sensed($uid, $channel = "A", $cached = true) {
			
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
			
			$val = $data; 
			return $val;
		}
		
#########################################

		public function load_alarm() {
			$dir = $this->dir."/alarm";
			$itm = $this->server->get($dir, OWNET_MSG_DIR, false);
			$items = array();
			if (isset($itm) && $itm) {
				$itm = explode(",", $itm);
				foreach($itm as $val) {
					$val = preg_replace("#^".$dir."/#", "", $val);
					$items[$val] = $val;
				}
			}
			$this->alarm = $items;
			return $this->alarm;
		}
		
#########################################
				
		public function is_alarm($uid) {
			$stat = isset($this->alarm[$uid]) ? true : false;
			return $stat;
		}
		
#########################################
				
		public function error($type, $file, $value = false) {
/*
		    $s = "SELECT * FROM errors
                    WHERE type = '$type'
                    AND file = '".$this->kernel->db->escape($file)."'
                    AND value ".($value === false ? " IS NULL " : " = '".$this->kernel->db->escape($value)."'");
            $r = $this->kernel->db->query($s);
            if (!$r) return false;
            if ($rr = $r->getRow()) {
                $fld = array(
                    "dateLast"	=> date("Y-m-d H:i:s"),
                    "quantity"  => $rr["quantity"] + 1,
                );
                $ID = $rr["ID"];
            } else {
                $fld = array(
                    "dateAdd" => date("Y-m-d H:i:s"),
                    "dateLast" => date("Y-m-d H:i:s"),
                    "type" => $type,
                    "file" => $this->kernel->db->escape($file),
                    "value" => $value === false ? false : $this->kernel->db->escape($value),
                    "quantity" => 1,
                );
                $ID = false;
            }
			$this->kernel->db->make("errors", $ID, $fld);
*/
		}
		
#########################################
					
	}
