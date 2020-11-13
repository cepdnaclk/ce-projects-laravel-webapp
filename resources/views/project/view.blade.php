@extends('layouts.public')

@section('title','Project')


@section('content')


    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Post Content Column -->
            <div class="col-lg-8">

                <!-- Title -->
                <h1 class="mt-4">{{ $data['name'] }}</h1>

                <!-- Author | hidden for now -->
                <p class="d-none lead">
                    by <a href="#">Start Bootstrap</a>
                </p>

                <!-- Date/Time -->
                <div class="">
                    <div>
                        Started on {{ $data['created_at'] }}
                        <span class="px-3">Forks: <span class="">{{ $data['forks'] }}</span></span>
                        <span class="px-3"> Watchers: <span class="">{{ $data['watchers'] }}</span> </span>
                        <span class="px-3"> Stars: <span class="">N/A</span> </span>
                    </div>
                    <hr>
                </div>

                <!-- Preview Image -->
                <div class="">
                    <img class="img-fluid rounded" src="http://placehold.it/900x300" alt="">
                </div>

                <hr>

                <!-- Post Content -->
                <p class="lead">{{ $data['description'] }}</p>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <a class="btn" href="{{ $data['repoLink'] }}" target="_blank">
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

                    @if($data['has_pages']==true)
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <a class="btn" href="{{ $data['pageLink'] }}" target="_blank">
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

                    @if($data['has_wiki']==true)

                    @endif
                </div>


                <!-- Comments Form -->
                <div class="d-none card my-4">
                    <h5 class="card-header">Leave a Comment:</h5>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <textarea class="form-control" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>

                <!-- Single Comment -->
                <div class="d-none media mb-4">
                    <img class="d-flex mr-3 rounded-circle" src="http://placehold.it/50x50" alt="">
                    <div class="media-body">
                        <h5 class="mt-0">Commenter Name</h5>
                        Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin.
                        Cras
                        purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac
                        nisi
                        vulputate fringilla. Donec lacinia congue felis in faucibus.
                    </div>
                </div>

                <!-- Comment with nested comments -->
                <div class="d-none media mb-4">
                    <img class="d-flex mr-3 rounded-circle" src="http://placehold.it/50x50" alt="">
                    <div class="media-body">
                        <h5 class="mt-0">Commenter Name</h5>
                        Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin.
                        Cras
                        purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac
                        nisi
                        vulputate fringilla. Donec lacinia congue felis in faucibus.

                        <div class="media mt-4">
                            <img class="d-flex mr-3 rounded-circle" src="http://placehold.it/50x50" alt="">
                            <div class="media-body">
                                <h5 class="mt-0">Commenter Name</h5>
                                Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante
                                sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
                                Fusce
                                condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in
                                faucibus.
                            </div>
                        </div>

                        <div class="media mt-4">
                            <img class="d-flex mr-3 rounded-circle" src="http://placehold.it/50x50" alt="">
                            <div class="media-body">
                                <h5 class="mt-0">Commenter Name</h5>
                                Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante
                                sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
                                Fusce
                                condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in
                                faucibus.
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Sidebar Widgets Column -->
            <div class="col-md-4">

                <!-- Search Widget -->
                <div class="card my-4 pb-3">
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
                @if( $data['languages']['count']>0 )

                    <div class="card my-4 pb-3">
                        <h5 class="card-header">Languages</h5>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @foreach($data['languages']['list'] as $lang => $usage)
                                    <li>{{ $lang }} - {{ 100*round($usage/$data['languages']['total'],2) }} %</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
            @endif

            <!-- Side Widget -->
                <div class="card my-4">
                    <h5 class="card-header">Team Members</h5>
                    <div class="card-body">

                        <ul class="list-unstyled mb-0">
                            @if($data['contributors']['count']>0)

                                @foreach($data['contributors']['list'] as $contributor)
                                    <li><a href="{{ $contributor['url'] }}"
                                           target="_blank">[Image] {{ $contributor['username'] }}</a></li>
                                @endforeach
                            @endif
                        </ul>

                    </div>
                </div>

            </div>

        </div>
        <!-- /.row -->

    </div>
    <!-- /.container -->


    <div class="p-3 pt-5">
        <hr>

        This will show a specific project

        Project: {{ $title }}<br>

        <a href="{{ $data['repoLink'] }}" target="_blank">GitHub Repository</a> |
        <a href="{{ $data['pageLink'] }}" target="_blank">GitHub Page</a><br>


        Data:
        <pre>{{ json_encode($data,JSON_PRETTY_PRINT) }}</pre>


        Suggested Templates:
        https://startbootstrap.com/template/blog-post


    </div>
@endsection
