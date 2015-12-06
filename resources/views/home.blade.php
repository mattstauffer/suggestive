@extends('layouts.app')

@section('content')
<p>
    NAV:
    <a v-link="{ path: '/' }">Home</a> |
    <a v-link="{ path: '/add-topic' }">Add Topic</a>
</p>

<!-- use router-view element as route outlet -->
<router-view></router-view>
@endsection
