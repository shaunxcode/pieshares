		var PS;
		$(function(){
			PS = {
				actingAsUserName: 'Erik F.',
				localTaskId: 0,
				name: 'Project Name',
				userCount: 1,
				taskCount: 0,
				actionCount: 0,
				workCount: 0,
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

				_itemHover: function(self, itemType, toHeight) {
					return function(){
						var view = self.view;
						if(self.editing) {
							return;
						}

						$('> div:first', this)
							.append(
								$('<div />')
									.addClass('ItemEditHover')
									.append(
										$('<span />')
											.text(view.data('expanded') ? 'collapse' : 'expand')
											.click(function() {
												$('>.ItemInner, >.ItemInner>.ItemBot, >ItemInner>.ItemTop', view.parent().parent())
													.animate({
														height: view.data('expanded') ? toHeight : 200 + toHeight})
												
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
												PS['remove' + itemType](self.id);
											})));
					}
				},

				updateCounts: function() {
					$('#UserCount').text(PS.userCount);	
					$('#TaskCount').text(PS.taskCount);		
					$('#ActionCount').text(PS.actionCount);		
					$('#WorkCount').text(PS.workCount);		
				},

				task: function(id, name, view) {
					this.id = id;
					this.name = name;
					this.view = view;
					this.actions = [];

					var self = this;

					
					this.view
						.html(
							$('<div />')
								.addClass('TaskTitle')
								.text(name)
								.dblclick(PS._nameEditor(self)))
						.hover(
							PS._itemHover(self, 'Task', 58), 
							function(){
								$('.ItemEditHover').remove();
							})
						.append(
							$('<span />')
								.addClass('CreatedBy')
								.text('Created By: ' + PS.actingAsUserName))
						.append(
							$('<div />')
								.css({position: 'absolute', top: 60})
								.html(
									$('<textarea />')));
				},

				newTask: function(taskEl) {
					var view = $('.ItemTop', taskEl);
					$('img', view).remove();
					var id = 'task-' + PS.localTaskId++;
					PS.taskCount++;
					PS.updateCounts();
					return PS.tasks[id] = new PS.task(id, 'Task', view);
				},


				removeTask: function(taskId) {
					if(PS.tasks[taskId]) {
						$.each(PS.tasks[taskId].actions, function(i, actionId){
							PS.removeAction(actionId);	
						});

						PS.taskCount--;
						PS.tasks[taskId].view.parent().parent().remove();
						delete PS.tasks[taskId];

						if($('#ProjectTasks').children().length == 0) {
							$('#ProjectTasks').append(TaskItemPlaceHolder());
						}

						PS.updateCounts();
					}
				},

				action: function(id, name, view) {
					this.id = id;
					this.name = name;
					this.view = view;
					this.works = [];

					var self = this;

					this.view
						.html(
							$('<div />')
								.addClass('ActionTitle')
								.text(name)
								.dblclick(PS._nameEditor(self)))
						.hover(
							PS._itemHover(self, 'Action', 48), 
							function(){
								$('.ItemEditHover').remove();
							})
				},

				newAction: function(task, actionEl) {
					var view = $('.ItemTop', actionEl);
					$('img', view).remove();
					var id = 'action-' + PS.localTaskId++;
					task.actions.push(id);

					PS.actionCount++;
					PS.updateCounts();
					return PS.actions[id] = new PS.action(id, 'Action', view);
				},

				removeAction: function(actionId) {
					
					if(PS.actions[actionId]) {
						$.each(PS.actions[actionId].works, function(i, workId){
							PS.removeWork(workId);	
						});

						PS.actionCount--;
						var list = PS.actions[actionId].view.parent().parent().parent();
						PS.actions[actionId].view.parent().parent().remove();
						delete PS.actions[actionId];

						if(list.children().length == 0) {
							list.append(ActionItemPlaceHolder());
						}

						PS.updateCounts();
					}
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
							PS._itemHover(self, 'Work', 40), 
							function(){
								$('.ItemEditHover').remove();
							})

				},

				newWork: function(action, workEl) {
					var view = $('.ItemTop', workEl);
					$('img', view).remove();
					var id = 'work-' + PS.localTaskId++;
					action.works.push(id);

					PS.workCount++;
					PS.updateCounts();
					return PS.works[id] = new PS.work(id, 'Work', view);
				},

				removeWork: function(workId) {
					
					if(PS.works[workId]) {
						PS.workCount--;
						var list = PS.works[workId].view.parent().parent().parent();
						PS.works[workId].view.parent().parent().remove();
						delete PS.works[workId];

						if(list.children().length == 0) {
							list.append(WorkItemPlaceHolder());
						}

						PS.updateCounts();
					}
				},

			};

			var TaskItemPlaceHolder = function() {
				return $('<li />')
					.addClass('FirstItem')
					.text('Drag Task Here');
			};

			var ActionItemPlaceHolder = function() {
				return $('<li />')
					.addClass('FirstActionItem')
					.text('Drag Action Here');
			};

			var WorkItemPlaceHolder = function() { 
				return $('<li />')
					.addClass('FirstWorkItem')
					.text('Drag Work Item Here');
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
											connectWith: '.Actions',
											stop: function(event, ui) {
											
											},
											remove: function(evt, ui) {
												var oldList = $(evt.target);
												if(oldList.children().length == 0) {
													oldList.append(ActionItemPlaceHolder())
												}												
											}

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
																	connectWith: '.Works',
																	receive: function(evt, ui) {
																		$('.FirstWorkItem', $(ui.item).parent()).remove();
																	}, 
																	remove: function(evt, ui) {
																		var oldList = $(evt.target);
																		if(oldList.children().length == 0) {
																			oldList.append(WorkItemPlaceHolder())
																		}
																	}
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
																.append(WorkItemPlaceHolder()))
														.data('children', true);
												}
											}
										})
										.addClass('Actions')
										.append(ActionItemPlaceHolder()))
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

			PS.updateCounts();
		});
