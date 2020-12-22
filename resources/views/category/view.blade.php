@extends('layouts.public')

@section('title', $category->title)

@section('content')
    <div class="p-3">

        <div class="row">
            <div class="col-md-3">
                <h3>{{ $category->title }}</h3>

                <p>{{ $category->description }}</p>

                @if($category->type=='COURSE')
                    {{-- Course projects --}}

                    @if($subtitle != '')
                        {{-- with batch filter  --}}
                        <div class="container m-0 p-0">
                            <div class="p-1"><a href="{{ route('category.show', [$category->category_code]) }}">Back</a>
                                | {{ $project_count }} Projects
                            </div>
                            <hr>
                        </div>

                    @elseif($subtitle == '')

                        <div class="p-1"><a href="{{ route('home') }}">Back</a>
                            | {{ $project_count }} Projects<br>
                        </div>
                        <hr>
                        <div class="p-1">
                            Filter Projects by:

                            <ul class="list-group">
                                @foreach($batches as $key=>$value)
                                    <li class="list-group-item p-1 align-items-center">
                                        <a href="{{ route('category.batch', [$category->category_code, $value->batch]) }}"
                                           class="d-flex justify-content-between">
                                            <span class="mx-2">{{ $value->batch }} Batch</span>
                                            <span
                                                class="mx-2 badge badge-secondary badge-pill">{{ $value->count }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <br><br>

                    @else
                        <div class="p-1"><a href="{{ route('home')}}">Back</a>
                            | {{ $project_count }} Projects
                        </div>
                        <br><br>
                    @endif
                @else
                    {{-- non-course projects --}}

                    <div class="p-1"><a href="{{ route('home') }}">Back</a>
                        | {{ $project_count }} Projects
                    </div>

                @endif
            </div>

            <div class="col-md-9">

                <div class="d-none row">
                    <!-- Preview Image -->
                    <div class="container">
                        <img class="img-fluid rounded" src="http://placehold.it/900x300" alt="">
                    </div>
                </div>

                <div class="container p-3 mw-100">
                    <div class="row">

                        @if($projects->count() > 0)
                            {{-- @foreach($projects->reverse() as $proj)--}}
                            @foreach($projects->shuffle() as $proj)

                                <div class="col-lg-3 col-md-6 d-flex">
                                    <a class="btn" href="{{ route("project.show", $proj->name) }}">
                                        <div class="card h-100 m-0">
                                            <img class="card-img-top" src="{{ $proj->thumbnail }}" alt="">
                                            <div class="card-body p-0 m-1">

                                                @if($proj->batch)
                                                    <p class="card-title">{{ $proj->title }} ({{ $proj->batch }})</p>
                                                @else
                                                    <p class="card-title">{{ $proj->title }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach

                        @else

                            <div class="text-center p-5" style="width: 100%;">
                                <h3>No projects available<br><small>Please come back later</small></h3>
                            </div>

                        @endif
                    </div>
                </div>
                @if($batches != null)
                    <div class="container my-4 d-flex justify-content-center">
                        {{ $projects->links() }}
                    </div>
                @endif

            </div>

        </div>


    </div>

@endsection
