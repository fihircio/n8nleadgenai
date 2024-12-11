@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))

{{-- @section('content')
    <p class="mt-4">
        <a href="{{ url()->previous() }}" class="underline">Go Back to Previous Page</a>
    </p>
@endsection
 --}}
