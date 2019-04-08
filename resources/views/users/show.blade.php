@extends('layouts.default')
@section('title', $user->name)
<style type="text/css">

</style>
@section('content')
    <div class="row">
        <div class="offset-md-2 col-md-8">
            <section class="user_info">
                @include('shared._user_info',['user' => $user])
            </section>
            <section class="stats mt-2">
                @include('shared._stats',['user'=>$user])
            </section>
            <section class="status">
                @if ($statuses->count() > 0)
                    <ul class="list-unstyled">
                        @foreach($statuses as $status)
                            @include('statuses._status')
                        @endforeach
                    </ul>
                    <div class="mt-5">
                        {!! $statuses->render() !!}
                    </div>
                @else
                    <p class="text-center">发条微博，丰富生活！</p>
                @endif
            </section>
        </div>
    </div>
@stop