<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Vesta Analyser</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="content">
        <div class="title m-b-md">
            Vesta Analyser
        </div>

        <div>Selecciona los archivos a analizar</div>

        <div>
            <form class="" action="{{ \Illuminate\Support\Facades\URL::to('/vesta_form') }}" enctype="multipart/form-data" method="post">
                <div class="form-group">
                    <p>Filtro 1 (F1)</p>
                    <input type="file" name="F1" value="">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                </div>
                <div class="form-group">
                    <p>Filtro 2 (F2)</p>
                    <input type="file" name="F2" value="">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                </div>
                <div class="form-group">
                    <p>Filtro 3 (F3)</p>
                    <input type="file" name="F3" value="">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                </div>
                <div class="form-group">
                    <p>Filtro 4 (F4)</p>
                    <input type="file" name="F4" value="">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                </div>
                <div class="form-group">
                    <p>Filtro 5 (F5)</p>
                    <input type="file" name="F5" value="">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                </div>
                <div class="form-group">
                    <p>Filtro 6 (F6)</p>
                    <input type="file" name="F6" value="">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                </div>
                <div class="form-group">
                    <p>Filtro 7 (F7)</p>
                    <input type="file" name="F7" value="">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                </div>
                <div class="form-group">
                    <p>Filtro 8 (F8)</p>
                    <input type="file" name="F8" value="">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                </div>
                <br>
                <button class="btn btn-primary" type="submit" name="button">Submit</button>
            </form>
        </div>
        <div>
            <a href="{{ url('/start_analysis') }}" class="btn btn-primary">Start Analysis</a>
        </div>
    </div>
</div>
</body>
</html>
