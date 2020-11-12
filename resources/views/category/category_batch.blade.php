@extends('layouts.public')

@section('title',"Project Batches")

@section('content')
    <div class="p-3">

        <p>This will list the batches under given category, {{ $category_title }}</p>

        Ex:

        <ul>
            <li><a href="/category/{{ $category_title }}/e14">{{ $category_title }}/e14</a></li>
            <li><a href="/category/{{ $category_title }}/e15">{{ $category_title }}/e15</a></li>
        </ul>

    </div>

@endsection
