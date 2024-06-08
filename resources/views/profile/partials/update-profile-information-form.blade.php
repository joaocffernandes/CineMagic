@php
$mode = $mode ?? 'edit';
$readonly = $mode == 'show';
@endphp
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <div class="flex mt-6 justify-between">
        <!-- Main form container -->
        <div class="flex-grow space-y-6 pr-10">
            <form method="post" action="{{ route('profile.update') }}" class="w-full" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="flex mt-6 justify-between">
                    <div class="space-y-6 flex-row grow">
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        @if (Auth::user()->type == "C")
                        <div class="mt-4">
                            <x-input-label for="nif" :value="__('NIF')" />
                            <x-text-input id="nif" name="nif" type="text" class="mt-1 block w-full" :value="old('nif', $customer->nif)" required autofocus autocomplete="nif" />
                            <x-input-error class="mt-2" :messages="$errors->get('nif')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="payment_type" :value="__('Tipo de Pagamento')" />
                            <x-field.select name="payment_type" id="payment_type" value="{{ old('payment_type', $customer->payment_type) }}" :options="['VISA' => 'VISA', 'PAYPAL' => 'PAYPAL', 'MBWAY' => 'MBWAY']" />
                            <x-input-error class="mt-2" :messages="$errors->get('payment_type')" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="payment_ref" :value="__('Referência')" />
                            <x-text-input id="payment_ref" name="payment_ref" type="text" class="mt-1 block w-full" :value="old('payment_ref', $customer->payment_ref)" required autofocus autocomplete="payment_ref" />
                            <x-input-error class="mt-2" :messages="$errors->get('payment_ref')" />
                        </div>
                        @endif



                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div>
                            <p class="text-sm text-gray-800 dark:text-gray-200">
                                {{ __('Your email address is unverified.') }}
                                <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                            @endif
                        </div>
                        @endif
                        <div class="mt-10">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </div>
                    <div>
                        <div class="pb-6 mb-10 ml-20">
                            <x-field.image name="photo_filename" label="Photo" width="md" :readonly="$readonly" deleteTitle="Delete Photo" :deleteAllow="($mode == 'edit') && ($user->photo_filename)" deleteForm="form_to_delete_photo" :imageUrl="$user->PhotoFullUrl" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <form class="hidden" id="form_to_delete_photo" method="POST" action="{{ route('profile.destroy.photo') }}">
        @csrf
        @method('DELETE')
    </form>
</section>