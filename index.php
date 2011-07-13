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
		#Header {
			margin-top: 1em;
			margin-bottom: 1em;
		}

		#Sidebar h3 {
			margin: 0;
			padding: 0;	
		}

		UL.Tasks { 
			margin: 0;
			padding: 0;
		}

		UL.Tasks li {
			list-style-type: none;
			margin-bottom: .5em;
		}

		UL.Tasks li > div { 
			border: 1px solid #000;
			padding: .5em;
			cursor: pointer;
		}

		UL.Actions { 
			margin-top: .5em;
		}

		UL.Works {
			margin-top: .5em;
		}

		li.Task > div {
			height: 2.5em;
			background: #fff;
			font-size: 140%;
		}

		li.Action > div { 
			height: 2.25em;
			background: #e4e4e3;
			font-size: 120%;
		}

		li.Work > div {
			height: 2em;
			background: #cbcccb;
			font-size: 110%;
		}

		#ItemTemplates { 
			position: fixed;
			top: 33%;
		}

		#ItemTemplates li > div { 
			text-align: right;
		}

		#ItemTemplates li.Task > div {
			width: 128px;
		}

		#ItemTemplates li.Action > div { 
			width: 104px;
		}

		#ItemTemplates li.Work > div{
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

		#ProjectTasks li.FirstActionItem {
			border: 1px dashed #000;
			height: 2.25em;
			padding: .5em;
			font-size: 120%;
		}

		#ProjectTasks li.FirstWorkItem {
			border: 1px dashed #000;
			height: 2.25em;
			padding: .5em;
			font-size: 120%;
		}

		li.Task > ul { 
			
		}

		.ProjectName {
			font-weight: bold;
			text-align: center;
		}

	</style>
	<script>
		$(function(){
			$('#ProjectTasks')
				.sortable({
					revert: true,
					items: '> li:not(.FirstItem)'
				})
				.droppable({
					accept: '.Task',
					drop: function(event, ui) {
						$('.FirstItem', this).remove();
						var task = $(ui.draggable);
						if(!task.data('children')) {
							task
								.append(
									$('<ul />')
										.sortable({
											revert: true, 
											items: '> li:not(.FirstActionItem)'
										})
										.droppable({
											accept: '.Action',
											drop: function(event, ui) {
												$('.FirstActionItem', this).remove();
												var action = $(ui.draggable);
												if(!action.data('children')) {
													action
														.append(
															$('<ul />')
																.sortable({
																	revert: true,
																	items: '> li:not(.FirstWorkItem)'
																})
																.droppable({
																	accept: '.Work',
																	drop: function(event, ui) {
																		$('.FirstWorkItem', this).remove();
																	}
																})
																.addClass('Works')
																.append(
																	$('<li />')
																		.addClass('FirstWorkItem')
																		.text('Drag Work Item Here')))
														.data('children', true);
												}
											}
										})
										.addClass('Actions')
										.append(
											$('<li />')
												.addClass('FirstActionItem')
												.text('Drag Action Here')))
								.data('children', true);
						}
					}
				});

			$('#ItemTemplates li.Task')
				.draggable({
					revertDuration: 250,
					connectToSortable: '#ProjectTasks',
					helper: 'clone',
					revert: 'invalid'
				});

			$('#ItemTemplates li.Action')
				.draggable({
					revertDuration: 250,
					connectToSortable: '.Actions',
					helper: 'clone',
					revert: 'invalid'
				});

			$('#ItemTemplates li.Work')
				.draggable({
					revertDuration: 250,
					connectToSortable: '.Works',
					helper: 'clone',
					revert: 'invalid'
				});

			$('ul, li').disableSelection();
		})
	</script>
</head>
<body>
	<div class="container">
		<div class="span-24 first last" id="Header">
			<div class="span-4 first"><h1>PieShares</h1></div>
			<div class="span-17 ProjectName"><h1>Project Name</h1></div>
			<div class="span-3 last">Settings/User/Exit</div>
		</div>
		<div class="span-4 first" id="Sidebar">
			<h3>Project</h3>
			<div id="ProjectMenu">Project name etc.</div>
			<h3>Users</h3>
			<div id="UserMenu">
				<ul><li>username</li><li>username</li></ul>
			</div>
			<h3>Contributions</h3>
			<div id="ContributionMenu">
				<ul><li>Type of contrib.</li><li>Type of Contrib.</li></ul>
			</div>
		</div>
		<div class="span-20 last">
			<div class="span-5 first">
		   	 &nbsp;
				<ul id="ItemTemplates" class="Tasks clear">
					<li class="Task"><div>Top Level Task</div></li>
					<li class="Action"><div>Action Name</div></li>
					<li class="Work"><div>Work Item</div></li>
				</ul>
			</div>
			<div class="span-15 last">
				<ul id="ProjectTasks" class="Tasks">
					<li class="FirstItem">Drag first task here</li>
				</ul>
			</div>
		</div>
	</div>
</body>
</html>