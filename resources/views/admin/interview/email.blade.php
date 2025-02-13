<!DOCTYPE html>
<html>
<head>
    <title>Interview Scheduled</title>
</head>
<body>
    <p>Dear {{ $applicant_name }},</p>
    <p>We are pleased to inform you that your interview for the position of <strong>{{ $job_name }}</strong> has been scheduled.</p>
    <p><strong>Details:</strong></p>
    <ul>
        <li>Date: {{ $interview_date }}</li>
        <li>Time: {{ $time }} WITA</li>
        <li>Location: {{ $location }}</li>
        <li>Link: {{ $link }}</li>
    </ul>
    <p>Thank you.</p>
    <p>Regards,</p>
    <p></p>
    <p>HR Team</p>
</body>
</html>
