<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auction House FIT</title>
</head>

<body>
<div>
    <p style="font-family: 'Comic Sans MS',serif; color: #8f5782">
        {{$mail_text}}
    </p>
    <a href="{{ env('FRONT_URL') }}">
        <p style="font-family: 'Comic Sans MS',serif; text-decoration: none; color: #C0A28D">
            Click here to visit our platform.
        </p>
    </a>
</div>
</body>

</html>
