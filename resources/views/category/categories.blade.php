@extends('layouts.public')

@section('title',"Project Categories")

@section('content')
    <div class="p-3">

        <p>This will show all available categories, once clicked on a category, it will go to a page that allow users to
            select a batch.</p>

        <ul>
        @foreach($data['repositories'] as  $repo)
                <li>
                    <a target="_blank" href="{{ route('project.show', $repo['fullName']) }}">
                        {{ $repo['batch'] }} | {{ $repo['category'] }} | {{ $repo['name'] }}
                    </a>
                </li>
        @endforeach
        </ul>


        Data:
        <pre>{{ json_encode($data,JSON_PRETTY_PRINT) }}</pre>

    </div>

@endsection
