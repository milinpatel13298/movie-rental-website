
<?php
	session_start();
	require('config.php');
	//echo $_GET['id'];
	$api_url="https://api.themoviedb.org/3/movie/".$_GET['id']."?api_key=".$api_key."&append_to_response=videos,credits,release_dates";
	$movie_json=json_decode(file_get_contents($api_url),true);
	//print_r($movie_json);
	if(!empty($movie_json['videos']['results'])){
		//$video_boolean=1;
		foreach ($movie_json['videos']['results'] as $tmp){
			if($tmp['site']=='YouTube' && $tmp['type']=='Trailer'){
				$video_key=$tmp['key'];
				break;
			}
		}
	}
	$youtube_link="https://www.youtube.com/embed/".$video_key;
	//else $video_boolean=0;
	$api_video_url="https://api.themoviedb.org/3/movie/".$_GET['id']."/videos?api_key=".$api_key;
	$video_json=json_decode(file_get_contents($api_video_url),true);
	$movie_overview=$movie_json['overview'];
	$movie_year=substr($movie_json['release_dates']['results'][0]['release_dates'][0]['release_date'],0,4);
	$movie_cast='';
	$count=0;
	foreach ($movie_json['credits']['cast'] as $tmp){
		if($count==3) break;
		if($tmp['known_for_department']=='Acting'){
			$movie_cast=$movie_cast.', '.$tmp['name'];
			$count+=1;
		}
	}
	$movie_cast=substr($movie_cast,2).', more';
	$movie_genres='';
	foreach($movie_json['genres'] as $tmp){
		$movie_genres=$movie_genres.', '.$tmp['name'];
	}
	$movie_genres=substr($movie_genres,2);


	if(isset($_POST['subscribe'])){
		if(isset($_SESSION['username'])){
			$username=$_SESSION['username'];
			$movie_id=$_GET['id'];
			$subscribe_query="insert into subscriptions values('$username','$movie_id')";
			$subscribe_result=mysqli_query($conn,$subscribe_query);
		}
		else{
			echo "<script type='text/javascript'>window.alert('You need to be logged in to subscribe/unsubscribe to movies!');</script>";
		}
	}
	if(isset($_POST['unsubscribe'])){
		if(isset($_SESSION['username'])){
			$username=$_SESSION['username'];
			$movie_id=$_GET['id'];
			$unsubscribe_query="delete from subscriptions where username='$username' and movie_id='$movie_id'";
			$unsubscribe_result=mysqli_query($conn,$unsubscribe_query);
		}
		else{
			echo "<script type='text/javascript'>window.alert('You need to be logged in to subscribe/unsunscribe to movies!');</script>";
		}
	}
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
		 <div class="MovieTitle"><?php echo $movie_json['title'] ?></div>
		 <iframe class="MovieTrailer" src=<?php echo $youtube_link ?>></iframe>
		 		 <table class='MovieInfo'style='float:right;'>
		 	<colgroup>
				<col span='1' style='width:61%'>
				<col span='1' style='width:39%'>
			</colgroup>
			<tr>
				<td><?php echo $movie_year ?></td>
				<td>Cast: <?php echo $movie_cast ?></td>
			</tr>
			<tr>
				<td style='word-wrap:break-word;'><?php echo $movie_overview ?></td>
				<td>Genres: <?php echo $movie_genres ?></td>
			</tr>
		 </table>
		 <?php
			if(isset($_SESSION['username'])){
				$movie_id=$_GET['id'];
				$username=$_SESSION['username'];
				$check_subscription_query="select * from subscriptions where username='$username' and movie_id='$movie_id'";
				$check_subscription_result=mysqli_query($conn,$check_subscription_query);
				if(mysqli_num_rows($check_subscription_result)==1){
					echo "<form id='emptyForm' method='post'>
			      		     	    <input id='unsubcribeButton' type='submit' value='Unsubscribe' name='unsubscribe'>
		      			      </form>";
				}
				else{
					echo "<form id='emptyForm' method='post'>
			      		     	    <input id='subscribeButton' type='submit' value='Subscribe' name='subscribe'>
		      			      </form>";
				}
			}
			else{
				echo  "<form id='emptyForm' method='post'>
			      	      	     <input id='subscribeButton' type='submit' value='Subscribe' name='subscribe'>
		      		       </form>";
			}
		      ?>
		 <!--<pre><?php print_r($movie_json); ?></pre>-->
	</body>
</html>



<!--
 <div>
			<div class="Title"><?php echo $movie_json['title'] ?></div>
			<div style='opacity:0.33;position:fixed;z-index:-99;width:100%;height:480px;'>
			     <iframe width='100%' height='100%' src=<?php echo $youtube_link ?>></iframe>
			</div>
		 	<p class="MovieOverview"><?php print_r($movie_json['overview']) ?></p>
		 	<!--<pre><?php print_r($movie_json) ?></pre>
		 	<pre><?php print_r($video_json) ?></pre>-->
		 </div>