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
	<script type="text/javascript" src="jquery.scrollTo-min.js"></script>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/smoothness/jquery-ui.css" type="text/css" media="all" />
	<link rel="stylesheet" href="style/blueprint/screen.css" type="text/css" media="all" />
	<style>
		#AppContainer { 
			height: 100%;
		}

		#Header {
			margin-top: 1em;
			margin-bottom: 1em;
		}

		#Sidebar {
			position: relative;
			height: 100%;
		}

		#Sidebar h3 {;
			margin: 0;
			padding: 0;	
			cursor: pointer;
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

		#TemplatesContainer { 
			position: fixed;
			top: 22%;
			padding-left: 5px;
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
			float: right;
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
			cursor: pointer;
		}

		.TaskTitle, .ActionTitle, .WorkTitle { 
			float: left;
			width: 75%; 
		}

		input.NameInput {
			font-size: inherit;
			width: inherit;
			height: inherit;
			font-family: inherit;
			border: 0;
			background: #FFFFCC;
			text-align: inherit;
		}

		.TaskExpandCollapse { 
			float: right; 
			font-size: 75%;
		}

		#WorkSpace { 
			overflow: hidden;
			border-left: 1px solid #ccc;
			border-right: 1px solid #ccc;
			width: 788;
			height: 100%;
		}

		#InnerWorkSpace { 
			width: 2370px;
		}

		.Section { 
			float: left;
		}

		.SectionInner {
			padding-left: 5px;
		}
	</style>
	<script>
		var PS;
		$(function(){
			PS = {
				localTaskId: 0,
				name: 'Project Name',
				tasks: {},
				actions: {},
				works: {},

				_nameEditor: function(self) {
					return function() {
						if(!self.editing) {
							self.editing = true;
							$(this)
								.html(
									$('<input />')
										.addClass('NameInput')
										.css({height: $(this).height(), overflow: 'hidden'})
										.val(self.name)
										.blur(function(){
											$(this).replaceWith(self.name);
											self.editing = false;
										})
										.keypress(function(evt) {
											if(evt.keyCode == 13) {
												self.name = $(this).val();
												$(this).replaceWith(self.name);
												self.editing = false;
											}
											if(evt.keyCode == 27) {
												$(this).replaceWith(self.name);
												self.editing = false;
											}
										}));

							$('input', this).focus().select();
						}		
					};
				},

				task: function(id, name, view) {
					this.id = id;
					this.name = name;
					this.view = view;

					var self = this;

					this.view
						.html(
							$('<div />')
								.addClass('TaskTitle')
								.text(name)
								.dblclick(PS._nameEditor(self)))
				},

				newTask: function(taskEl) {
					var view = $('> div', taskEl);
					var id = 'task-' + PS.localTaskId++;
					return PS.tasks[id] = new PS.task(id, view.text(), view);
				},

				action: function(id, name, view) {
					this.id = id;
					this.name = name;
					this.view = view;

					var self = this;

					this.view
						.html(
							$('<div />')
								.addClass('ActionTitle')
								.text(name)
								.dblclick(PS._nameEditor(self)))
				},

				newAction: function(task, actionEl) {
					var view = $('> div', actionEl);
					var id = 'action-' + PS.localTaskId++;
					return PS.actions[id] = new PS.action(id, view.text(), view);
				},

				work: function(id, name, view) {
					this.id = id;
					this.name = name;
					this.view = view;

					var self = this;

					this.view
						.html(
							$('<div />')
								.addClass('WorkTitle')
								.text(name)
								.dblclick(PS._nameEditor(self)))
				},

				newWork: function(action, workEl) {
					var view = $('> div', workEl);
					var id = 'work-' + PS.localTaskId++;
					return PS.works[id] = new PS.work(id, view.text(), view);
				}

			};

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
							var taskNode = PS.newTask(task);

							task
								.append(
									$('<ul />')
										.sortable({
											revert: true, 
											items: '> li:not(.FirstActionItem)', 
											connectWith: '.Actions'
										})
										.droppable({
											accept: '.Action',
											drop: function(event, ui) {
												$('.FirstActionItem', this).remove();
												var action = $(ui.draggable);
												if(!action.data('children')) {
													var actionNode = PS.newAction(taskNode, action); 

													action
														.append(
															$('<ul />')
																.sortable({
																	revert: true,
																	items: '> li:not(.FirstWorkItem)',
																	connectWith: '.Works'
																})
																.droppable({
																	accept: '.Work',
																	drop: function(event, ui) {
																		$('.FirstWorkItem', this).remove();
																		var work = $(ui.draggable);
																		if(!work.data('init')) {
																			var workNode = PS.newWork(actionNode, work);
																			work.data('init', true);
																		}
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

			//$('ul, li').disableSelection();
			var collapseTemplates = function() {
				$('#ItemTemplates').css('overflow', 'hidden').animate({width: 0}, 200);
			};

			var expandTemplates = function() {
				$('#ItemTemplates').css('overflow', 'visible').animate({width: 190}, 300)
			};

			$('#ProjectMenuHeading').click(function(){
				$('#WorkSpace').scrollTo('#ProjectSection', 800, {onAfter: expandTemplates});
			});

			$('#UsersMenuHeading').click(function(){
				collapseTemplates();
				$('#WorkSpace').scrollTo('#UsersSection', 800);
			});

			$('#ContributionsMenuHeading').click(function(){
				collapseTemplates();
				$('#WorkSpace').scrollTo('#ContributionsSection', 800);
			})

			$('.ProjectName h1').click(PS._nameEditor(PS));
		});

	</script>
</head>
<body>
	<div class="container" id="AppContainer">
		<div class="span-24 first last" id="Header">
			<div class="span-4 first"><h1>PieShares</h1></div>
			<div class="span-17 ProjectName"><h1>Project Name</h1></div>
			<div class="span-3 last">Settings/User/Exit</div>
		</div>
		<div class="span-4 first" id="Sidebar">		
			<h3 id="ProjectMenuHeading">Project</h3>
			<div id="ProjectMenu">Project name etc.</div>
			<h3 id="UsersMenuHeading">Users</h3>
			<div id="UserMenu">
				<ul><li>username</li><li>username</li></ul>
			</div>
			<h3 id="ContributionsMenuHeading">Contributions</h3>
			<div id="ContributionMenu">
				<ul><li>Type of contrib.</li><li>Type of Contrib.</li></ul>
			</div>
		</div>
		<div id="WorkSpace" class="span-20 last">
			<div id="InnerWorkSpace">
				<div id="ProjectSection" class="span-20 first last Section">
					<div class="span-5 first">
				   	 	&nbsp;
				   	 	<div id="TemplatesContainer">
							<ul id="ItemTemplates" class="Tasks clear">
								<li class="Task"><div>Top Level Task</div></li>
								<li class="Action"><div>Action Name</div></li>
								<li class="Work"><div>Work Item</div></li>
							</ul>
						</div>
					</div>
					<div class="span-15 last">
						<ul id="ProjectTasks" class="Tasks">
							<li class="FirstItem">Drag first task here</li>
						</ul>
					</div>
				</div>
				<div id="UsersSection" class="span-20 first last Section">
					<div class="SectionInner">
					Stuff about users
					</div>
				</div>
				<div id="ContributionsSection" class="span-20 first last Section">
					<div class="SectionInner">
					Stuff about contributions
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</body>
</html>