@extends('admin.base')
@section('page-title')
    {{ trans('template.menu_sub_channels') }}
@endsection
@section('page-content')
    <div id="multiple-playlist"></div>
    <script src="{{ asset('js/MultiplePlayList.react.js') }}"></script>
@endsection
