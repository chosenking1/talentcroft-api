<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Payment Callback</title>
    @include('partials.icon-links')
    <style>
        body {
            padding: 0;
            margin: 0;
            overflow: hidden;
            display: flex;
            height: 100vh;
            background-color: #f1f1f1;
            width: 100vw;

        }

        .centered {
            height: 100vh;
            width: 100vw;
            justify-content: center;
            align-items: center;
            display: flex;
            flex-direction: column;
        }
    </style>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</head>
<body>
<div class="centered">
    @if ($status == 'success')
        <lottie-player
                src="https://assets8.lottiefiles.com/packages/lf20_aehuzqdw.json"
                background="transparent"
                speed="1" style="width: 450px; height: 450px;" loop autoplay></lottie-player>
        <h1>Your payment was successful</h1>
    @elseif($status == 'failed')
        <lottie-player
                src="https://assets1.lottiefiles.com/packages/lf20_ysrn2iwp.json"
                background="transparent"
                speed="1" style="width: 450px; height: 450px;" loop autoplay></lottie-player>
        <h1>Your transaction failed try again later</h1>
    @else
        <lottie-player
                src="https://assets1.lottiefiles.com/packages/lf20_ail5bspx.json"
                background="transparent"
                speed="1" style="width: 450px; height: 450px;" loop autoplay></lottie-player>
        <h1>{{$message}}</h1>
    @endif
    <p >Back</p>
</div>
</body>
</html>
