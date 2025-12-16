@extends('layouts.app')

@section('content')
    <!-- Paw Decorations -->
    <div class="paw-shape paw-1"></div>
    <div class="paw-shape paw-2"></div>
    <div class="paw-shape paw-3"></div>

    <!-- Floating Bubbles -->
    <div class="bubble" style="width: 40px; height: 40px; bottom: 10%; left: 5%; animation-delay: 0s;"></div>
    <div class="bubble" style="width: 60px; height: 60px; bottom: 15%; right: 10%; animation-delay: 1s;"></div>
    <div class="bubble" style="width: 30px; height: 30px; top: 30%; left: 15%; animation-delay: 2s;"></div>

    @include('pages.sections.hero')
    @include('pages.sections.features')
    @include('pages.sections.steps')
    @include('pages.sections.cta')
@endsection
