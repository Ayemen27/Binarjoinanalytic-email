@extends('errors.layout')

@section('code', '503')

@if (env('SYSTEM_INSTALLED'))
    @section('title', translate('Service Unavailable'))
    @section('message', translate('The service is currently unavailable. Please try again later.'))
@else
    @section('title', __('Service Unavailable'))
    @section('message', __('The service is currently unavailable. Please try again later.'))
@endif
