@extends('base::email.layout')

@section('content')
    <p>Hi {{ $employee->user->name }},</p>
    <p>This is a confirmation that you have successfully enrolled in the following course:</p>
    <h3>{{ $course->title }}</h3>
    <p>We look forward to seeing you there!</p>
@endsection