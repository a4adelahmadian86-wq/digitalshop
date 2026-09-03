@extends('admin.layout')
@section('title','بازخورد دستیار هوشمند')
@section('content')
<div class="admin-page ai-feedback-page">
<div class="admin-page-head"><div><div class="admin-eyebrow">AI COMMERCE · FEEDBACK LOOP</div><h1>بازخورد دستیار</h1><p>بازخوردها داده آموزشی هستند؛ قبل از استفاده برای بهبود مدل باید بررسی و تأیید شوند.</p></div></div>
<div class="stats"><div><small>کل بازخورد</small><b>{{ number_format($stats['total']) }}</b></div><div><small>مثبت</small><b>{{ number_format($stats['positive']) }}</b></div><div><small>منفی</small><b>{{ number_format($stats['negative']) }}</b></div><div><small>بررسی‌نشده</small><b>{{ number_format($stats['unresolved']) }}</b></div></div>
<section class="card"><div class="card-head"><h2>آخرین بازخوردها</h2><span>Human review before learning</span></div>
@forelse($feedback as $item)
<article class="feedback"><div class="top"><div><strong>{{ $item->product?->title ?: 'گفت‌وگوی عمومی' }}</strong><small>{{ $item->type }} · {{ $item->created_at?->format('Y/m/d H:i') }}</small></div><b>{{ $item->rating ? $item->rating.'/5' : '—' }}</b></div>@if($item->comment)<p>{{ $item->comment }}</p>@endif</article>
@empty<p class="empty">هنوز بازخوردی ثبت نشده است.</p>@endforelse
{{ $feedback->links() }}
</section></div>
@endsection
@push('styles')<style>.ai-feedback-page{max-width:1100px}.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px}.stats>div,.card{background:#fff;border:1px solid #eaecf0;border-radius:15px}.stats>div{padding:17px}.stats small,.stats b{display:block}.stats small{font-size:10px;color:#667085}.stats b{font-size:24px;margin-top:7px}.card{padding:20px}.card-head{display:flex;justify-content:space-between;align-items:center}.card-head h2{margin:0;font-size:16px}.card-head span{font-size:10px;color:#667085}.feedback{border-top:1px solid #edf0f4;padding:14px 0}.top{display:flex;justify-content:space-between;gap:12px}.top strong,.top small{display:block}.top strong{font-size:12px}.top small{font-size:10px;color:#98a2b3;margin-top:4px}.top>b{font-size:12px}.feedback p{font-size:11px;line-height:1.9;margin:8px 0 0;color:#344054}.empty{font-size:11px;color:#98a2b3}@media(max-width:700px){.stats{grid-template-columns:1fr 1fr}}</style>@endpush
