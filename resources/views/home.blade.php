@extends('layouts.app')

@section('body')
    <div id="app">
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">{{ $appName }}</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav pull-right">
                        @if (Auth::user()->isAdmin())
                        <li><a v-link="{ path: '/episodes' }">Episodes</a></li>
                        <li><a v-link="{ path: '/suggested-topics' }">Suggested Topics</a></li>
                        <li><a v-link="{ path: '/accepted-topics' }">Accepted Topics</a></li>
                        <li><a v-link="{ path: '/suggest-topic' }">Add Topic</a></li>
                        @else
                        <li><a v-link="{ path: '/', exact: true }">Home</a></li>
                        <li><a v-link="{ path: '/suggest-topic' }">Suggest Topic</a></li>
                        @endif
                        <li><a href="/logout">Log out</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="content">
                <router-view :topics="topics" :episodes="episodes"></router-view>
            </div>
        </div>
    </div>
    <script src="/js/app.js"></script>
@endsection
