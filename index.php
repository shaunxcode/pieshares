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
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/smoothness/jquery-ui.css" type="text/css" media="all" />
	<link rel="stylesheet" href="style/blueprint/screen.css" type="text/css" media="all" />
	<link rel="stylesheet" href="style/pieshares.css" type="text/css" media="all" />

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js"></script>
	<script type="text/javascript" src="jquery.scrollTo-min.js"></script>
	<script type="text/javascript" src="js/pieshares.js"></script>
</head>
<body>
	<div id="Header">
		<div class="container">
			<div class="span-4 first"><img src="images/logo-1.png" alt="PieShares"/></div>
			<div class="span-16 ProjectName"><h1>Project Name</h1></div>
			<div class="span-4 last">
				<div id="UserSettingsMenu">
					<img src="images/icon-user.png" alt="profile" />
					<img src="images/icon-settings.png" alt="settings" />
					<img src="images/icon-exit.png" alt="logout" />
				</div>
			</div>
		</div>
	</div>

	<div class="container" id="AppContainer">
		<div class="span-4 first last" id="Sidebar">		
			<h3 id="ProjectMenuHeading">Project Details</h3>
				<div id="ProjectMenu">
					<ul>
						<li>Overview</li>
						<li>Task Tree</li>
						<li>Users(n)</li>
						<li>Items<ul>
							<li>Tasks(n)</li>
							<li>Actions(n)</li>
							<li>Work(n)</li></ul><li>
					</ul>
				</div>
			<h3 id="UsersMenuHeading">Active Users</h3>
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
				   	 		<span>Drag n' Drop</span>
							<ul id="ItemTemplates" class="Tasks clear">
								<li class="Task">
									<div class="ItemInner">
										<div class="ItemBot"></div>
										<div class="ItemTop">
											<span>Task</span> 
											<img src="images/handle.png" alt="handle"/>
										</div>
									</div>
								</li>
								<li class="Action">
									<div class="ItemInner">
										<div class="ItemBot"></div>
										<div class="ItemTop">
											<span>Action</span> 
											<img src="images/handle.png" alt="handle" />
										</div>
									</div>
								</li>
								<li class="Work">
									<div class="ItemInner">
										<div class="ItemBot"></div>
										<div class="ItemTop">
											<span>Work</span> 
											<img src="images/handle.png" alt="handle" />
										</div>
									</div>
								</li>
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
	</div>
	<div id="Footer"></div>	
</body>
</html>