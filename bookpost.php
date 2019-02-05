<?php
	if(isset($_POST['book_isbn'])){
		// submit things
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>List a book</title>
	<link rel="stylesheet" href="css/global.css"/>
	<link rel="stylesheet" href="css/bookpost.css"/>
	<script>
		function refresh(){
			console.log("yay");
			var visibles = document.getElementsByTagName("input");
			var allowSubmit = false;
			for(var x = 0; x < visibles.length; x++){
				var element = visibles[x];
				if(element.type=="checkbox"){
					if(element.checked == true)
						allowSubmit = true;
				}
			}
			var noteLabel = document.getElementById("notefield");
			if(allowSubmit){
				console.log("rem");
				noteLabel.classList.remove('req');
			} else{
				console.log("add");
				noteLabel.classList.add('req');
			}
		}
	</script>
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
	<div class="body-container">
		<div class="body-header">
			<?php include("html/header.html"); ?>
		</div>
		<div class="main-content">
			<div class="content-block database-search">
				<h2 class="info-header">List a book </h2>
				<div class="form-holder">
					<form method="post" action="bookManager.php">
						<div class="query-data">
							<div class="book-info">
								<h3>Book Info</h3>
								<span class="label">Book title</span>
								<input type="text" maxlength="150" name="book_name">
								<span class="req label">ISBN</span>
								<input type="text" maxlength="50" name="book_isbn">
								<span class="label">Author</span>
								<input type="text" maxlength="50" name="book_author">
								<span class="label">Year</span>
								<input type="text" maxlength="50" name="book_year">
								<span class="req label">Condition</span>
								<select name="book_condition">
									<option value="F">Needs Repair</option>
									<option value="D">Poor</option>
									<option value="C">Well Used</option>
									<option value="B" selected>Like New</option>
									<option value="A">Brand New</option>
								</select><br>
								<span class="req label">Price</span>
								<input type="text" maxlength="50" name="book_price">
							</div>
							<div class="user-info">
								<h3>User Info</h3>
								<span class="req label">Name</span>
								<input type="text" maxlength="50" name="owner_name">
								<span class="req label">School</span>
								<select name="owner_school">
									<option value="MSU" selected>Montana State University</option>
								</select>
								<div class="label-line"><span class="label">Email</span><input onclick="refresh()" type="checkbox" name="email_visible">Visible to Viewers</div>
								<input type="text" maxlength="50" name="owner_email">
								<div class="label-line"><span class="label">Phone</span><input onclick="refresh()" type="checkbox" name="phone_visible" checked>Visible to Viewers</div>
								<input type="text" maxlength="50" name="owner_phone">
								<span id="notefield" class="label">Notes</span>
								<textarea maxlength="1000" name="owner_notes"></textarea>
							</div>
						</div>
						<div class="submission-box">
							<div class="g-recaptcha" data-sitekey="6LdF_IwUAAAAAADMtQo29LnlF0qXp3YDYapOZvoV"></div> 
							<input type="hidden" name="request" value="submit">
							<input class="submit" value="List Book" type="submit">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="body-footer">
		<?php include("html/footer.html"); ?>
	</div>
</body>
</html>
