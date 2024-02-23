<?php 
namespace App\Libraries;
use Illuminate\Support\Facades\Request; // Import class Request dari Laravel
use Illuminate\Support\Facades\Http;

	class ZukoLibs {
	
		private $config = array(
			'host' => 'https://zuko.stiki.ac.id/index.php/',
			'app_id' => 'nbNJhl0Wm77l0dml',
			'private_token' => '2k7ZZ8XkjeSrnXqI4iiUzXmBsYsqaHoK',
			'public_token' => '05qdXbrcojy6dc'
		);
	
		public function connect()
		{
			$conf = $this->config;
			
			// Mendapatkan alamat IP server menggunakan Request::server('SERVER_ADDR')
			$ipAddress = Request::server('SERVER_ADDR');
			
			$data = array(
				'app' => base64_encode(base64_encode($conf['app_id'])),
				'device' => base64_encode(base64_encode($ipAddress)),
				'data' => urlencode($this->aes_encode($conf['public_token'],$conf['private_token'])),
			);
	
			$res = $this->curl_request($this->config['host'].'/connect',$data);
			if(isset($res['isOk']) && $res['isOk']){
				$res['data'] = array(
					'session_token' => $this->aes_decode($res['data']['data'],$conf['private_token']).$this->aes_decode($res['data']['ack'],$conf['private_token']),
				);
			}else{
				$res['data'] = array();
			}
			return $res;
		}

		function get_mahasiswa($token,$par){
			$data = array(
				'token' => $this->myBase64encode($token),
				'data' => json_encode($par),
			);
			print_r($data);
			die();
			$res = $this->curl_request($this->config['host'].'/get_mahasiswa',$data);
			return $res;
		}
		function get_mhs_profile($token,$par){
			$data = array(
                            'token' => $this->myBase64encode($token),
                            'data' => json_encode($par),
			);
			$res = $this->curl_request($this->config['host'].'/get_mhs_profile',$data);
			return $res;
		}
		function get_mhs_jumlah_aktif_by_program($token){
			$data = array(
                            'token' => $this->myBase64encode($token),
                            'data' => json_encode(array('secretkey'=>'38b35238bd687d851c6431dde30bba77')),
			);
			$res = $this->curl_request($this->config['host'].'/get_mhs_jumlah_aktif_by_program',$data);
			return $res;
		}
		function get_mhs_krs($token,$par){
			$data = array(
                            'token' => $this->myBase64encode($token),
                            'data' => json_encode($par),
			);
			$res = $this->curl_request($this->config['host'].'/get_mhs_krs',$data);
			return $res;
		}
		function get_mhs_nilai($token,$par){
			$data = array(
                            'token' => $this->myBase64encode($token),
                            'data' => json_encode($par),
			);
			$res = $this->curl_request($this->config['host'].'/get_mhs_nilai',$data);
			return $res;
		}
		function get_mata_kuliah($token,$par){
			$data = array(
				'token' => $this->myBase64encode($token),
				'data' => json_encode($par),
			);
			$res = $this->curl_request($this->config['host'].'/get_mata_kuliah',$data);
			return $res;
		}
		function get_kelas_kuliah($token,$par){
			$data = array(
				'token' => $this->myBase64encode($token),
				'data' => json_encode($par),
			);
			$res = $this->curl_request($this->config['host'].'/get_kelas_kuliah',$data);
			return $res;
		}
		function get_peserta_kelas($token, $par){
			$data = array(
				'token' => $this->myBase64encode($token),
				'data' => json_encode($par),
			);
			$res = $this->curl_request($this->config['host'].'/get_peserta_kelas',$data);
			return $res;
		}
		function get_unit($token,$par){
			$data = array(
				'token' => $this->myBase64encode($token),
				'data' => json_encode($par),
			);
			$res = $this->curl_request($this->config['host'].'/get_unit',$data);
			return $res;
		}
		function get_pegawai($token,$par){
			$data = array(
				'token' => $this->myBase64encode($token),
				'data' => json_encode($par),
			);
			$res = $this->curl_request($this->config['host'].'/get_pegawai',$data);
			return $res;
		}
		function get_dosen($token,$par){
			$data = array(
				'token' => $this->myBase64encode($token),
				'data' => json_encode($par),
			);
			$res = $this->curl_request($this->config['host'].'/get_dosen',$data);
			return $res;
		}
		function get_dosen_penugasan_ajar($token,$par){
			$data = array(
				'token' => $this->myBase64encode($token),
				'data' => json_encode($par),
			);
			$res = $this->curl_request($this->config['host'].'/get_dosen_penugasan_ajar',$data);
			return $res;
		}
		function get_dosen_jadwal_ajar($token,$par){
			$data = array(
				'token' => $this->myBase64encode($token),
				'data' => json_encode($par),
			);
			$res = $this->curl_request($this->config['host'].'/get_dosen_jadwal_ajar',$data);
			return $res;
		}
		function get_dosen_kuisioner_ajar($token,$par){
			$data = array(
				'token' => $this->myBase64encode($token),
				'data' => json_encode($par),
			);
			$res = $this->curl_request($this->config['host'].'/get_dosen_kuisioner_ajar',$data);
			return $res;
		}
		function get_dosen_persentase_kelulusan($token,$par){
			$data = array(
				'token' => $this->myBase64encode($token),
				'data' => json_encode($par),
			);
			$res = $this->curl_request($this->config['host'].'/get_dosen_persentase_kelulusan',$data);
			return $res;
		}
		function get_ruang($token,$par){
			$data = array(
				'token' => $this->myBase64encode($token),
				'data' => json_encode($par),
			);
			$res = $this->curl_request($this->config['host'].'/get_ruang',$data);
			return $res;
		}
		function get_aset_router($token,$par){
			$data = array(
				'token' => $this->myBase64encode($token),
				'data' => json_encode($par),
			);
			$res = $this->curl_request($this->config['host'].'/get_aset_router',$data);
			return $res;
		}
		function get_khs_mhs($token, $idmhs, $refdata = array()){
			/*
			$refdata = array(
				'requestedby' => // session user
			)
			*/
			if(empty($idmhs)) return array('isOk'=>false,'errno'=>'400008','msg'=>'Invalid parameters');

			$data = array(
				'token' => $this->myBase64encode($token),
				'data' => json_encode(array('nrp'=>$idmhs)),
				'refdata' => json_encode($refdata),
			); print_r($data);
			$res = $this->curl_request($this->config['host'].'/get_khs_mhs',$data);
			return $res;
		}
		function att_submitqr($token, $qrtoken, $logdata = ''){
			$data = array(
				'token' => $this->myBase64encode($token),
				'data' => $this->myBase64encode($qrtoken),
				'logdata' => $this->myBase64encode($logdata),
			);
			$res = $this->curl_request($this->config['host'].'/att_submitqr',$data);
			return $res;
		}
	
                
		function curl_request($url,$data){
			$ch = curl_init();

			// set url
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

			//return the transfer as a string
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			// $output contains the output string
			$output = curl_exec($ch);//echo $output;
			//echo 'output:'.$output; die;
			$output = json_decode($output,true);
			
			// close curl resource to free up system resources
			curl_close($ch);    

			return $output;
		}
		function curl_request_binary($url, $args){
			$curl_session = curl_init();
			curl_setopt($curl_session, CURLOPT_URL, $url);
			curl_setopt($curl_session, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_session, CURLOPT_POST, true);
			curl_setopt($curl_session, CURLOPT_POSTFIELDS, $args);

			$response = curl_exec($curl_session);
			curl_close($curl_session);
			
			$response = json_decode($response,true);
			return $response;
		}
                
		function aes_encode($text,$key){
			$cipher = "aes-256-cbc";
			$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
			$iv = openssl_random_pseudo_bytes($ivlen);
			$ciphertext_raw = openssl_encrypt($text, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
			$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
			$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
			return $ciphertext;
		}
		function aes_decode($text,$key){
			$cipher = "aes-256-cbc";
			$c = base64_decode($text);
			$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
			$iv = substr($c, 0, $ivlen);
			$hmac = substr($c, $ivlen, $sha2len=32);
			$text = substr($c, $ivlen+$sha2len);
			$original_plaintext = openssl_decrypt($text, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
			$calcmac = hash_hmac('sha256', $text, $key, $as_binary=true);
			if (hash_equals($hmac, $calcmac))
			{
				return $original_plaintext;
			}
		}
		function myBase64encode($text){
			$ciph = base64_encode($text);
			$ciph = $ciph.base64_encode("55d691297914ce1a60e044f6fae674d6");
			$ciph = base64_encode($ciph);
			return $ciph;
		}
	}
?>
