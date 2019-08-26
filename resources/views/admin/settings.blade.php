@extends('admin.layouts.app')

@section('content')
    <admin-settings :user-collection="{{ $user }}"></admin-settings>
@endsection
