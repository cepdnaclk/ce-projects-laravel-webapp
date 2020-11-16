@extends('layouts.public')

@section('title','Project')


@section('content')


    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Post Content Column -->
            <div class="col-lg-8">

                <!-- Title -->
                <h1 class="mt-4">{{ $project->title }}</h1>

                <!-- Author | hidden for now -->
                <p class="d-none lead">
                    by <a href="#">Start Bootstrap</a>
                </p>

                <!-- Date/Time -->
                <div class="">
                    <div>
                        <span class="px-3"><a href="{{ URL::previous() }}">Back</a></span>
                        Started on {{ date_format(date_create( $project->created_at), 'Y-m-d') }}
                        <span class="px-3">Forks: <span class="">{{ $project->forks }}</span></span>
                        <span class="px-3"> Watchers: <span class="">{{ $project->watchers }}</span> </span>
                        <span class="px-3"> Stars: <span class="">{{ $project->stars }}</span> </span>
                    </div>
                    <hr>
                </div>

                <!-- Preview Image -->
                <div class="">
                    <img class="img-fluid rounded" src="{{ $project->image }}" alt="">
                </div>

                <hr>

                <!-- Post Content -->
                <p class="lead">{{ $project->description }}</p>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <a class="btn" href="{{ $project->repoLink }}" target="_blank">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <img src="/img/icon_repo.jpg" class="card-img p-3" alt="...">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body text-left">
                                            <h5 class="card-title">Project Repository</h5>
                                            <p class="card-text"><small class="text-muted">Visit >> </small></p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    @if($project->has_pages==true)
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <a class="btn" href="{{ $project->pageLink }}" target="_blank">
                                    <div class="row no-gutters">
                                        <div class="col-md-4">
                                            <img src="/img/icon_page.jpg" class="card-img p-3" alt="...">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body text-left">
                                                <h5 class="card-title">Project Page</h5>
                                                <p class="card-text"><small class="text-muted">Visit >> </small></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($project->has_wiki==true)

                    @endif
                </div>

            </div>

            <!-- Sidebar Widgets Column -->
            <div class="col-md-4">

                <!-- Search Widget -->
                <div class="d-none card my-4 pb-3">
                    <h5 class="card-header">Search</h5>
                    <div class="card-body">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search for..."/>
                            <span class="input-group-append">
                                <button class="btn btn-secondary"
                                        type="button">Go!</button>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Categories Widget -->

                <div class="card my-4 pb-3">
                    <h5 class="card-header">Languages</h5>
                    <div class="card-body">

                        <ul class="list-unstyled mb-0">
                            @foreach($langData['list'] as $key=>$value)
                                @php
                                    $percentage = 100.0*round( $value/$langData['total'] ,4 );
                                @endphp

                                @if($percentage>0.1)
                                    <li> {{ $key }} - {{ $percentage }} %</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Side Widget -->
                <div class="card my-4">
                    <h5 class="card-header">Contributors</h5>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($contributorData as $contributor)
                                <li><a href="#" target="_blank">[Image] {{ $contributor['username'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
