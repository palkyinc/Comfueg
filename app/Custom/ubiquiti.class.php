<?php
//namespace App\Custom;
/*
 * Usage example:
 * $ubiquiti   = new Ubiquiti('10.10.6.34', 'ubnt', 'S1mon3d@', true, '80', 3);
 * print_r($ubiquiti->stations(true)); //true => array | false | json
 * print_r($ubiquiti->status(true)); true => array | false | json
 * print_r($ubiquiti->status_new(true)); true => array | false | json
 * print_r($ubiquiti->ifstats(true)); true => array | false | json
 * print_r($ubiquiti->iflist(true)); true => array | false | json
 * print_r($ubiquiti->brmacs(true)); true => array | false | json
 * print_r($ubiquiti->spectrum(10, false));  true => array | false | json
 * print_r($ubiquiti->signal(true)); true => array | false | json
 * print_r($ubiquiti->air_view(true)); true => array | false | json
 * print_r($ubiquiti->station_kick('AA:BB:CC:DD:EE:FF', 'ath0', true)); true => array | false | json
 */

class Ubiquiti{

	private $_ch;
	private $_baseurl;
	private $_timeout;
	private $_username;
	private $_password;
	private $_ip;


	public function __construct($ip, $user, $password, $https = true, $port = '443', $timeout = 5){
		$this->_ch			= curl_init();
		$this->_timeout		= $timeout;
		$this->_username	= $user;
		$this->_password	= $password;
		$this->_ip			= $ip;
		$this->_baseurl		= ($https) ? 'https://'.$ip.':'.$port.'/login.cgi?uri=' : 'http://'.$ip.':'.$port.'/login.cgi?uri=';
	}

	private function query($page, $timeout = false){
		if(!$timeout){
			$timeout = $this->_timeout;
		}

		$postdata	= [
			'username'	=> $this->_username,
			'password'	=> $this->_password,
			'redirect'	=> $this->_baseurl,
			'uri'		=> $page
		];

        curl_setopt($this->_ch, CURLOPT_URL, $this->_baseurl.$page);
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->_ch, CURLOPT_HTTPHEADER,array("Expect:  "));
        curl_setopt($this->_ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_ch, CURLOPT_COOKIEFILE, '/tmp/cookie-'.$this->_ip);
        curl_setopt($this->_ch, CURLOPT_COOKIEJAR, '/tmp/cookie-'.$this->_ip);
        curl_setopt($this->_ch, CURLOPT_POST, true);
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $postdata);
        $result		= curl_exec($this->_ch);
		return ($result);
	}

	private function login(){
		$exec	= $this->query('/');
		if($exec){
			return true;
		} else {
			return false;
		}
	}

	public function stations($array = false){
		if($this->login()){
			$result	= $this->query('/sta.cgi');
			if($array){
				$result = json_decode($result, true);
				return ($result);
			} else {
				return $result;
			}
		} else {
			return false;
		}
	}

    public function status($array = false){
        if($this->login()){
            $result = $this->query('/status.cgi');
            if($array){
                $result = json_decode($result, true);
                return ($result);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }

    public function status_new($array = false){
        if($this->login()){
            $result = $this->query('/status-new.cgi');
            if($array){
                $result = json_decode($result, true);
                return ($result);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }

    public function ifstats($array = false){
        if($this->login()){
            $result = $this->query('/ifstats.cgi');
            if($array){
                $result = json_decode($result, true);
                return ($result);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }

    public function iflist($array = false){
        if($this->login()){
            $result = $this->query('/iflist.cgi');
            if($array){
                $result = json_decode($result, true);
                return ($result);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }

    public function brmacs($array = false){
        if($this->login()){
            $result = $this->query('/brmacs.cgi?brmacs=y');
            if($array){
                $result = json_decode($result, true);
                return ($result);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }

    public function station_kick($mac, $interface, $array = false){
        if($this->login()){
            $result = $this->query('/stakick.cgi?staid='.$mac.'&staif='.$interface);
            if($array){
                $result = json_decode($result, true);
                return ($result);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }

    public function spectrum($timeout = 10, $array = false){
        if($this->login()){
            $result = $this->query('/survey.json.cgi', $timeout);
            if($array){
                $result = json_decode($result, true);
                return ($result);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }

    public function signal($array = false){
        if($this->login()){
            $result = $this->query('/signal.cgi');
            if($array){
                $result = json_decode($result, true);
                return ($result);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }

    public function air_view($array = false){
        if($this->login()){
            $result = $this->query('/air-view.cgi');
            if($array){
                $result = json_decode($result, true);
                return ($result);
            } else {
                return $result;
            }
        } else {
            return false;
        }
    }

	public function __destruct(){
		curl_close($this->_ch);
	}

}

?>
