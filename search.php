<?php
require("./bm_functions.php");

function book_search_result(){
	$q = $_GET['q'];
	$res = processSearch($q);
	$len = count($res);
	//echo $len;
	if($res == FALSE){
		if(isset($q) && strlen($q) > 0)
			echo "<h2>No Results. Please try a different search.</h2>";
	}
	else
		for($x = 0; $x < $len; $x++)
		{
			$resx = $res[$x];
			//print_r($resx);
			$school = $resx['owner_school'];
			$title = $resx['book_name'];
			$author = $resx['book_author'];
			$isbn = $resx['book_isbn'];
			$condition = $resx['book_condition'];
			$bookid = $resx['uid'];
			$condd = 'N/A';
			switch ($condition) {
				case 'A': $condd = "Brand New"; break;
				case 'B': $condd = "Like New"; break;
				case 'C': $condd = "Well Used"; break;
				case 'D': $condd = "Poor"; break;
				case 'F': $condd = "Needs Repair"; break;
			}
			$price = $resx['book_price'];
			echo"<div class='book-search-result'>
				<div class='result-text'>
					<div class='book-search-school'><div class='res-key'>School</div><div class='res-val'>$school</div></div>
					<div class='book-search-title'><div class='res-key'>Title</div><div class='res-val'>$title</div></div>
					<div class='book-search-author'><div class='res-key'>Author</div><div class='res-val'>$author</div></div>
					<div class='book-search-isbn'><div class='res-key'>ISBN</div><div class='res-val'>$isbn</div></div>
					<div class='book-search-condition'><div class='res-key'>Condition</div><div class='res-val'>$condd</div></div>
					<div class='book-search-price'><div class='res-key'>Price</div><div class='res-val'>\$$price</div></div>
				</div>
				<img alt='image of book' class='result-img' src='images/isbn/$isbn.jpg' onerror='this.src = \"./images/nobookerror.jpg\"; this.onerror=null;'></img>
				<a target='_blank' href='bookinfo.php?b=$bookid' class=result-view-more>More Info</a>
			</div>";
		}
}
?>
<html>
<head>
	<title>Book Search</title>
	<link rel="stylesheet" href="css/global.css"/>
	<link rel="stylesheet" href="css/search.css"/>
	<script>document.school = "<?php echo $_GET['school'];?>"</script>
</head>

<body>
	<div class="body-container">
		<div class="body-header">
			<?php include("html/header.html"); ?>
		</div>
		<div class="main-content">
			<div class="content-block search-result">
				<h2 class="search-result-header">
					Search by Title or ISBN:
					<input id="searchTermField" class="search-term" type="text" value="<?php echo $_GET['q']?>" onkeydown = "if(event.keyCode==13)
						window.location.href = window.location.pathname +'?school='+document.school+'&q='+document.getElementById('searchTermField').value"/>
				</h2>
				<div class="search-result-container">
					<?php
					book_search_result();
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="body-footer">
		<?php include("html/footer.html"); ?>
	</div>
</body>
</html>
</html>
