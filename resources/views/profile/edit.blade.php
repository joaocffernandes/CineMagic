@extends('layouts.main')
@section('header-title', 'Edit Profile')
@section('main')

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div>
                @include('profile.partials.update-profile-information-form', ['mode' => 'edit'])
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mt-20">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form', ['mode' => 'edit'])
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form', ['mode' => 'edit'])
            </div>
        </div>
    </div>
</div>
@endsection