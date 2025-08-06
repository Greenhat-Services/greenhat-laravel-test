@extends('base::email.layout')

@section('content')
    <p>Hi {{ $employee->user->name }},</p>
    <p>Welcome aboard! Your employee account has been successfully created.</p>
    <p>You can now log in using the following credentials:</p>
    <ul>
        <li><strong>Email:</strong> {{ $employee->user->email }}</li>
        <li><strong>Temporary Password:</strong> {{ $password }}</li>
    </ul>
    <p>We strongly recommend you change your password upon your first login.</p>
@endsection