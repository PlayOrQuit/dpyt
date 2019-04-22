@extends('admin.base')
@section('page-title')
    {{ trans('template.menu_api_key') }}
@endsection
@section('page-content')
    <div id="section-api-key"></div>
    <script src="{{ asset('js/PageApiKey.js') }}"></script>
@endsection
