<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
@extends('layouts.app')

@section('content')
    <h1>Gestion des utilisateurs</h1>
    <table>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
            </tr>
        @endforeach
    </table>
@endsection
