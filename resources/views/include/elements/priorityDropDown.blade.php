<ul class="dropdown-menu">
    @foreach ($tasks->priority as $k => $v)
        <li><a href="#" data-type="{{ $v["bg"] }}" data-status="{{ $k }}">{{ $v["title"] }}</a></li>
    @endforeach
</ul>