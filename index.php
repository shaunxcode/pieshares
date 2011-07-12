<?php
	$file = isset($_GET['file']) ? $_GET['file'] : false;
	if(!$file) {
		header('location:index.php?file=' . uniqid());
		die();
	}

?>
<html>
<head>
	<title>PieShares</title>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/smoothness/jquery-ui.css" type="text/css" media="all" />
	<link rel="stylesheet" href="style/blueprint/screen.css" type="text/css" media="all" />
	<style>
		UL.Tasks { 
			margin: 0;
			padding: 0;
		}

		UL.Tasks li { 
			border: 1px solid #000;
			list-style-type: none;
			margin-bottom: .5em;

		}

		li.Task { 
			height: 62px;
		}

		li.Action { 
			height: 55px;
			background: #e4e4e3;
		}

		li.Work {
			height: 38px;
			background: #cbcccb;
		}

		#ItemTemplates { position: relative; padding-top: 50%;}
		#ItemTemplates li.Task {
			width: 128px;
		}

		#ItemTemplates li.Action {
			width: 104px;
		}

		#ItemTemplates li.Work {
			width: 81px;
		}
	</style>
	<script>
		
	</script>
</head>
<body>
	<div class="container">
		<div class="span-24 first last"><h1>PieShares</h1></div>
		<div class="span-5 first">
			<ul id="ItemTemplates" class="Tasks">
				<li class="Task">Top Level Task</li>
				<li class="Action">Action Name</li>
				<li class="Work">Work Item</li>
			</ul>
		</div>
		<div class="span-19 last">Workspace</div>
	</div>
</body>
</html>