<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Auto Create PlayList Youtube</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="data:image/png;base64,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" rel="icon" type="image/x-icon"/>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<noscript>
    You need to enable JavaScript to run this app.
</noscript>
<div class="page">
    <div class="flex-fill">
        <div class="header py-4">
            <div class="container">
                <div class="d-flex">
                    <a class="header-brand" href="./index.html">
                        <img src="./demo/brand/tabler.svg" class="header-brand-img" alt="tabler logo">
                    </a>
                    <div class="d-flex order-lg-2 ml-auto">
                        <div class="nav-item d-none d-md-flex">
                            <a href="https://github.com/tabler/tabler" class="btn btn-sm btn-outline-primary" target="_blank">Source code</a>
                        </div>
                        <div class="dropdown d-none d-md-flex">
                            <a class="nav-link icon" data-toggle="dropdown">
                                <i class="fe fe-bell"></i>
                                <span class="nav-unread"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a href="#" class="dropdown-item d-flex">
                                    <span class="avatar mr-3 align-self-center" style="background-image: url(demo/faces/male/41.jpg)"></span>
                                    <div>
                                        <strong>Nathan</strong> pushed new commit: Fix page load performance issue.
                                        <div class="small text-muted">10 minutes ago</div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item d-flex">
                                    <span class="avatar mr-3 align-self-center" style="background-image: url(demo/faces/female/1.jpg)"></span>
                                    <div>
                                        <strong>Alice</strong> started new task: Tabler UI design.
                                        <div class="small text-muted">1 hour ago</div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item d-flex">
                                    <span class="avatar mr-3 align-self-center" style="background-image: url(demo/faces/female/18.jpg)"></span>
                                    <div>
                                        <strong>Rose</strong> deployed new version of NodeJS REST Api V3
                                        <div class="small text-muted">2 hours ago</div>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item text-center">Mark all as read</a>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                                <span class="avatar" style="background-image: url(./demo/faces/female/25.jpg)"></span>
                                <span class="ml-2 d-none d-lg-block">
                      <span class="text-default">Jane Pearson</span>
                      <small class="text-muted d-block mt-1">Administrator</small>
                    </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" href="#">
                                    <i class="dropdown-icon fe fe-user"></i> Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="dropdown-icon fe fe-settings"></i> Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <span class="float-right"><span class="badge badge-primary">6</span></span>
                                    <i class="dropdown-icon fe fe-mail"></i> Inbox
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="dropdown-icon fe fe-send"></i> Message
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">
                                    <i class="dropdown-icon fe fe-help-circle"></i> Need help?
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="dropdown-icon fe fe-log-out"></i> Sign out
                                </a>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
                        <span class="header-toggler-icon"></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-3 ml-auto">
                        <form class="input-icon my-3 my-lg-0">
                            <input type="search" class="form-control header-search" placeholder="Search&hellip;" tabindex="1">
                            <div class="input-icon-addon">
                                <i class="fe fe-search"></i>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg order-lg-first">
                        <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                            <li class="nav-item">
                                <a href="./index.html" class="nav-link"><i class="fe fe-home"></i> Home</a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fe fe-box"></i> Interface</a>
                                <div class="dropdown-menu dropdown-menu-arrow">
                                    <a href="./cards.html" class="dropdown-item ">Cards design</a>
                                    <a href="./charts.html" class="dropdown-item ">Charts</a>
                                    <a href="./pricing-cards.html" class="dropdown-item ">Pricing cards</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fe fe-calendar"></i> Components</a>
                                <div class="dropdown-menu dropdown-menu-arrow">
                                    <a href="./maps.html" class="dropdown-item ">Maps</a>
                                    <a href="./icons.html" class="dropdown-item ">Icons</a>
                                    <a href="./store.html" class="dropdown-item ">Store</a>
                                    <a href="./blog.html" class="dropdown-item ">Blog</a>
                                    <a href="./carousel.html" class="dropdown-item ">Carousel</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="javascript:void(0)" class="nav-link active" data-toggle="dropdown"><i class="fe fe-file"></i> Pages</a>
                                <div class="dropdown-menu dropdown-menu-arrow">
                                    <a href="./profile.html" class="dropdown-item ">Profile</a>
                                    <a href="./login.html" class="dropdown-item ">Login</a>
                                    <a href="./register.html" class="dropdown-item ">Register</a>
                                    <a href="./forgot-password.html" class="dropdown-item ">Forgot password</a>
                                    <a href="./400.html" class="dropdown-item ">400 error</a>
                                    <a href="./401.html" class="dropdown-item ">401 error</a>
                                    <a href="./403.html" class="dropdown-item ">403 error</a>
                                    <a href="./404.html" class="dropdown-item ">404 error</a>
                                    <a href="./500.html" class="dropdown-item ">500 error</a>
                                    <a href="./503.html" class="dropdown-item ">503 error</a>
                                    <a href="./email.html" class="dropdown-item ">Email</a>
                                    <a href="./empty.html" class="dropdown-item active">Empty page</a>
                                    <a href="./rtl.html" class="dropdown-item ">RTL mode</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="./form-elements.html" class="nav-link"><i class="fe fe-check-square"></i> Forms</a>
                            </li>
                            <li class="nav-item">
                                <a href="./gallery.html" class="nav-link"><i class="fe fe-image"></i> Gallery</a>
                            </li>
                            <li class="nav-item">
                                <a href="./docs/index.html" class="nav-link"><i class="fe fe-file-text"></i> Documentation</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-3 my-md-5">
        </div>
    </div>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-6 col-md-3">
                            <ul class="list-unstyled mb-0">
                                <li><a href="#">First link</a></li>
                                <li><a href="#">Second link</a></li>
                            </ul>
                        </div>
                        <div class="col-6 col-md-3">
                            <ul class="list-unstyled mb-0">
                                <li><a href="#">Third link</a></li>
                                <li><a href="#">Fourth link</a></li>
                            </ul>
                        </div>
                        <div class="col-6 col-md-3">
                            <ul class="list-unstyled mb-0">
                                <li><a href="#">Fifth link</a></li>
                                <li><a href="#">Sixth link</a></li>
                            </ul>
                        </div>
                        <div class="col-6 col-md-3">
                            <ul class="list-unstyled mb-0">
                                <li><a href="#">Other link</a></li>
                                <li><a href="#">Last link</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    Premium and Open Source dashboard template with responsive and high quality UI. For Free!
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-auto ml-lg-auto">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item"><a href="./docs/index.html">Documentation</a></li>
                                <li class="list-inline-item"><a href="./faq.html">FAQ</a></li>
                            </ul>
                        </div>
                        <div class="col-auto">
                            <a href="https://github.com/tabler/tabler" class="btn btn-outline-primary btn-sm">Source code</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
                    Copyright © 2019 <a href=".">Tabler</a>. Theme by <a href="https://codecalm.net" target="_blank">codecalm.net</a> All rights reserved.
                </div>
            </div>
        </div>
    </footer>
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
{{--<script src="https://apis.google.com/js/client:platform.js?onload=start" async defer></script>--}}
{{--<script>--}}

{{--    function start() {--}}
{{--         gapi.load('auth2', function() {--}}
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
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
