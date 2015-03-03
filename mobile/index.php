<!DOCTYPE html>
<html lang="en">
	<?php
	require '../api/includes/logged.php';
	$ignoress = true;
	require '../api/includes/connect.php';

	//TOFIX: player association
	$player = 1;
	$s = $m->query("SELECT * FROM `players` WHERE `playerId`='$player'") or die($m->error);
	$f = $s->fetch_array(MYSQLI_ASSOC);
	$startpause = ($f['Status'] == 1) ? "pause" : "";
	$m->close();
	?>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Ensemble</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="res/css/material.css">
		<link rel="stylesheet" href="res/css/player.css">
		<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic' rel='stylesheet' type='text/css'>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<style>
			/* Fixed footer.
			* --------------------------------------- */
			#footer {
				position:fixed;
				display:block;
				width: 100%;
				background: #333;
				z-index:9;
				color: #f2f2f2;
				margin: 0px auto;
				bottom:0px;
			}
		</style>
	</head>
	<?php 
		$data = file_get_contents("http://ensembleplayer.me/api/queue.php");
		$array = json_decode($data, true);

		if(count($array) == 0) {
			$title = "Nothing is in the Queue";
			$subtitle = "Use Music tab to play a song.";
			$album_art = "res/img/icon.png";
			$artist_art = "res/img/albums.jpg";
		} else {
			$title = $array[0]['title'];
			$artist = $array[0]['artist'];
			$album = $array[0]['album'];
			$service = $array[0]['service'];
			$user = $array[0]['user'];
			$album_art = $array[0]['album_art'];
			$artist_art = $array[0]['artist_art'];

			if($album_art == "") {
				$album_art = "res/img/icon.png";
			}

			if($artist_art == "") {
				$artist_art = "res/img/albums.jpg";
			}

			if($service == "YouTube" || $artist == "") {
				$subtitle = $artist . " - " . $user . " - " . $service;
				if($service == "YouTube")
					$album = "res/img/youtube.png";
			} else {
				$subtitle = $artist . " - " . $album . " - " . $user . " - " . $service;
			}
		}
	?>
	<body style="font-family: 'Roboto', sans-serif; margin-top:-20px;">
		<div id="footer">
			<img src=<?php echo '"' . $album_art . '"'; ?> width="60" height="60" style="float:left;"/>
			<a href="" class="play-btn <?=$startpause?> pull-right" style="margin-bottom: 10px;"></a> 
			<div style="margin-top: 20px; margin-left: 70px;">
				<p><span style="font-size: 18px; font-weight: 300; white-space: nowrap; overflow: hidden; width: auto;"><?php echo $title; ?></span></p>
			</div>
		</div>

		<div id="fullpage">
			<div class="section">
				<div style="position: fixed; top: 0; width: 100%;">
					<div style="background: #333; height: 50px; padding: 15px;">
						<div>
							<a style="text-decoration: none; color: #f2f2f2; font-size: 14px; font-weight: 400; padding-left: 10px; padding-right: 10px; border-bottom-style: solid; border-bottom-color: #e91e63;" href="index.php">Home</a>
							<a style="text-decoration: none; color: #f2f2f2; font-size: 14px; font-weight: 300; padding-left: 10px; padding-right: 10px;" href="music.php">Music</a>
							<a style="text-decoration: none; color: #f2f2f2; font-size: 14px; font-weight: 300; padding-left: 10px; padding-right: 10px;" href="settings.php">Settings</a>
							<a style="text-decoration: none; color: #f2f2f2; font-size: 14px; font-weight: 300; padding-left: 10px; padding-right: 10px;" href="logout.php">Logout</a>
						</div>
					</div>

					<div style="padding: 20px;">
						<?php if(count($array) == 0) { ?>
							<h3 style="text-align: center;">Nothing is in the Queue</h3>
						<?php } else { ?>
							<table class="table table-striped queue">
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Artist</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
						<?php 
							$id = 1;
							foreach ($array as $v) { $e = $v['id']; ?>
								<tr>
									<th><?php echo $id; ?></th>
									<td><?php echo $v['title']; ?> <?php if($id == 0) { ?><span class="label label-default">Now Playing</span><?php } ?></td>
									<td><?php echo $v['artist']; ?></td>
									<td>
										<a style="color:black;" href="/api/move.php?entryid=<?=$e?>&dir=0"><i class="fa fa-chevron-up fa-2x"></i></a> &nbsp;
										<a style="color:black;" href="/api/move.php?entryid=<?=$e?>&dir=1"><i class="fa fa-chevron-down fa-2x"></i></a> &nbsp;
										<a style="color:black;" href="/api/<?=(($id==1) ? 'pause.php"><i class="fa fa-pause' : 'skipto.php?entryid='.$e.'"><i class="fa fa-play')?> fa-2x"></i></a> &nbsp;
										<a style="color:black;" href="/api/duplicate.php?entryid=<?=$e?>"><i class="fa fa-repeat fa-2x"></i></a> &nbsp;
										<a style="color:black;" href="/api/delete.php?entryid=<?=$e?>"><i class="fa fa-trash fa-2x"></i></a>
									</td>
								</tr>
								<?php $id++;
								}
							} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<script src="res/js/material.js"></script>
	</body>
</html>