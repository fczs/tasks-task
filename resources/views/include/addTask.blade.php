<button class="btn btn-lg btn-success add-task"><span class="glyphicon glyphicon-plus"></span></button>

<div class="task add-form row" id="">
    <div class="prop title col-xs-12 col-sm-8 col-md-4">
        <div class="input-group">
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('page.title')]) !!}
            <div class="input-group-btn">
                {!! Form::button('<span class="glyphicon glyphicon-ok"></span>', ['class' => 'btn btn-default save-task']) !!}
            </div>
        </div>
    </div>
    <div class="prop priority col-xs-12 col-sm-4 col-md-2">
        <div class="btn-group">
            {!! Form::button(__('page.lowPriority'), ['class' => 'state-tag btn btn-success disabled', 'data-status' => '0']) !!}
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
    </div>
    <div class="prop status col-xs-12 col-sm-4 col-md-2">
        <div class="btn-group">
            {!! Form::button(__('page.statusProcess'), ['class' => 'state-tag btn btn-primary disabled', 'data-status' => '0']) !!}
            {!! Form::button('<span class="caret"></span>', ['type' => 'button', 'class' => 'btn btn-info dropdown-toggle', 'data-toggle' => 'dropdown']) !!}
            @include('include.elements.statusDropDown')
        </div>
    </div>
</div>