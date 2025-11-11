<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Enrollment Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px;">
        <h1 style="color: #2c3e50; margin-top: 0;">Course Enrollment Confirmation</h1>

        <p>Dear {{ $employee->user->first_name }},</p>

        <p>Congratulations! You have successfully enrolled in the following course:</p>

        <div style="background-color: #fff; padding: 15px; border-left: 4px solid #4CAF50; margin: 20px 0;">
            <h2 style="color: #2c3e50; margin-top: 0;">{{ $course->title }}</h2>

            @if($course->instructor)
                <p><strong>Instructor:</strong> {{ $course->instructor }}</p>
            @endif

            @if($course->duration_hours)
                <p><strong>Duration:</strong> {{ $course->duration_hours }} hours</p>
            @endif

            @if($course->difficulty_level)
                <p><strong>Level:</strong> {{ ucfirst($course->difficulty_level) }}</p>
            @endif

            @if($course->start_date)
                <p><strong>Start Date:</strong> {{ $course->start_date->format('F j, Y') }}</p>
            @endif

            @if($course->end_date)
                <p><strong>End Date:</strong> {{ $course->end_date->format('F j, Y') }}</p>
            @endif
        </div>

        <div style="background-color: #e8f5e9; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="color: #2c3e50; margin-top: 0;">Next Steps</h3>
            <ul>
                <li>Check your learning dashboard for course materials</li>
                <li>Mark your calendar with important dates</li>
                <li>Prepare any prerequisites if mentioned in the course description</li>
                <li>Reach out to your instructor if you have any questions</li>
            </ul>
        </div>

        @if($enrollment->notes)
            <div style="margin: 20px 0;">
                <p><strong>Your notes:</strong></p>
                <p style="font-style: italic; color: #666;">{{ $enrollment->notes }}</p>
            </div>
        @endif

        <p>We're excited to support your professional development journey!</p>

        <p style="color: #666; font-size: 14px; margin-top: 30px;">
            <strong>Enrolled on:</strong> {{ $enrollment->enrolled_at->format('F j, Y \a\t g:i A') }}
        </p>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

        <p style="color: #999; font-size: 12px; text-align: center;">
            This is an automated message. Please do not reply directly to this email.
        </p>
    </div>
</body>
</html>
