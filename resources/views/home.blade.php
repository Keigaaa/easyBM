@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <div>
        <p>Bonjour {{ auth()->user()->name }}, vous êtes bien connecté !</p>
        @if (auth()->user()->is_admin)
            <p><a href="{{ route('index') }}">Acceder à la page d'administration</a></p>
        @endif
        {{-- <p>Bonjour {{ Auth::user()->name }}, vous êtes bien connecté !</p> --}}
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit">'Se déconnecter'</button>
    </form>

    <hr>
@endsection
