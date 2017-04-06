$(function () {
    //additional header for ajax requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //tasks grid, use it as container for events
    var $tasks = $('.tasks');

    //show/close new task form
    $('.add-task').on('click', function () {
        $('.add-form').toggleShown('slow');
    });

    //change priority and status of the task
    $tasks.on('click', '.priority a, .status a', function (e) {
        var $tag = $(this).parents('.btn-group').find('.state-tag'),
            status = $(this).data('status'),
            type = $(this).data('type'),
            $task = $(this).parents('.task'),
            id = $task.attr('id'),
            field = ($(this).parents('.prop').hasClass('priority')) ? 'priority' : 'status';

        $tag.removeClassByMask('btn-*').addClass('btn-' + type).text($(this).text()).data('status', status);

        //if existing task, perform ajax request with new priority/status and replace task on response respectively
        if (id.length > 0) {
            $.post('change', {id: id, field: field, value: status}, function(response) {
                if(response != '') {
                    $task.hide().detach().insertBefore('#' + response).show('slow');
                } else {
                    $task.hide().detach().appendTo('.tasks').show('slow');
                }
            });
        }

        e.preventDefault();
    });

    //show task title edit button
    $tasks.on('mouseenter', 'div.task .title', function () {
        if ($(this).hasClass('editor') === false)
            $(this).find('.edit-task').show();
    });

    //hide button
    $tasks.on('mouseleave', 'div.task .title', function() {
        $(this).find('.edit-task').hide();
    });

    //open an editor for task title
    $tasks.on('click', '.edit-task', function () {
        var $taskTitle = $(this).parent();

        $(this).hide();
        $('.editor').toggleEditor();
        $taskTitle.toggleEditor();
    });

    //show/hide new tag input
    $tasks.on('click', '.add-tag ', function () {
        $(this).parent().find('.input-tag').toggleShown(300);
    });

    //submit new tag and add it to a task
    $tasks.on('click', '.submit-tag', function () {
        var $inputTag = $(this).parents('.input-tag'),
            tag = $inputTag.find('[name=tag]').val(),
            $task = $(this).parents('.task'),
            id = $task.attr('id'),
            tags;

        if (tag.length > 0) {
            $(this).parents('.tags').appendTag(tag);

            //if existing task, perform ajax request with new tags array
            if (id.length > 0) {
                tags = $task.find('.tags').serializeTags();
                $.post('change', {id: id, field: 'tags', value: tags}, function () {
                    //
                });
            }

            //close new tag input and drop value
            $inputTag.toggleShown();
            $inputTag.find('[name=tag]').val('');
        }
    });

    //remove tag
    $tasks.on('click', '.remove-tag', function () {
        var $task = $(this).parents('.task'),
            id = $task.attr('id'),
            tags;

        //remove tag before assembling new tags array
        $(this).parent().remove();

        if (id.length > 0) {
            tags = $task.find('.tags').serializeTags();
            $.post('change', {id: id, field: 'tags', value: tags});
        }
    });

    //submit new title of an existing task
    $tasks.on('click', '.task:not(.add-form) .save-task', function () {
        var $task = $(this).parents('.task'),
            id = $task.attr('id'),
            value = $task.find('[name=title]').val();

        if (id.length > 0 && value.length > 0) {
            $.post('change', {id: id, field: 'title', value: value}, function (response) {
                if (response == "1") {
                    $('.editor').toggleEditor();
                }
            });
        }
    });

    //submit new task form and add a task with ajax response data
    $('.add-form .save-task').on('click', function (e) {
        var $addForm = $('.add-form'),
            $form = $(this).parents('.add-form'),
            title = $form.find('[name=title]').val(),
            priority = $form.find('.priority .state-tag').data('status'),
            tags = $form.find('.tags').serializeTags(),
            status = $form.find('.status .state-tag').data('status');

        if (title.length > 0) {
            $.post('create', {title: title, priority: priority, tags: tags, status: status}, function(response) {
                //if the task has no neighbour simply append it to a bottom of the grid
                if (response.neighbour != '') {
                    $('.task-new').clone().attr('id', response.id).insertBefore('#' + response.neighbour);
                } else {
                    $('.task-new').clone().attr('id', response.id).appendTo('.tasks');
                }

                var $newTask = $('#' + response.id),
                    $tags = $newTask.find('.tags'),
                    $priority = $newTask.find('.priority'),
                    $status = $newTask.find('.status'),
                    $priorityData = $priority.find('[data-status=' + response.priority +']'),
                    $statusData = $status.find('[data-status=' + response.status +']');

                //task title
                $newTask.find('.title-field').text(response.title);
                //task priority
                $priority.find('.state-tag')
                    .addClass('btn-' + $priorityData.data('type'))
                    .data('status', $priorityData.data('status'))
                    .text($priorityData.text());
                //task status
                $status.find('.state-tag')
                    .addClass('btn-' + $statusData.data('type'))
                    .data('status', $statusData.data('status'))
                    .text($statusData.text());
                //task tags
                $.each(response.tags, function (k, v) {
                    $tags.appendTag(v);
                });
                //show new task
                $newTask.show('slow').removeClass('task-new');
                //clear new task form
                $addForm.find('input').val('');
                $addForm.find('.tags .btn-group-xs').remove();
                $addForm.toggleShown();
            });
        }

        e.preventDefault();
    })
});

$.fn.removeClassByMask = function (mask) {
    return this.removeClass(function (index, cls) {
        var re = mask.replace(/\*/g, '\\S+');
        return (cls.match(new RegExp('\\b' + re + '', 'g')) || []).join(' ');
    });
};

$.fn.toggleEditor = function () {
    var title = '';

    if (this.hasClass('editor')) {
        var $titleForm = this.find('.input-group');

        title = $titleForm.find('input').val();
        $titleForm.detach();
        this.append('<span class="title-field">' + title + '</span>');
        this.removeClass('editor');
    } else {
        var $titleField = this.find('.title-field');

        title = $titleField.text();
        $titleField.detach();
        this.append(
            '<div class="input-group">' +
            '<input class="form-control" name="title" type="text" value="' + title + '">' +
            '<div class="input-group-btn">' +
            '<button class="btn btn-default save-task" type="button"><span class="glyphicon glyphicon-ok"></span></button>' +
            '</div>' +
            '</div>');
        this.addClass('editor');
    }
};

$.fn.toggleShown = function (speed) {
    if (this.hasClass('shown')) {
        this.hide(speed).removeClass('shown');
    } else {
        this.show(speed).addClass('shown');
    }
};

$.fn.serializeTags = function () {
    var $tagItems = this.find('.tag-item'),
        tags = {};
    $.each($tagItems, function (index, tag) {
        tags[index] = $(tag).text();
    });
    return tags;
};

$.fn.appendTag = function (tag) {
    return this.append(
        '<div class="btn-group btn-group-xs">' +
        '<button type="button" class="tag-item btn btn-default">' + tag + '</button>' +
        '<button type="button" class="remove-tag btn btn-default"><span class="glyphicon glyphicon-remove"></span></button>' +
        '</div>');
};