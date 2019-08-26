@extends('admin.layouts.app')

@section('content')
    <v-container pa-5>
        <v-layout column justify-center>
            <v-flex>
                <admins :admin-collection="{{ $admins }}" :role-collection="{{ $roles }}"></admins>
            </v-flex>
        </v-layout>
    </v-container>
@endsection
