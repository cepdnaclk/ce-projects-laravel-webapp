@extends('layouts.public')

@section('title', "Docs - ".$data['title'])

@section('navibar')
    @include('includes.navibar')
@endsection

@section('content')
    <div>
    <!--This is a doc page with given title, {{ $data['title'] }}<br>
        This will render the page <a href="{{ $data['link'] }}">{{ $data['link'] }}</a>-->

        <div class="container d-flex justify-content-center" style="width: 100vw !important; height: 70vh;">
            <iframe class="embed-responsive-item"
                    style="width: 230mm !important; height: 80vh; border: 1px solid gray;"
                    src="{{ $data['link'] }}" allowfullscreen
                    onload="resizeIframe(this)">
            </iframe>
        </div>

    </div>

    <script>
        function resizeIframe(obj) {
            //obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
        }
    </script>

@endsection
