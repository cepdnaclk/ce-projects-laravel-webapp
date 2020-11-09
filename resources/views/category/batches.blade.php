@extends('layouts.public')

@section('title',"Batches")

@section('content')
    <div class="p-3">
        <p>This will show batches of the department, and once clicked on a batch, it will show the projects done under
            that batch</p>

        Ex:

        <ul>
            <li><a href="/batch/e14">E14</a></li>
            <li><a href="/batch/e15">E15</a></li>
            <li><a href="/batch/e16">E16</a></li>
        </ul>

    </div>

@endsection
