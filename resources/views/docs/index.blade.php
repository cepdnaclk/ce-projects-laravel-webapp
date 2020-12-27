@extends('layouts.public')

@section('title', "Docs")

@section('navibar')
    @include('includes.navibar')
@endsection

@section('content')
    <div class="p-5">

        <h3>Links to Documentation Pages:</h3>
        <ul style="font-size: larger">
            <li><a target="_blank" href="https://github.com/cepdnaclk/projects.ce.pdn.ac.lk">Project Source Code</a></li>
            <li><a target="_blank" href="https://cepdnaclk.github.io/projects/deployments">Deployment Instructions</a></li>
            <li><a target="_blank" href="https://cepdnaclk.github.io/projects/developers">Contact information of original developers</a></li>
            <li><a target="_blank" href="https://cepdnaclk.github.io/projects/maintainers">Contact information of current maintainers</a></li>
            <li>&nbsp;</li>
            <li><a target="_blank" href="#">How to add a project ?</a></li>
            <li><a target="_blank" href="#">How to add a course ?</a></li>
            <li><a target="_blank" href="#">FAQ</a></li>
        </ul>
    </div>
@endsection
