@extends('layouts.app')

@section('content')
    <create-task-component :lists="{{ $lists }}"></create-task-component>
@endsection
