// ';' End any code that may be still open
// And '$' receive jQuery
;$(function() {
    // Define our prototype class
    $.todo = function(callback){
        // Check is the method exists, and called it
        if($.isFunction(callback)){
            return $.todo.ready(callback);
        }
    };
    // Extend our class
    $.extend($.todo,{
        // Init of out class
        init: function(){
            // Fetch all tasks
            $.todo.request.getTasks();

            // Bind the button to add a new task
            $.todo.bind.addNew();
        },
        variables : {
            // Get baseUrl defined on layout
            baseUrl : baseUrl
        },
        // Elements that has no needed of re-instance
        elements : {
            tasksList : $('ul.tasks'),
            addEl : $('button[name="add"]'),
            loadingEl : $('div.loading'),
        },
        // Generate HTMLs
        html : {
            // Generate <li> of each task
            task : function(){
                return ''
                + '<li class="list-group-item task" data-id="{id}" data-done="{done}">'
                    + '<div class="check">'
                        + '<input type="checkbox" name="check">'
                    + '</div>'
                    + '<div class="description">'
                        + '<div class="text">{description}</div>'
                        + '<div class="edit">'
                            + '<textarea name="description" placeholder="Press Enter and save it!" '
                                + 'onkeyup="$.todo.bind.autoAdjustTextArea(this);">'
                                + '{description}'
                            + '</textarea>'
                        + '</div>'
                    + '</div>'
                    + '<div class="delete">'
                        + '<button type="button" name="delete" class="btn btn-default btn-sm">'
                            + '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>'
                        + '</button>'
                    + '</div>'
                ;
            }
        },
        // Binds
        bind : {
            // Textarea auto adjustable (height)
            autoAdjustTextArea : function(o){
                o.style.height = "1px";
                o.style.height = (25+o.scrollHeight)+"px";
            },
            // Bind when the done input is checked, or unchecked
            done : function(id){
                task = $.todo.elements.tasksList.find('li.task[data-id="'+id+'"]');

                task.find('input[type="checkbox"][name="check"]').click(function(){
                    $this = $(this);

                    var li = $this.parents('li.task');

                    li.attr('data-done', $this.is(':checked') ? 1 : 0);

                    var id = li.attr('data-id'),
                        done = li.attr('data-done');

                    $.todo.request.setDone(id, done);
                    $.todo.action.updateTasks();
                });
            },
            // Bind of exclude button
            exclude : function(id){
                task = $.todo.elements.tasksList.find('li.task[data-id="'+id+'"]');

                task.find('button[name="delete"]').click(function(){
                    $this = $(this);

                    var li = $this.parents('li.task');

                    $.todo.request.exclude(id, function(){
                        li.remove();
                    });
                    $.todo.action.updateTasks();
                });
            },
            // Rewrite default behavior of textarea
            // For when users press Enter, we save it
            enterToSave : function(id){
                task = $.todo.elements.tasksList.find('li.task[data-id="'+id+'"]');

                task.find('textarea[name="description"]').keydown(function(e) {
                    $this = $(this);

                    var li = $this.parents('li.task'),
                        id = li.attr('data-id'),
                        code = e.keyCode;

                    if (code == 13){

                        if($this.val() != ''){
                            li.find('div.description div.text').html($this.val());
                            $.todo.action.save(id);
                        }

                        return false;
                    }
                });
            },
            // Bind when double click is detected on div description
            // So we can bring up the textarea for edit
            doubleClickEdit : function(id){
                task = $.todo.elements.tasksList.find('li.task[data-id="'+id+'"]');

                task.find('div.description').dblclick(function(){
                    $this = $(this);

                    var descriptionTextEl = $this.find('div.text'),
                        descriptionEditEl = $this.find('div.edit');

                    if(descriptionTextEl.is(':visible')){
                        descriptionTextEl.hide();
                        descriptionEditEl.show();
                    }
                });
            },
            // Bind to add new task
            addNew : function(){
                $.todo.elements.addEl.click(function(){

                    if($.todo.elements.tasksList.find('li.task[data-id="0"]').length == 0){
                        html = $.todo.html.task(),
                            taskHtml = html.replace(/{id}/g, 0)
                                .replace(/{done}/g, 0)
                                .replace(/{description}/g, '');

                        var params = {id: 0, done: false, text: ''};
                        $.todo.action.addToDo(params);

                        task = $.todo.elements.tasksList.find('li.task[data-id="0"]');

                        var descriptionBlock = task.find('div.description');
                        descriptionBlock.find('div.text').hide();
                        descriptionBlock.find('div.edit').show().find('textarea').focus();
                    }
                });
            },
        },
        // Actions that DO something other then keep binding
        action : {
            // Generate the new HTML, and bind it
            addToDo : function(params){
                html = $.todo.html.task(),
                task = html.replace(/{id}/g, params.id)
                    .replace(/{done}/g, params.done ? 1 : 0)
                    .replace(/{description}/g, params.text);

                $.todo.elements.tasksList.append(task);
                $.todo.bind.done(params.id);
                $.todo.bind.exclude(params.id);
                $.todo.bind.enterToSave(params.id);
                $.todo.bind.doubleClickEdit(params.id);
            },
            // Save the tasks changes
            save : function(id){
                newTask = $.todo.elements.tasksList.find('li.task[data-id="'+id+'"]');

                params = {
                    id: id,
                    description: newTask.find('textarea[name="description"]').val(),
                };

                $.todo.request.save(params, function(response){
                    newTask.attr('data-id', response.response.id);

                    var descriptionBlock = newTask.find('div.description');
                    descriptionBlock.find('div.text').show();
                    descriptionBlock.find('div.edit').hide();
                });
            },
            // Control if each task is showing their status right (done|undone)
            updateTasks : function(){
                tasks = $.todo.elements.tasksList.find('li.task');
                tasks.each(function(i,e){
                    $this = $(e);
                    var done = $this.attr('data-done') == 1 ? true : false;

                    if(done){
                        $this.addClass('done');
                        $this.find('input[type="checkbox"]').attr('checked', true);
                    }else{
                        $this.removeClass('done');
                        $this.find('input[type="checkbox"]').attr('checked', false);
                    }
                });
            },
        },
        // Make all requests
        request : {
            // Save changes on the task
            save : function(params, callback){
                $.todo.elements.loadingEl.show();
                $.post($.todo.variables.baseUrl+'/save-task', params, function(response){
                    callback(response);
                    $.todo.elements.loadingEl.hide();
                });
            },
            // Delete a task
            exclude : function(id, callback){
                $.todo.elements.loadingEl.show();
                $.post($.todo.variables.baseUrl+'/exclude-task', {id: id}, function(response){
                    if(response.response == true){
                        callback();
                    }
                    $.todo.elements.loadingEl.hide();
                });
            },
            // Change the status (done|undone)
            setDone : function(id, done){
                $.todo.elements.loadingEl.show();
                $.post($.todo.variables.baseUrl+'/set-task-done', {id: id, done: done}, function(response){
                    if(response.response != null){
                    }
                    $.todo.elements.loadingEl.hide();
                });
            },
            // Fetch all tasks
            getTasks : function(){
                $.todo.elements.loadingEl.show();
                $.get($.todo.variables.baseUrl+'/get-tasks', {}, function(response){
                    for(x in response.response){
                        var item = response.response[x];
                        $.todo.action.addToDo(item);
                    }

                    $.todo.action.updateTasks();
                    $.todo.elements.loadingEl.hide();
                });
            }
        }
    });
    $(function(){
        // Init the whole class
        $.todo.init();
    });
});