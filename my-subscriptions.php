<?php
	session_start();
	require('config.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel='stylesheet' href='styles.css'>
  </head>
  <body>
    <header>
          <?php if(isset($_SESSION['username'])) { ?>
	  		<p class="Home"><a href='index.php'>Home</a></p>
	  		<p class="FirstMenu"><a href='my-subscriptions.php'>My Subscriptions</a></p>
          		<p class="Menu">Hi,&nbsp;<?php echo $_SESSION['username']; ?></p>
      			<p class="LastMenu"><a href='logout.php'>Logout</a></p>
      	  <?php } else { ?>
	  	       	<p class="Home"><a href='index.php'>Home</a></p>
           	       	<p class="FirstMenu"><a href='register.php'>Register</a></p>
			<p class="LastMenu"><a href='login.php'>Login</a></p>
	  <?php } ?>
    </header>
    <div class="Title"> My Subscriptions</div>

    <div class="PosterContainer">
      <?php
	$username=$_SESSION['username'];
	$mysub_query="select * from subscriptions join movies on subscriptions.movie_id=movies.id where username='$username'";
	$mysub_result=mysqli_query($conn,$mysub_query);
	while($row=mysqli_fetch_assoc($mysub_result)){
		$api_url="https://api.themoviedb.org/3/movie/".$row['id']."?api_key=".$api_key;
		$movie_json=json_decode(file_get_contents($api_url),true);
		$poster_url="https://image.tmdb.org/t/p/w154/".$movie_json['poster_path'];
		//other poster sizes - 92, 154, 185, 342, 500, 780, original
		//print($image_url);
      ?>
      <a href='movie-info.php?id=<?php echo $row['id'] ?>'><img  class='Poster' src=<?php echo $poster_url ?> alt=<?php echo $row['name'] ?> ></a>
      <?php } ?>
    </div>
  </body>
</html>


<!--
      <img class="Poster" src="thumbnails/chi-raq-thumbnail.jpg" alt="Chi-Raq">
      <img class="Poster" src="thumbnails/fear-street-part-one-1994-thumbnail.jpg" alt="Fear Street: Part One - 1994">
      <img class="Poster" src="thumbnails/freedom-writers-thumbnail.jpg" alt="Freedom Writers">
      <img class="Poster" src="thumbnails/hawaa-hawaai-thumbnail.jpg" alt="Hawaa Hawaai">
      <img class="Poster" src="thumbnails/mississippi-grind-thumbnail.jpg" alt="Mississippi Grind">
      <img class="Poster" src="thumbnails/paranoia-thumbnail.jpg" alt="Paranoia">
      <img class="Poster" src="thumbnails/the-wedding-guest-thumbnail.jpg" alt="The Wedding Guest">
      <img class="Poster" src="thumbnails/chi-raq-thumbnail.jpg" alt="Chi-Raq">
      <img class="Poster" src="thumbnails/chi-raq-thumbnail.jpg" alt="Chi-Raq">
      <img class="Poster" src="thumbnails/chi-raq-thumbnail.jpg" alt="Chi-Raq">
-->
