<ul class="dropdown-menu">
    @foreach ($tasks->status as $k => $v)
        <li><a href="#" data-type="{{ $v["bg"] }}" data-status="{{ $k }}">{{ $v["title"] }}</a></li>
    @endforeach
</ul>