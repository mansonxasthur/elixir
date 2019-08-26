@extends('layouts.auth')

@section('content')
    <v-container fluid fill-height>
        <v-layout row wrap align-center justify-center>
            <v-flex xs12 sm10 md8 lg5>
                <v-card>
                    <v-card-title>
                        <v-img
                                max-width="30%"
                                src="https://digitalsigma.io/assets/img/logo_md.webp"
                                class="mx-auto"></v-img>
                    </v-card-title>
                    <v-toolbar dark color="primary">
                        <v-toolbar-title>{{ __('Register') }}</v-toolbar-title>
                    </v-toolbar>
                    <v-form action="{{ route('register') }}" method="post">
                        @csrf
                        <v-card-text>
                            <!-- Name -->
                            <v-text-field
                                    prepend-icon="person"
                                    name="name"
                                    label="{{ __('Name') }}"
                                    type="text"
                                    value="{{ old('name') }}"
                                    autocomplete="name"
                                    required
                            ></v-text-field>
                            @error('name')
                            <span class="red--text" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                            <!-- E-Mail -->
                            <v-text-field
                                    prepend-icon="email"
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

                            <!-- Password -->
                            <v-text-field
                                    id="password"
                                    prepend-icon="lock"
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

                            <!-- Password Confirmation -->
                            <v-text-field
                                    id="passwordConfiramtion"
                                    prepend-icon="lock"
                                    name="password_confirmation"
                                    label="{{ __('Password Confirmation') }}"
                                    type="password"
                                    required
                            ></v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" type="submit">{{ __('Register') }}</v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
@endsection
