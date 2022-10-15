@extends('layouts.app')

@section('content')
    <h1>Créer un utilisateur</h1>
    <form method="POST" action="{{ route('postcreateuser') }}">
        @csrf

        <div>
            <label for="name">Nom</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus />
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required />
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" required />
        </div>

        <div>
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" required />
            <button type="submit">Enregistrer</button>
        </div>
    </form>
    <a href="{{ route('index') }}">Retour à la page Index</a>
@endsection
