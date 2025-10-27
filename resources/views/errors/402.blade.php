@extends('errors.layout')

@section('code', '402')

@if (env('SYSTEM_INSTALLED'))
    @section('title', translate('Payment Required'))
    @section('message', translate('Payment is required to access this resource.'))
@else
    @section('title', __('Payment Required'))
    @section('message', __('Payment is required to access this resource.'))
@endif
