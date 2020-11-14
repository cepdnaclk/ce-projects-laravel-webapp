@extends('layouts.public')

@section('title', "Docs")

@section('navibar')
    @include('includes.navibar')
@endsection

@section('content')
    <div class="p-5">
        This is the docs index page, will be listed all the available docs

        <p>Sample Pages</p>
        <ul>
            <li><a href="{{ route('docs.page', 'project-design') }}">Project Design</a></li>
            <li><a href="{{ route('docs.page', 'github_projects') }}">Current GitHub Project Page</a></li>
        </ul>
    </div>
@endsection
