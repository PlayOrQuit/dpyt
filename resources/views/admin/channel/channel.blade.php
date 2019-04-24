@extends('admin.base')
@section('page-title')
    {{ trans('template.menu_sub_channels') }}
@endsection
@section('page-content')
    <div id="section-channel"></div>
    <script src="{{ asset('PageChannel.react.jsact.jsct.js') }}"></script>
@endsection
