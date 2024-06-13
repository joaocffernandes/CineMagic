@extends('layouts.main')

@section('header-title', 'List of Customers')

@section('main')
<div>
    <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
        <div class="font-base text-sm text-gray-700 dark:text-gray-300">
            <x-users.filter-card id='namefilter' :filterAction="route('customers')" :resetUrl="route('customers')" :name="old('name', $filterByName)" class="mb-6" />
            <x-users.table :users="$customers" :showView="false" :showEdit="false" :showDelete="true" :showBlock="true" />
        </div>
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection