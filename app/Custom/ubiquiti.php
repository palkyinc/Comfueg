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
    public function getIp()
    {
        return $this->_ip;
    }
    public function setRenewDhcp ()
    {
        if ($this->status()){
            $comando = 'echo y\n | plink -pw ' . $this->_password . ' '  . $this->_username . '@' . $this->_ip . ' killall udhcpc  2>&1';
            $output = shell_exec($comando);
            return true;
        }else {
            return false;
        }
    }
    public function getTestinternet ($direccion)
    {
        $comando = 'echo y | plink -pw '. $this->_password . ' '  . $this->_username . '@' . $this->_ip . ' ping ' . $direccion . ' -c 10 > putty.log  2>&1';
        $output = shell_exec($comando);
        $file = fopen("putty.log", "r");
        while(!feof($file))
            {
                $linea = fgets($file);
                //echo "$linea <br>\n";
                if (substr($linea,0,10)==='10 packets')
                    $dato["Ping_Loss"] = $this->between('ved, ','%',$linea);
                if (substr($linea,0,10)==='round-trip')
                    {
                    $dato["Ping_AVG"] = $this->between('/','/', $this->after ('=', $linea));
                    }
            } 
        fclose($file);
        return isset($dato) ? $dato : null;
    }
    private function between ($esto, $that, $inthat)
    {
        return $this->before($that, $this->after($esto, $inthat));
    }
    private function before ($esto, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $esto));
    }
    private function after ($esto, $inthat)
    {
        if (!is_bool(strpos($inthat, $esto)))
        return substr($inthat, strpos($inthat,$esto)+strlen($esto));
    }
    public static function makeBkp ($datos)
    {
        $output=null;
        $retval=null;
        exec ('echo y | pscp -scp -pw ' . $datos['password'] . ' ' . $datos['usuario'] . '@' . $datos['ip'] . ':/tmp/system.cfg /app/public/configPanels/' . $datos['ip'] . '-bkp.cfg  2>&1',
                            $output,
                            $retval);
        return ['output' => $output, 'retval' => $retval];
    }
    /* 
    * tratarMac([
    *                     'usuario' => '',
    *                     'password' => '',
    *                     'ip' => '',
    *                     'contrato' => '',
    *                     'macaddress' => '',
    *                     'ope' => 1 = Del, 0 = Add
    *     ])
    */
    public static function tratarMac ($datos)
        {
            shell_exec ('echo y | pscp -scp -pw ' . $datos['password'] . ' ' . $datos['usuario'] . '@' . $datos['ip'] . ':/tmp/system.cfg /app/public/configPanels/' . $datos['ip'] . '-old.cfg  2>&1');
            $oldFile = fopen ('/app/public/configPanels/' . $datos['ip'] . '-old.cfg', 'r');
            $newFile = fopen ('/app/public/configPanels/' . $datos['ip'] . '.cfg', 'w');
            while (!feof ($oldFile))
            {
                $linea = fgets($oldFile);
                $datoLinea = explode('=', $linea);
                $lineaExplotada = explode ('.', ($datoLinea[0]));
                if ($lineaExplotada[0] == 'wireless' && $lineaExplotada[1] == '1' && $lineaExplotada[2] == 'mac_acl' && isset($lineaExplotada[4]))
                {
                    $listaMacs[$lineaExplotada[3]][$lineaExplotada[4]] = $datoLinea[1];
                    if ($lineaExplotada[4] == 'mac' && trim($datoLinea[1]) == $datos['macaddress'])
                    {
                        $encontrado = $lineaExplotada[3];
                    }
                }
                else
                    {
                    fwrite ($newFile, $linea);
                    }
            }
            fclose($oldFile);
            switch ($datos['ope']) 
            {
                case 0:
                    if (!isset($listaMacs))
                    {
                        $largoArray = 0;
                    }
                    else 
                    {
                        $largoArray = count($listaMacs);
                    }
                    if (isset($encontrado))
                    {
                        if (
                            $listaMacs[$encontrado]['comment'] != $datos['contrato'] . ';modifiedBySlam' . PHP_EOL &&
                            $listaMacs[$encontrado]['comment'] != $datos['contrato'] . ';addBySlam' . PHP_EOL)
                            {
                                $listaMacs[$encontrado]['comment'] = $datos['contrato'] . ';modifiedBySlam' . PHP_EOL;
                                $listaMacs[$encontrado]['status'] = 'enabled' . PHP_EOL;
                            }
                    }
                    else    {
                            $listaMacs[$largoArray + 1]['comment'] = $datos['contrato'] . ';addBySlam' . PHP_EOL;
                            $listaMacs[$largoArray + 1]['mac'] = $datos['macaddress'] . PHP_EOL;
                            $listaMacs[$largoArray + 1]['status'] = 'enabled' . PHP_EOL;
                            }
                    for ($i = 1; $i <= count($listaMacs); $i++) 
                    {
                        fwrite($newFile, 'wireless.1.mac_acl.' . $i . '.comment=' . $listaMacs[$i]['comment']);
                        fwrite($newFile, 'wireless.1.mac_acl.' . $i . '.mac=' . $listaMacs[$i]['mac']);
                        fwrite($newFile, 'wireless.1.mac_acl.' . $i . '.status=' . $listaMacs[$i]['status']);
                    }
                    fclose($newFile);
                    shell_exec('echo y | pscp -scp -pw ' . $datos['password'] . ' /app/public/configPanels/' . $datos['ip'] . '.cfg '  . $datos['usuario'] . '@' . $datos['ip'] . ':/tmp/system.cfg 2>&1' );
                    shell_exec('echo y | plink ' . $datos['usuario'] . '@' . $datos['ip'] . ' -pw ' . $datos['password'] . ' iwpriv ath0 addmac ' . $datos['macaddress'] . ' 2>&1');
                    shell_exec('echo n | plink ' . $datos['usuario'] . '@' . $datos['ip'] . ' -pw ' . $datos['password'] . ' -m save.sh 2>&1');
                    shell_exec('echo n | plink ' . $datos['usuario'] . '@' . $datos['ip'] . ' -pw ' . $datos['password'] . ' -m reboot.sh 2>&1');
                    return 'EXITO. Mac Address Cargado OK en panel con IP ' . $datos['ip'];
                    break;
            
                case 1:
                    if (isset($listaMacs))
                    {
                        if (isset($encontrado))
                        {
                            $largoArray = count($listaMacs);
                            unset($listaMacs[$encontrado]);
                            for ($i = $largoArray - 1; 0 < $i; $i--) 
                            {
                                if (isset($listaMacs[$largoArray]))
                                {
                                    fwrite($newFile, 'wireless.1.mac_acl.' . $i . '.comment=' . $listaMacs[$largoArray]['comment']);
                                    fwrite($newFile, 'wireless.1.mac_acl.' . $i . '.mac=' . $listaMacs[$largoArray]['mac']);
                                    fwrite($newFile, 'wireless.1.mac_acl.' . $i . '.status=' . $listaMacs[$largoArray]['status']);
                                }
                                    else
                                    {
                                        $i++;
                                    }
                                $largoArray--;
                            }
                            fclose($newFile);
                            shell_exec('echo y | pscp -scp -pw ' . $datos['password'] . ' /app/public/configPanels/' . $datos['ip'] . '.cfg '  . $datos['usuario'] . '@' . $datos['ip'] . ':/tmp/system.cfg 2>&1' );
                            shell_exec('echo y | plink ' . $datos['usuario'] . '@' . $datos['ip'] . ' -pw ' . $datos['password'] . ' iwpriv ath0 delmac ' . $datos['macaddress'] . ' 2>&1');
                            shell_exec('echo n | plink ' . $datos['usuario'] . '@' . $datos['ip'] . ' -pw ' . $datos['password'] . ' iwpriv ath0 kickmac ' . $datos['macaddress'] . ' 2>&1');
                            shell_exec('echo n | plink ' . $datos['usuario'] . '@' . $datos['ip'] . ' -pw ' . $datos['password'] . ' -m save.sh 2>&1');
                            shell_exec('echo n | plink ' . $datos['usuario'] . '@' . $datos['ip'] . ' -pw ' . $datos['password'] . ' -m reboot.sh 2>&1');
                            return 'EXITO. Se eliminó Mac Address OK del Panel con IP:' . $datos['ip'];
                        }
                        
                    }
                    fclose($newFile);
                    return 'ERROR. No se encontró Mac Address para borrar en Panel con IP: ' . $datos['ip'];
                    break;
                
                default:
                    return "ERROR. tipo de ope incorrecta al tratar Mac en Panel";
                    break;
            }
            shell_exec ("echo y | plink $usuario@$ip -pw $password iwpriv ath0 $command $macaddress > response.txt 2>&1");
            return true;
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

	private function login()
    {
		$exec	= $this->query('/');
		if($exec){
			return true;
		} else {
			return false;
		}
	}

	public function stations($array = false)
    {
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

    public function status($array = false)
    {
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

    public function status_new($array = false)
    {
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

    public function ifstats($array = false)
    {
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

    public function iflist($array = false)
    {
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

    public function brmacs($array = false)
    {
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

    public function station_kick($mac, $interface, $array = false)
    {
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

    public function spectrum($timeout = 10, $array = false)
    {
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

    public function signal($array = false)
    {
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

    public function air_view($array = false)
    {
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

	public function __destruct()
    {
		curl_close($this->_ch);
	}

    

}
