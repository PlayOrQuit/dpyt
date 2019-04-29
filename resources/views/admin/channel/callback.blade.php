<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base id="baseUrl" href="{{ url('/') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body>
<script src="{{ asset('js/app.js') }}"></script>
<script>
    const BASE_URL = document.getElementById("baseUrl").href;
    const URL_CHANNEL_CALLBACK = BASE_URL + 'admin/channel/callback';
    function submit(client_id, redirect_uri) {
        const oauth2Endpoint = 'https://accounts.google.com/o/oauth2/v2/auth';
        let form = document.createElement('form');
        form.setAttribute('method', 'GET');
        form.setAttribute('action', oauth2Endpoint);
        const params = {
            'client_id': client_id,
            'response_type': 'code',
            'redirect_uri': redirect_uri,
            'scope': 'https://www.googleapis.com/auth/youtube',
            'access_type': 'offline'
        };
        for (let p in params) {
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', p);
            input.setAttribute('value', params[p]);
            form.appendChild(input);
        }
        document.body.appendChild(form);
        form.submit();
    }
    function fetch(uri, method, data, callback) {
        const token = document.head.querySelector('meta[name="csrf-token"]');
        $.ajax({
            url: uri,
            method: method,
            dataType: 'json',
            data: data,
            xhrFields: { withCredentials: true },
            headers: {
                'X-CSRF-TOKEN' : token.content,
                'Content-Type': 'application/json',
            },
            success: function (data) {
                callback(data);
            }
        });
    }
    function authGoogle(uri, method, data, callback) {
        $.ajax({
            url: uri,
            method: method,
            dataType: 'json',
            data: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            success: function (data) {
                callback(data);
            },
            error: function (error) {
                console.log(error);
            }
        })
    }
    function getAPIKeyPrimary(callback) {
        fetch(BASE_URL + 'admin/api-key/getKeyByPrimary', 'get', {}, function (data) {
            callback(data);
        });
    }
    function run(){
        const uri = window.location.href;
        const url = new URL(uri);
        const code = url.searchParams.get("code");
        const scope = url.searchParams.get("scope");
        const client_id = url.searchParams.get("client_id");
        if(code == null && scope == null && client_id == null){
            window.location.href = "{{ action('ChannelController@render') }}"
        }else if(code == null && scope == null){
            submit(client_id, URL_CHANNEL_CALLBACK);
        }else{
            getAPIKeyPrimary(function (res) {
                if(res.body.data[0]){
                    authGoogle('https://www.googleapis.com/oauth2/v4/token', 'post', {
                        grant_type: 'authorization_code',
                        code: code,
                        client_id: res.body.data[0].id_client,
                        client_secret: res.body.data[0].client_secret,
                        redirect_uri: URL_CHANNEL_CALLBACK
                    }, function (auth) {
                        window.opener.postMessage(JSON.stringify(auth), URL_CHANNEL_CALLBACK);
                        window.close();
                    });
                }
            });
        }
    }
    $(document).ready(function () {
        run();
    });
</script>
</body>
</html>
