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

    @if($password)
    <div style="background-color: #f8f9fa; border-left: 4px solid #007bff; padding: 15px; margin: 20px 0;">
        <h3 style="margin-top: 0; color: #007bff;">Your Login Credentials</h3>
        <p style="margin-bottom: 10px;">Your account has been created with the following credentials:</p>
        <ul style="list-style: none; padding-left: 0;">
            <li style="margin: 8px 0;"><strong>Email:</strong> {{ $employeeEmail }}</li>
            <li style="margin: 8px 0;">
                <strong>Password:</strong>
                <span style="background-color: #fff; padding: 8px 12px; border: 1px solid #dee2e6; border-radius: 4px; font-family: monospace; font-size: 14px; display: inline-block;">{{ $password }}</span>
            </li>
        </ul>
        <p style="color: #dc3545; font-weight: bold; margin-top: 15px;">
            ⚠️ Important: Please save this password in a secure location. For security reasons, we recommend changing your password after your first login.
        </p>
        <p style="margin-top: 15px;">
            <a href="{{ config('app.url') }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;">
                Login to Employee Portal
            </a>
        </p>
    </div>
    @endif

    <h3>Next Steps:</h3>

    <p>You can now log in to the Employee Portal to:</p>
    <ul>
        <li>Browse available courses</li>
        <li>Enroll in training programs</li>
        <li>Track your learning progress</li>
    </ul>

    <p>If you have any questions, please don't hesitate to reach out to our HR department.</p>

    <p>We look forward to having you as part of the Greenhat team!</p>

    <p>Best regards,<br>
    The Greenhat Team</p>

    <p style="font-size: 12px; color: #666; margin-top: 20px;">
        <em>This is an automated email. Please do not reply to this message.</em>
    </p>
@endsection
