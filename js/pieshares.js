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
							$('.ItemEditHover', self.view).remove();

							$(this)
								.html(
									$('<input />')
										.addClass('NameInput')
										.css({
											height: $(this).height(), 
											width: $(this).width(), 
											overflow: 'hidden'})
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

				_itemHover: function(self, toHeight) {
					return function(){
						var view = self.view;
						if(self.editing) {
							return;
						}

						$('> div', this)
							.append(
								$('<div />')
									.addClass('ItemEditHover')
									.append(
										$('<span />')
											.text(view.data('expanded') ? 'collapse' : 'expand')
											.click(function() {
												$('>.ItemInner, >.ItemInner>.ItemBot, >ItemInner>.ItemTop', view.parent().parent())
													.animate({
														height: view.data('expanded') ? toHeight : 200})
												
												if(!view.data('expanded')) {
													$(this).text('collapse');
													view.data('expanded', true);
												} else {
													$(this).text('expand');
													view.data('expanded', false);
												}
											}))
									.append(' | ')
									.append(
										$('<span />')
											.text('remove')
											.click(function(){
												
											})));
					}
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
						.hover(
							PS._itemHover(self, 58), 
							function(){
								$('.ItemEditHover', this).remove();
							})
				},

				newTask: function(taskEl) {
					var view = $('.ItemTop', taskEl);
					$('img', view).remove();
					var id = 'task-' + PS.localTaskId++;
					return PS.tasks[id] = new PS.task(id, 'Task', view);
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
						.hover(
							PS._itemHover(self, 48), 
							function(){
								$('.ItemEditHover', this).remove();
							})
				},

				newAction: function(task, actionEl) {
					var view = $('.ItemTop', actionEl);
					$('img', view).remove();
					var id = 'action-' + PS.localTaskId++;
					return PS.actions[id] = new PS.action(id, 'Action', view);
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
						.hover(
							PS._itemHover(self, 40), 
							function(){
								$('.ItemEditHover', this).remove();
							})

				},

				newWork: function(action, workEl) {
					var view = $('.ItemTop', workEl);
					$('img', view).remove();
					var id = 'work-' + PS.localTaskId++;
					return PS.works[id] = new PS.work(id, 'Work', view);
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
				$('#ItemTemplates').css('overflow', 'hidden').animate({width: 0}, 200, function(){
					$('#TemplatesContainer > span').hide();	
				});
			};

			var expandTemplates = function() {
				$('#TemplatesContainer > span').show();
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
