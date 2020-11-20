@extends('layouts.public')

@section('title','Home')

@section('content')

    <!-- Page Content -->
    <div class="container">

        <!-- Jumbotron Header -->
        <header class="jumbotron my-2">
            <h1 class="display-3">A Warm Welcome!</h1>
            <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa, ipsam, eligendi, in quo sunt
                possimus non incidunt odit vero aliquid similique quaerat nam nobis illo aspernatur vitae fugiat numquam
                repellat.</p>
            <a href="#" class="d-none btn btn-primary btn-lg">View Project Categories!</a>
        </header>

        <!-- Page Features -->
        <div class="row text-center my-4">
            @foreach($categories as $cat)
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
