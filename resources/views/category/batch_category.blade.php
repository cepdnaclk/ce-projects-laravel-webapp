@extends('layouts.public')

@section('content')
    <div class="p-3">
        <p>This will list the categories under the given batch {{ $batch_id }}</p>

        Ex:
        <ul>
            <li><a href="/batch/{{ $batch_id }}/Unified">{{ $batch_id }}/Unified</a></li>
            <li><a href="/batch/{{ $batch_id }}/FYP">{{ $batch_id }}/FYP</a></li>
        </ul>

    </div>
@endsection



