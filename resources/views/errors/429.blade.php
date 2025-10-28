@extends('errors.layout')

@section('code', '429')

@if (isSystemInstalled())
    @section('title', translate('Too Many Requests'))
    @section('message', translate('You have made too many requests. Please wait and try again later.'))
@else
    @section('title', __('Too Many Requests'))
    @section('message', __('You have made too many requests. Please wait and try again later.'))
@endif
