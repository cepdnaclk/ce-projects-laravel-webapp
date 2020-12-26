@extends('layouts.public')

@section('title','Home')

@section('content')

    <!-- Page Content -->
    <div class="container">

        <!-- Jumbotron Header -->
        <header class="jumbotron my-2">
            <h1 class="d-none display-3">A Warm Welcome!</h1>
            <p class="lead">
                Welcome to the student project listing of the Department of Computer Engineering, University of
                Peradeniya. This website contains the documentation, code and other multimedia resources for the
                academic and extra curricular projects conducted by the students of the department.
            </p>
            <a href="#" class="d-none btn btn-primary btn-lg">View Project Categories!</a>
        </header>

        <!-- Page Features -->

        <h3 class="pt-3 pb-1">Course Projects</h3>
        <div class="row text-center my-2">
            @foreach($courseProj as $cat)
                <div class="col-lg-3 col-md-6 mb-2 d-flex">
                    <a class="btn" href="{{ route("category.show", $cat->category_code) }}">
                        <div class="card h-100">
                            <img class="card-img-top" src="{{ $cat->thumb_image }}" alt="">
                            <div class="card-body">
                                <h5 class="card-title">{{ $cat->title }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <h3 class="pt-3 pb-1">Department Projects</h3>
        <div class="row text-center my-4">
            @foreach($departProj as $cat)
                <div class="col-lg-3 col-md-6 mb-2 d-flex">
                    <a class="btn" href="{{ route("category.show", $cat->category_code) }}">
                        <div class="card h-100">
                            <img class="card-img-top" src="{{ $cat->thumb_image }}" alt="">
                            <div class="card-body">
                                <h5 class="card-title">{{ $cat->title }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

    </div>

    <footer class="d-none py-5 bg-dark">
        <div class="container">
            <p class="m-2 text-center text-white">Copyright &copy; Your Website 2020</p>
        </div>
    </footer>

@endsection
