@extends('layouts.main')

@section('header-title', 'Staff List')

@section('main')
<div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
    <div class="flex items-center gap-4 mb-4">
        <x-button href="{{ route('users.create') }}" text="Create a new user" type="success" />
    </div>
    <div class="flex justify-center">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50 flex grow">
            <div class="font-base text-sm text-gray-700 dark:text-gray-300 flex grow w-full">
                <x-users.table class="w-full" :users="$allUsers" :showView="true" :showEdit="true" :showDelete="true" :showBlock="false" />
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $allUsers->links() }}
    </div>
</div>
@endsection