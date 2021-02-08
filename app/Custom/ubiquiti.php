<?php
namespace App\Custom;
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
        $this->_baseurl		= ($https) 
                                ? 
                                ('https://'.$ip) 
                                : 
                                ('http://'.$ip);
	}

	private function query($page, $timeout = false)
    {
		if(!$timeout){
			$timeout = $this->_timeout;
		}

		$postdata	= [
			'username'	=> $this->_username,
			'password'	=> $this->_password,
			'redirect'	=> $this->_baseurl,
			'uri'		=> $page
		];
        $postdata1   = [
            'username'  => $this->_username,
            'password'  => $this->_password
        ];
        
        $cookies = str_replace('\\','/', getcwd().'/'.'tmp/cookie-'.$this->_ip);
        
        //Setup CURL
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($this->_ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_ch, CURLOPT_COOKIEJAR, null);
        curl_setopt($this->_ch, CURLOPT_HTTPHEADER,array("Expect:  "));
        curl_setopt($this->_ch, CURLOPT_TIMEOUT, $timeout);

        // Login AirOS >= 8.5.0+ OR get cookie with session ID for AirOS < 8.5.0
        curl_setopt($this->_ch, CURLOPT_URL, $this->_baseurl . '/api/auth');
        curl_setopt($this->_ch, CURLOPT_POST, true);
	    curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $postdata1);
        curl_setopt ($this->_ch, CURLOPT_HEADER, true);
        $response = curl_exec ($this->_ch);
        curl_setopt ($this->_ch, CURLOPT_HEADER, false);

        // AirOS >= 8.5.0 request and return file
        if (curl_getinfo ($this->_ch, CURLINFO_HTTP_CODE) == 200)
        {
            curl_setopt($this->_ch, CURLOPT_URL, $this->_baseurl . $page);
            curl_setopt($this->_ch, CURLOPT_POST, false);
            $result		= curl_exec($this->_ch);

            curl_setopt ($this->_ch, CURLOPT_URL, $this->_baseurl . "/logout.cgi");
            //curl_setopt ($this->_ch, CURLOPT_HTTPHEADER, Array (trim ($XCSRFID[0]), 'X-AIROS-LUA: 1'));
            curl_setopt ($this->_ch, CURLOPT_POST, 1);
            curl_setopt ($this->_ch, CURLOPT_POSTFIELDS, Array());
            curl_exec ($this->_ch);
        }

        // Login failed, try AirOS < 8.5.0 login, request, and return file
        else
        {
                curl_setopt ($this->_ch, CURLOPT_URL, $this->_baseurl . "/login.cgi");
                curl_setopt ($this->_ch, CURLOPT_POST, 1);
                curl_setopt ($this->_ch, CURLOPT_POSTFIELDS, $postdata1);
                curl_exec ($this->_ch);

                curl_setopt ($this->_ch, CURLOPT_URL, $this->_baseurl . $page);
                curl_setopt ($this->_ch, CURLOPT_POST, false);
                $result     = curl_exec($this->_ch);
        }
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
		if($result = $this->query('/sta.cgi')){
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
        if($result = $this->query('/status.cgi')){
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
        if($result = $this->query('/status-new.cgi')){
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
        if($result = $this->query('/ifstats.cgi')){
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
        if($result = $this->query('/iflist.cgi')){
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
        if($result = $this->query('/brmacs.cgi?brmacs=y')){
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
        if($result = $this->query('/stakick.cgi?staid='.$mac.'&staif='.$interface)){
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
        if($result = $this->query('/survey.json.cgi', $timeout)){
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
        if($result = $this->query('/signal.cgi')){
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
        if($result = $this->query('/air-view.cgi')){
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
