@foreach ($tasks as $task)
    <div class="task row" id="{{ $task->id }}">
        <div class="prop title col-xs-12 col-sm-8 col-md-4">
            <span class="title-field">{{ $task->title }}</span>
            {!! Form::button('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', ['type' => 'button', 'class' => 'btn btn-default edit-task']) !!}
        </div>
        <div class="prop priority col-xs-12 col-sm-4 col-md-2">
            <div class="btn-group">
                {!! Form::button($task["priority"]["title"], ['class' => 'state-tag btn btn-' . $task["priority"]["bg"] . ' disabled', 'data-status' => $task["priority"]["status"]]) !!}
                {!! Form::button('<span class="caret"></span>', ['type' => 'button', 'class' => 'btn btn-default dropdown-toggle', 'data-toggle' => 'dropdown']) !!}
                @include('include.elements.priorityDropDown')
            </div>
        </div>
        <div class="prop tags col-xs-12 col-sm-8 col-md-4">
            <div class="input-tag input-group input-group-sm" style="display: none">
                {!! Form::text('tag', null, ['class' => 'form-control', 'placeholder' => __('page.tag')]) !!}
                <div class="input-group-btn">
                    {!! Form::button('<span class="glyphicon glyphicon-ok"></span>', ['class' => 'submit-tag btn btn-default']) !!}
                </div>
            </div>
            {!! Form::button('<span class="glyphicon glyphicon-plus"></span>', ['class' => 'add-tag btn btn-info btn-xs']) !!}
            @foreach ($task["tags"] as $tag)
                <div class="btn-group btn-group-xs">
                    {!! Form::button($tag, ['type' => 'button', 'class' => 'tag-item btn btn-default']) !!}
                    {!! Form::button('<span class="glyphicon glyphicon-remove"></span>', ['type' => 'button', 'class' => 'remove-tag btn btn-default']) !!}
                </div>
            @endforeach
        </div>
        <div class="prop status col-xs-12 col-sm-4 col-md-2">
            <div class="btn-group">
                {!! Form::button($task["status"]["title"], ['class' => 'state-tag btn btn-' . $task["status"]["bg"] . ' disabled', 'data-status' => $task["status"]["status"]]) !!}
                {!! Form::button('<span class="caret"></span>', ['type' => 'button', 'class' => 'btn btn-info dropdown-toggle', 'data-toggle' => 'dropdown']) !!}
                @include('include.elements.statusDropDown')
            </div>
        </div>
    </div>
@endforeach
