@extends('layouts.public')

@section('title',"Project Categories")

@section('content')
    <div class="p-3">

        <p>This will show all available categories, once clicked on a category, it will go to a page that allow users to
            select a batch.</p>

        Ex:

        <ul>
            <li><a href="/category/2YP">2nd Year Projects</a></li>
            <li><a href="/category/Unified">Unified Projects</a></li>
            <li><a href="/category/FYP">Final Year Projects</a></li>
        </ul>


    </div>

@endsection
