@extends('layouts.default')

@section('content')
     {{-- <p>{{ Auth::user()->name }}</p>
    <p>{{ Auth::user()->type }}</p>
    <p>{{ Auth::user()->select('name', 'type')->get() }}</p>  --}}
    {{--  @if (Auth::guest())  --}}
    {{--  <article :user-name="Guest" user-type="guest"></article>  --}}
    {{--  @else
    <article :user-name="{{Auth::user()->name}}" user-type="{{Auth::user()->type}}"></article>
    @endif  --}}

    <article_page :article_id="{{Request::segment(2)}}"></article_page>
    
@endsection
