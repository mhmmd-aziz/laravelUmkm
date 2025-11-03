<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Model Gemini</title>
</head>
<body>
    <h1>Daftar Model Gemini</h1>
    <ul>
        @foreach ($models as $model)
            <li>{{ $model }}</li>
        @endforeach
    </ul>
</body>
</html>
