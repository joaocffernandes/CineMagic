@php
$mode = $mode ?? 'edit';
$readonly = $mode == 'show';
$user = $user ?? new App\Models\User; // Garante que $user esteja definido
@endphp
<div class="flex mt-6 justify-between">
    <!-- Main form container -->
    <div class="flex-grow space-y-6 pr-10">
        <x-field.input name="name" label="Name" :readonly="$readonly" value="{{ old('name', $user->name) }}" />

        <div>
            <x-field.input name="email" label="Email" :readonly="$readonly" value="{{ old('email', $user->email) }}" />
        </div>

        <div class="flex items-center space-x-4">
            <x-field.input type="password" name="password" label="Password" width="1/2" :readonly="$readonly" value="{{ old('password') }}" />
            <x-field.input type="password" name="password_confirmation" label="Confirm Password" width="1/2" :readonly="$readonly" value="{{ old('password_confirmation') }}" />
        </div>
    </div>

    <div class="pb-6 mb-10 ml-20">
        <x-field.image name="photo_filename" label="Photo" width="md" :readonly="$readonly" deleteTitle="Delete Photo" :deleteAllow="($mode == 'edit') && ($user->photo_filename)" deleteForm="form_to_delete_photo" :imageUrl="$user->PhotoFullUrl" />
    </div>
</div>



<script>
    document.getElementById('togglePassword').addEventListener('change', function(e) {

        const passwordField = document.querySelector('input[name="password"]');
        const confirmPasswordField = document.querySelector('input[name="password_confirmation"]');


        if (e.target.checked) {
            passwordField.type = 'text';
            confirmPasswordField.type = 'text';
        } else {
            passwordField.type = 'password';
            confirmPasswordField.type = 'password';
        }
    });
</script>

<x-field.radio-group name="type" label="Type of user" :readonly="$readonly" value="{{ old('type', $user->type) }}" :options="[
                     'A' => 'Administrator',
                     'E' => 'Employee',
                ]" />