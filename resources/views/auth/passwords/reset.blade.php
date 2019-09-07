@extends('layouts.auth')

@section('content')

<v-container fluid fill-height>
    <v-layout align-center justify-center>
        <v-flex xs12 sm10 md8 lg5>
            <v-card class="elevation-12">
                <v-card-title>
                    <v-img
                            max-width="30%"
                            src="https://mx13.io/assets/logo.webp"
                            class="mx-auto"></v-img>
                </v-card-title>
                <v-toolbar dark color="primary">
                    <v-toolbar-title>{{ __('Reset Password') }}</v-toolbar-title>
                </v-toolbar>
                <v-form action="{{ route('password.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <v-card-text>
                        <v-text-field
                                prepend-icon="mdi-email"
                                name="email"
                                label="{{ __('E-Mail Address') }}"
                                type="email"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                required
                        ></v-text-field>
                        @error('email')
                        <span class="red--text" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <v-text-field
                                id="password"
                                prepend-icon="mdi-lock"
                                name="password"
                                label="{{ __('Password') }}"
                                type="password"
                                autocomplete="new-password"
                                required
                        ></v-text-field>
                        @error('password')
                        <span class="red--text" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <v-text-field
                                id="confirm-password"
                                prepend-icon="mdi-lock"
                                name="password_confirmation"
                                label="{{ __('Confirm Password') }}"
                                type="password"
                                autocomplete="new-password"
                                required
                        ></v-text-field>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" type="submit">{{ __('Reset Password') }}</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-flex>
    </v-layout>
</v-container>
@endsection
