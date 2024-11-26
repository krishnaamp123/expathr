<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2, h3 {
            text-align: center;
        }
        h3.left{
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>{{ $user->fullname }}</h2>
    <h3>Basic Information</h3>
    <p><strong>City:</strong> {{ $user->city->name }}</p>
    <p><strong>Address:</strong> {{ $user->address }}</p>
    <p><strong>Phone:</strong> {{ $user->phone }}</p>

    @foreach ($education as $edu)
    <p><strong>institusi:</strong> {{ $edu->university }}</p>
    <p><strong>institusi:</strong> {{ $edu->degree }}</p>
    <p><strong>institusi:</strong> {{ $edu->major }}</p>
    @endforeach

    <h2>Education</h2>

    <!-- Add other sections similarly -->
</body>
</html>
