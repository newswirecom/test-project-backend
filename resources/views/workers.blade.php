@extends('html')

@section('body')

    <ul>
        @foreach ($workers as $worker)
        <li>
            <a href="switch-worker?id={{ $worker->id }}">{{ $worker->name }}</a>
            <div><small>{{ $worker->email }}</small></div>
        </li>
        @endforeach
    </ul>

@endsection
