@extends('layouts.app')
@section('title', $title)
@section('content')
<div class="container" style="padding:40px 0;max-width:900px">
  <article style="background:#fff;border:1px solid #eee;border-radius:18px;padding:28px">
    <h1 style="margin-top:0">{{ $title }}</h1>
    <div style="line-height:1.9;color:#344054">{{ $body }}</div>
  </article>
</div>
@endsection
