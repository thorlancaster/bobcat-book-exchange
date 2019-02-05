<?php
	error_reporting(E_ALL); // Error engine - always ON!
	ini_set('display_errors', TRUE); // Error display - OFF in production env or real server
	ini_set('log_errors', TRUE); // Error logging
	ini_set('error_log', 'var/phperr.log'); // Logging file
	ini_set('log_errors_max_len', 1024); // Logging file size

	require("./bm_functions.php");

	$conn = new mysqli("127.0.0.1", "root", "b00k-ABLOY", "books");

	if(isset($_POST["request"])){
		if(!(is_numeric($_POST['book_isbn']) && ((!isset($_POST['book_year'])) || is_numeric($_POST['book_year']))))
			dieProperly("ISBN and YEAR must be numeric");
		if (!$conn || $conn->connect_error) {
			echo"DB connection failed: " . $conn->connect_error;
			die("Databass Connection Failed"); // Doesn't need to be closed?
		}

		if($_POST["request"] == "submit"){
			if(!verifyRecaptcha()) dieProperly("Captcha Validation Failed");
			if(!(isset($_POST["book_name"]) && 
				isset($_POST["book_isbn"]) && 
				isset($_POST["book_condition"]) && 
				isset($_POST["book_price"])))
			dieProperly("Book must have name, ISBN, condition, and price set");

			if(!(isset($_POST["owner_school"]) && 
				(isset($_POST["owner_email"]) || isset($_POST["owner_phone"]) || isset($_POST["owner_notes"])) 
			)) dieProperly("Owner school and email, or phone, or notes must be set");

			$key = md5(microtime().rand());
			$hash = hash('sha256',$key);
			$uid = substr(hash('sha256', $key . "mod"), 0, 32);
			$stmt = $conn->prepare("INSERT INTO books (
				book_name,
				book_isbn,
				book_author,
				book_year,
				book_condition,
				book_price,

				owner_school,
				owner_email,
				owner_phone,
				owner_notes,

				private_key,
				expire_date,
				uid
			) VALUES (
				?,
				?,
				?,
				?,
				?,
				?,

				?,
				?,
				?,
				?,

				?,
				?,
				?
			)");
			if($stmt == FALSE)
				dieProperly("STMT is FALSE!!!");
			/*
			$sql = "INSERT INTO MyGuests (firstname, lastname, email) VALUES ('John', 'Doe', 'john@example.com')";

			$stmt = $conn->prepare("INSERT INTO REGISTRY (name, value) VALUES (:book_name, :value)");*/
			$stmt->bind_param('sisisssssssss',
				$_POST["book_name"],
				intval($_POST["book_isbn"]),
				$_POST["book_author"],
				intval($_POST["book_year"]),
				$_POST["book_condition"],
				$_POST["book_price"],
				$_POST["owner_school"],
				$_POST["owner_email"],
				$_POST["owner_phone"],
				$_POST["owner_notes"],
				$hash,
				$_POST["expire_date"],
				$uid);

			if ($stmt->execute() == TRUE) {
			} else {
   				echo "New record NOT created successfully!";
			}
			/*
			echo("BP0");
			echo("BP1");
			echo("BP2");
			$stmt->bindValue(":book_name", $_POST["book_name"], PDO::ibase_param_info(query, param_number)STR);
			echo("BP3");
			$stmt->bindParam(":book_isbn", $_POST["book_isbn"]);
			$stmt->bindParam(":book_author", $_POST["book_author"]);
			$stmt->bindParam(":book_year", $_POST["book_year"]);
			$stmt->bindParam(":book_condition", $_POST["book_condition"]);
			$stmt->bindParam(":book_price", $_POST["book_price"]);
			
			$stmt->bindParam(":owner_school", $_POST["owner_school"]);
			$stmt->bindParam(":owner_email", $_POST["owner_email"]);
			$stmt->bindParam(":owner_phone", $_POST["owner_phone"]);
			$stmt->bindParam(":owner_notes", $_POST["owner_notes"]);
			$stmt->bindParam(":private_hashed_key", $hash);*/

			if(isset($_POST['owner_email'])){
				$subject = "Bobcat BookExchange - '" . $_POST['book_name'] . "' management";
				$headers = "From: no-reply@bobcatsports.net";
				$message = "Hello Seller,<br>"
				. "&emsp;This is a one time email that allows you to delete, modify, or renew your posting at Bobcat BookExchange website."
				. "This email and accompanied link are for managing your posting for:<br>"
				. "<b>Title:</b> &emsp;" . $_POST["book_name"] . "<br>"
				. "<b>Author:</b> &emsp;" . $_POST["book_author"] . "<br>"
				. "<b>ISBN: </b> &emsp;" . $_POST["book_isbn"] . "<br>"
				. "<b>Price:</b> &emsp;" . $_POST["book_price"] . "<br><br>"
				. "The link to manage this posting: <a href='https://bobcatsports.net/bcbe/bookpost.php?key=" . $key . "'>"
				. "https://bobcatsports.net/bcbe/bookpost.php?key=" . $key
				. "</a><br><br>"
				. "There will be one more email sent for this publishing, and that will be sent 3 days before the book's posting expires. "
				. "After that expiration date, the posting will be deleted, unless otherwise renewed by you, the poster.";
				mail($_POST['owner_email'],$subject,$message,$headers);
			}
			$bookname = $_POST["book_name"];
			echo("
				<html>
				<head>
					<link rel='stylesheet' href='css/global.css'/>
					<title>Book successfully submitted</title>
				</head>
				<body style='background: #334; color: FFD'>
					<div class='content-block'>
						<h2>Success</h2>
						<p>You have successfully submitted the book <strong>$bookname</strong> to the book exchange!</p>
					</div>
				</body>
				</html>
				");

			/* TODO 
				generate a key
				add to database
				send an email with a link to that key
				return key
				redirect to listing page
			*/

		} else if($_POST["request"] == "showcontact"){
			if(!verifyRecaptcha) dieProperly("Captcha validation failed");

		} else if($_POST["request"] == "manage"){
			if(!isset($_GET["key"])) dieProperly("Key is not set for manage function");
			// TODO Code for managing entry
		} else dieProperly("No Command specified");
		$conn->close();
	} else if(isset($_GET["q"])){

	}else dieProperly('Either <strong>$_POST[request]</strong> or <strong>q</strong> must be set!');


	/*
	// sql to create table
	$sql = "CREATE TABLE book (
	uid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	search_field VARCHAR(250),
	book_name VARCHAR(250),
	book_author VARCHAR(200),
	book_year INT,
	book_isbn VARCHAR(40),
	book_condition CHAR(1),
	book_price FLOAT(8,2),
	owner_email VARCHAR(50),
	owner_phone VARCHAR(25),
	owner_school VARCHAR(20),
	owner_notes VARCHAR(1000),
	submitdate TIMESTAMP,
	expiredate TIMESTAMP,
	private_key VARCHAR(64)
	)";
	*/
	/*
		function verifyRecaptcha(){
		/* <div class="g-recaptcha" data-sitekey="6LdF_IwUAAAAAADMtQo29LnlF0qXp3YDYapOZvoV"></div> 
		if(!isset($_POST['g-recaptcha-response'])){
			echo "Invalid recaptcha response";
			return false;
		}

		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$privatekey = "6LdF_IwUAAAAAA8DGVgNsN_EUxIDrt5IubkaqyxH";
		$data = array('secret' => $privatekey, 'response' => $_POST['g-recaptcha-response'], 'remoteip' => $_SERVER['REMOTE_ADDR']);

		$options = array(
		    'http' => array( //uses https however
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		echo "<br/><br/>F";
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		echo $result;
		print_r($result);
		$json = json_decode($result,true);
		print_r($json);
		echo $json;
		echo "<br/><br/>W";
		return $json['success'] == 'true';
	}
	*/
?>