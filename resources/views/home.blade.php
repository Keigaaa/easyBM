@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <div>
        <p>Bonjour {{ auth()->user()->name }}, vous êtes bien connecté !</p>
        @if (auth()->user()->is_admin)
            Bonjour, administrateur !
        @endif
        {{-- <p>Bonjour {{ Auth::user()->name }}, vous êtes bien connecté !</p> --}}
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit">'Se déconnecter'</button>
    </form>

    <hr>
@endsection
