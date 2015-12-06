@extends('layouts.app')

@section('content')
<p>
    NAV:
    <a v-link="{ path: '/' }">Home</a> |
    <a v-link="{ path: '/add-topic' }">Add Topic</a>
</p>

<router-view></router-view>
@endsection
