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
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js"></script>
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
			padding: .5em;
			cursor: pointer;
		}

		li.Task {
			height: 2.5em;
			background: #fff;
			font-size: 140%;
		}

		li.Action { 
			height: 2.25em;
			background: #e4e4e3;
			font-size: 120%;
		}

		li.Work {
			height: 2em;
			background: #cbcccb;
			font-size: 110%;
		}

		#ItemTemplates { 
			position: fixed;
			top: 33%;
		}

		#ItemTemplates li { 
			text-align: right;
		}

		#ItemTemplates li.Task {
			width: 128px;
		}

		#ItemTemplates li.Action {
			width: 104px;
		}

		#ItemTemplates li.Work {
			width: 81px;
		}

		#ProjectTasks { 
			padding:.5em;
			width: 400px;
		}

		#ProjectTasks > li.FirstItem { 
			border: 1px dashed #000;
			height: 2.5em;
			padding: .5em;
			font-size: 140%;
		}
	</style>
	<script>
		$(function(){
			$('#ProjectTasks')
				.sortable({
					revert: true,
					items: 'li:not(.FirstItem)'
				})
				.droppable({
					drop: function(event, ui) {
						$('.FirstItem', this).remove()
					}
				});

			$('#ItemTemplates li')
				.draggable({
					revertDuration: 250,
					connectToSortable: '#ProjectTasks',
					helper: 'clone',
					revert: 'invalid'
				});

			$('ul, li').disableSelection();
		})
	</script>
</head>
<body>
	<div class="container">
		<div class="span-24 first last"><h1>PieShares</h1></div>
		<div class="span-5 first">
		    &nbsp;
			<ul id="ItemTemplates" class="Tasks clear">
				<li class="Task">Top Level Task</li>
				<li class="Action">Action Name</li>
				<li class="Work">Work Item</li>
			</ul>
		</div>
		<div class="span-19 last">
			<ul id="ProjectTasks" class="Tasks">
				<li class="FirstItem">Drag first task here</li>
			</ul>
		</div>
	</div>
</body>
</html>