<?php

namespace App\Http\Controllers;

class CioController extends Controller {

	function __construct() {
		self::login();
	}

	public function getCierre($period) {

		$postdata = http_build_query(
			array(
				'reportId' => 'reportSales',
				'filterType' => 'T',
				'period' => $period,
			)
		);

		$url = "http://10.7.3.53/console/reportSales/";
		$page = self::get_web_page($url, $postdata);

		return $page;
	}

	public function getYPFenRuta($period) {

		$postdata = http_build_query(
			array(
				'idReporte' => 'reportDispatchs',
				'filterType' => 'T',
				'period' => $period,
				'paymentTypes[]' => 48
			)
		);

		$url = "http://10.7.3.53/console/reportDispatchs";
		$page = self::get_web_page($url, $postdata);

		return $page;
	}

	/**
	 * @month  Int [0-11];
	 * $year long year
	 */
	public function getPeriodos($month = 9, $year = 2019) {
		$url = "http://10.7.3.53/console/reportSales/reloadPeriod/T/" . $month . "/" . $year . "/";
		$result = self::get_web_page($url, [], ['Content-type: application/json']);
		return $result;
	}

	private static function login() {
		$postdata = http_build_query(
			array(
				'id_usuario' => '27294608',
				'clave' => 'P4bl0mar',
			)
		);

		$url = "http://10.7.3.53/console/login/login/";
		$result = self::get_web_page($url, $postdata);
		return $result;
	}

	private static function tableDomtoDataArray($table) {
		$tableDom = new DomDocument();
		$tableDom->appendChild($tableDom->importNode($table, true));

		$obj = [];
		$jsonObj = [];
		$th = $tableDom->getElementsByTagName('th');
		$td = $tableDom->getElementsByTagName('td');
		$thNum = $th->length;
		$arrLength = $td->length;
		$rowIx = 0;

		for ($i = 0; $i < $arrLength; $i++) {
			$head = trim($th[$i % $thNum]->textContent);
			$content = trim($td[$i]->textContent);
			$obj[$head] = $content;
			if (($i + 1) % $thNum === 0) {
				$jsonObj[++$rowIx] = $obj;
				$obj = [];
			}
		}

		return $jsonObj;
	}

	/**
	 * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
	 * array containing the HTTP server response header fields and content.
	 */
	private static function get_web_page($url, $postdata, $headers = ['Content-Type: application/x-www-form-urlencoded']) {
		$user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

		$options = array(

			CURLOPT_CUSTOMREQUEST => "POST", //set request type post or get
			CURLOPT_POST => 1,
			CURLOPT_USERAGENT => $user_agent, //set user agent
			CURLOPT_COOKIEFILE => "cookie.txt", //set cookie file
			CURLOPT_COOKIEJAR => "cookie.txt", //set cookie jar
			CURLOPT_RETURNTRANSFER => true, // return web page
			CURLOPT_HEADER => false, // don't return headers
			CURLOPT_FOLLOWLOCATION => true, // follow redirects
			CURLOPT_ENCODING => "", // handle all encodings
			CURLOPT_AUTOREFERER => true, // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
			CURLOPT_TIMEOUT => 120, // timeout on response
			CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
			CURLOPT_POSTFIELDS => $postdata,
			CURLOPT_HTTPHEADER => $headers,
		);

		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		$content = curl_exec($ch);
		$err = curl_errno($ch);
		$errmsg = curl_error($ch);
		$header = curl_getinfo($ch);
		curl_close($ch);

		$header['errno'] = $err;
		$header['errmsg'] = $errmsg;
		$header['content'] = $content;

		/** Error en la comunicacion */
		if ($header['errno'] != 0) {exit(json_encode(['error' => 1]));}
		if ($header['http_code'] != 200) {exit(json_encode(['error' => 1]));}

		return $content;
	}




}
