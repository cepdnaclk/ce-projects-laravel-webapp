@extends('layouts.public')

@section('content')
    <div class="p-3">
        This will show a specific project

        batch: {{ $batch_id }}, category: {{ $category_title }}

    </div>
@endsection
