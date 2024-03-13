@extends('layouts.auth')

@section('title', 'Восстановить пароль')

@section('content')

    <x-forms.auth-forms title="Восстановить пароль" action="{{ route('forgot-password.handle') }}" method="POST">
        @csrf
        <x-forms.text-input
            name="email"
            type="email"
            placeholder="E-mail"
            required
            :isError="$errors->has('email')"
        />

        @error('email')
            <x-forms.error>
                {{ $message }}
            </x-forms.error>
        @enderror

        <x-forms.primary-button>
            Отправить
        </x-forms.primary-button>

        <x-slot:socialAuth></x-slot:socialAuth>
        <x-slot:links>
            <div class="space-y-3 mt-5">
                <div class="text-xxs md:text-xs"><a href="{{ route('login.page') }}" class="text-white hover:text-white/70 font-bold">Вспомнил пароль</a></div>
            </div>
        </x-slot:links>

    </x-forms.auth-forms>

@endsection
