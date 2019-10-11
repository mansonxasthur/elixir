@extends('layouts.auth')

@section('content')
    <v-container fluid fill-height>
               <v-layout row wrap align-center justify-center>
            <v-flex xs12 sm10 md8 lg5>
                <v-card>
                    <v-card-title>
                        <v-img
                                max-width="30%"
                                src="https://mx13.io/assets/logo.webp"
                                class="mx-auto"></v-img>
                    </v-card-title>
                    <v-toolbar dark color="primary">
                        <v-toolbar-title>{{ __('Login') }}</v-toolbar-title>
                    </v-toolbar>
                    <v-form action="{{ route('login') }}" method="post">
                        @csrf
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
                                    autocomplete="current-password"
                                    required
                            ></v-text-field>
                            @error('password')
                            <span class="red--text" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </v-card-text>
                        <v-card-actions>
                            <v-checkbox
                                    label="{{ __('Remember Me') }}"
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                                    class="ml-2"
                                    value="{{ old('remember') ? 'checked' : '' }}"
                            ></v-checkbox>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" type="submit">{{ __('Login') }}</v-btn>
                            <v-btn link color="secondary" href="{{ route('register') }}">
                                {{ __('Register') }}
                            </v-btn>
                        </v-card-actions>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn text href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
@endsection
