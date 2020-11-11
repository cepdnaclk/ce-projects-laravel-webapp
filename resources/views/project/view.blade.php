@extends('layouts.public')

@section('title','Project')

@section('content')
    <div class="p-3">
        This will show a specific project

        Project: {{ $title }}<br>

        <a href="{{ $data['repoLink'] }}" target="_blank">GitHub Repository</a> |
        <a href="{{ $data['pageLink'] }}" target="_blank">GitHub Page</a><br>


        Data:
        <pre>{{ json_encode($data,JSON_PRETTY_PRINT) }}</pre>



    </div>
@endsection
