<?php
require("./bm_functions.php");
function echo_email($text){
	// TODO obfusciate the e-mail with CSS
	echo "<a class='email-link' target='_blank' href='mailto:$text'>$text</a>";
}
$books = getBook($_GET['b']);
if(!isset($_GET[b])){
	http_response_code(404);
	die("No book ID is specified");
}
if(count($books) == 0){
	http_response_code(404);
	die("The book you are looking for might have been removed or might have never existed.");
}
$book = $books[0];
// From here on out, the book must exist
?>
<!DOCTYPE html>
<html>
<head>
	<title>Book Info</title>
	<link rel="stylesheet" href="css/global.css"/>
	<link rel="stylesheet" href="css/bookinfo.css"/>
</head>
<body>
	<div class="body-container">
		<div class="body-header">
			<?php include("html/header.html"); ?>
		</div>
		<div class="main-content">
			<div class="content-block content-block-compact book-info">
				<h2>Book Information for <?php echo $book['book_name']?></h2>
				<!-- Information in this list will be pulled from the db through PHP -->
				<div><span class="s-c-b-header">Book Detail</span></div>
				<div class="book-details sub-content-block">
					<div class="book-text">
						<ul>
							<li>School: <?php echo $book['owner_school']?></li>
							<li>Title: <?php echo $book['book_name']?></li>
							<li>Author: <?php echo $book['book_author']?></li>
							<li>Year: <?php echo $book['book_year']?></li>
							<li>ISBN: <?php echo $book['book_isbn']?></li>
							<li>Price: <?php echo $book['book_price']?></li>
						</ul>
						<h2>Contact Information</h2>
						<ul>
							<li>Current Owner: <?php echo $book['owner_name']?></li>
							<li>Email: <?php echo_email($book['owner_email'])?></li>
							<li>Phone: <?php echo $book['owner_phone']?></li>
						</ul>
					</div>
					<div class="book-image">
						<?php $isbn = $book['book_isbn'];
						echo"<img alt='image of book' class='result-img' src='images/isbn/$isbn.jpg' onerror='this.src = \"./images/nobookerror.jpg\"; this.onerror=null;'></img>" ?>
					</div>
				</div>
				<div><span class="s-c-b-header">User Comments</span></div>	
				<div class="book-comments sub-content-block">
					<?php echo $book['owner_notes']?>
				</div>
			</div>
		</div>
	</div>
	<div class="body-footer">
		<?php include("html/footer.html"); ?>
	</div>
</body>
</html>
