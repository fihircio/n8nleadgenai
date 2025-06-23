@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Conversion Tracking</h1>
        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">
            Back to Dashboard
        </a>
    </div>

    @livewire('page.conversions.conversion-manager')
</div>
@endsection 