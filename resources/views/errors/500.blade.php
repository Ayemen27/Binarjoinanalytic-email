@extends('errors.layout')

@section('code', '500')

@if (isSystemInstalled())
    @section('title', translate('Server Error'))
    @section('message', translate('Whoops, something went wrong on our servers.'))
@else
    @section('title', __('Server Error'))
    @section('message', __('Whoops, something went wrong on our servers.'))
@endif
