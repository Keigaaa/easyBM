<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <h1>Gestion des utilisateurs</h1>
    @if (isset($deleteMsg))
        <p>{{ $deleteMsg }}</p>
    @endif
    <table>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if (!$user->is_admin)
                        <form method="GET" action="{{ route('adminuser', ['user' => $user->id]) }}">
                            @csrf
                            <input type="submit" value="Rendre Admin">
                        </form>
                    @endif
                </td>
                <td>
                    <form method="POST" action="{{ route('deleteuser', ['user' => $user->id]) }}">
                        @csrf
                        <input type="submit" value="Supprimer">
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    <a href="{{ route('index') }}">Retour à la page Index</a>
@endsection
