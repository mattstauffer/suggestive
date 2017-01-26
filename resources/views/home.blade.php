@extends('layouts.app')

@section('body')
    <div id="app">
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <router-link to="{ path: '/' }" class="navbar-brand">{{ $appName }}</router-link>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav pull-right">
                        @if (Auth::user()->isAdmin())
                        <li><router-link to="{ path: '/episodes' }">Episodes</router-link></li>
                        <nav-dropdown>
                            <a href="#" class="dropdown-toggle">Topics <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><router-link to="{ path: '/suggested-topics' }">Suggested Topics</router-link></li>
                                <li><router-link to="{ path: '/accepted-topics' }">Accepted Topics</router-link></li>
                                <li><router-link to="{ path: '/suggest-topic' }">Add Topic</router-link></li>
                            </ul>
                        </nav-dropdown>
                        @else
                        <li><router-link to="{ path: '/', exact: true }">Topics</router-link></li>
                        @endif
                        <li><a href="/logout">Log out</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="content">
                <router-view
                    :topics="topics"
                    :episodes="episodes"
                 ></router-view>
            </div>
        </div>
    </div>
    <script src="/js/app.js"></script>
@endsection
