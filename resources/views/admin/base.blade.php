<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Auto Create PlayList Youtube @yield('page-title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base id="baseUrl" href="{{ url('/') }}">
    <link href="{{ asset('images/youtube.png') }}" rel="icon" type="image/x-icon"/>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
<noscript>
    You need to enable JavaScript to run this app.
</noscript>
<div class="page">
    <div class="flex-fill">
        @include('admin.header')
        @include('admin.nav')
        <div class="my-3 my-md-5">
            @yield('page-content')
        </div>
    </div>
    @include('admin.footer')
</div>
{{--<script type="text/javascript">--}}
{{--    let token = document.head.querySelector('meta[name="csrf-token"]');--}}
{{--    console.log(token.content);--}}
{{--    $.ajax(--}}
{{--        {--}}
{{--            cache: false,--}}
{{--            url: '{{ action('CreateApiKeyController@create') }}',--}}
{{--            dataType: 'json',--}}
{{--            contentType: 'application/json; charset=utf-8',--}}
{{--            type: 'POST',--}}
{{--            headers: {--}}
{{--                'X-CSRF-TOKEN': token.content,--}}
{{--            },--}}
{{--            data: {--}}
{{--                'api_key': 'demo123'--}}
{{--            },--}}
{{--            xhrFields: {--}}
{{--                withCredentials: true--}}
{{--            },--}}
{{--            success: function (res) {--}}
{{--                console.log(res);--}}
{{--            },--}}
{{--            error: function (err) {--}}
{{--                console.log(err);--}}
{{--            }--}}
{{--        })--}}
{{--</script>--}}

<script src="{{ asset('js/app.js') }}"></script>
{{--<script src="https://apis.google.com/js/client:platform.js?onload=start" async defer></script>--}}
{{--<script>--}}

{{--    function start() {--}}
{{--        gapi.load('auth2', function() {--}}
{{--            auth2 = gapi.auth2.init({--}}
{{--                client_id: '263498759299-to3jnbgjkcee9hfhain7t9av53l4vg0n.apps.googleusercontent.com',--}}
{{--                scope: 'https://www.googleapis.com/auth/youtube'--}}
{{--            });--}}
{{--        });--}}
{{--    }--}}
{{--    $('#signinButton').click(function() {--}}
{{--        // signInCallback defined in step 6.--}}
{{--        auth2.grantOfflineAccess().then(signInCallback);--}}
{{--    });--}}
{{--    function signInCallback(authResult) {--}}
{{--        console.log(authResult);--}}
{{--        console.log(auth2);--}}
{{--    }--}}
{{--</script>--}}
</body>
</html>
