<?php


	function dieProperly($msg){
		if(isset($conn))
			$conn->close();
		die($msg);
	}

	function processSearch($term) {
		if($term == ";" || $term == "%") return FALSE;
		if(strpos($term, ";") > 0) return FALSE;
		if(strpos($term, '%') > 0) return FALSE;
		if(strlen($term) == 0){
			return FALSE;
			$query = "SELECT * FROM books";
		}
		else{
			if(is_numeric($term))
				$query = "SELECT * FROM books WHERE book_isbn = $term";
			else
				$query = "SELECT * FROM books WHERE book_name LIKE \"%$term%\"";
		}
		$conn = new mysqli("127.0.0.1", "root", "b00k-ABLOY", "books");
		$res = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
		$conn->close();
		return $res;
	}

	function getBook($bookid) {
		if(!ctype_alnum($bookid))
			return FALSE;
		$query = "SELECT * FROM books WHERE uid=\"$bookid\"";
		$conn = new mysqli("127.0.0.1", "root", "b00k-ABLOY", "books");
		$res = $conn->query($query);
		if(!$res)
			return FALSE;
		$resx = $res->fetch_all(MYSQLI_ASSOC);
		$conn->close();
		return $resx;
	}

	function httpPost($url, $data){
	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_POST, true);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($curl);
	    curl_close($curl);
	    return $response;
	}

		function verifyRecaptcha(){
		return TRUE; // TODO fix ReCaptcha
		/* <div class="g-recaptcha" data-sitekey="6LdF_IwUAAAAAADMtQo29LnlF0qXp3YDYapOZvoV"></div> */
		if(!isset($_POST['g-recaptcha-response'])){
			echo "Invalid recaptcha response";
			return false;
		}

		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$privatekey = "6LdF_IwUAAAAAA8DGVgNsN_EUxIDrt5IubkaqyxH";
		$data = array('secret' => $privatekey, 'response' => $_POST['g-recaptcha-response'], 'remoteip' => $_SERVER['REMOTE_ADDR']);

		$result = httpPost($url, $data);
		echo $result;
		print_r($result);
		$json = json_decode($result,true);
		print_r($json);
		echo $json;
		echo "<br/><br/>";
		return $json['success'] == 'true';
	}
?>