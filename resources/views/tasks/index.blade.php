@extends('layouts.app')

@section('content')
    <tasks-component :tasks="{{ $tasks }}" :lists="{{ $lists }}"></tasks-component>
@endsection
