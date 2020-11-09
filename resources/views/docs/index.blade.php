@extends('layouts.public')

@section('title', "Docs")

@section('content')
    <div class="p-3">
        This is the docs index page, will be listed all the available docs

        <p>Sample Pages</p>
        <ul>
            <li><a href="{{ route('docs.page', 'project-design') }}">Project Design</a></li>
        </ul>
    </div>
@endsection
