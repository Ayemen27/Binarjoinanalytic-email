@extends('errors.layout')

@section('code', '404')

@if (env('SYSTEM_INSTALLED'))
    @section('title', translate('Not Found'))
    @section('message', translate('The page you are looking for could not be found.'))
@else
    @section('title', __('Not Found'))
    @section('message', __('The page you are looking for could not be found.'))
@endif
