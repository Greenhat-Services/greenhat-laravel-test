@extends('base::email.layout')

@section('content')
    <p>Dear {{ $employeeName }},</p>

    <p>Welcome to Greenhat! We are excited to confirm that your registration has been successfully completed.</p>

    <h3>Registration Details:</h3>

    <ul>
        <li><strong>Name:</strong> {{ $employeeName }}</li>
        <li><strong>Email:</strong> {{ $employeeEmail }}</li>
        <li><strong>Role:</strong> {{ $employeeRole }}</li>
        <li><strong>Registration Date:</strong> {{ $registrationDate }}</li>
    </ul>

    <h3>Next Steps:</h3>

    <p>You will receive further instructions from our team regarding your onboarding process. If you have any questions in the meantime, please don't hesitate to reach out to our HR department.</p>

    <p>We look forward to having you as part of the Greenhat team!</p>

    <p>Best regards,<br>
    The Greenhat Team</p>

    <p style="font-size: 12px; color: #666; margin-top: 20px;">
        <em>This is an automated email. Please do not reply to this message.</em>
    </p>
@endsection
