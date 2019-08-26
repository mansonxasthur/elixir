@extends('layouts.app')

@section('content')
    <user-settings :user-collection="{{ $user }}"></user-settings>
@endsection
