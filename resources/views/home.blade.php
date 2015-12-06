@extends('layouts.app')

@section('content')
@if (Auth::user()->isAdmin())
    <admin-dashboard></admin-dashboard>
@else
    <user-dashboard></user-dashboard>
@endif
@endsection
