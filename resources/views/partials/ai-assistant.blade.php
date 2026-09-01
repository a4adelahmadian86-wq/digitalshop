<div class="ai-assistant" id="aiAssistant" aria-label="دستیار هوشمند فروشگاه">
    <button class="ai-fab" id="aiFab" type="button" aria-expanded="false" aria-controls="aiPanel" title="دستیار هوشمند">
        <span class="ai-fab-icon">✦</span>
        <span class="ai-fab-label">دستیار خرید</span>
    </button>

    <section class="ai-panel" id="aiPanel" hidden>
        <header class="ai-head">
            <div>
                <strong>دستیار هوشمند فروشگاه</strong>
                <small>فقط بر اساس اطلاعات واقعی محصولات پاسخ می‌دهم.</small>
            </div>
            <button type="button" class="ai-close" id="aiClose" aria-label="بستن">×</button>
        </header>

        <div class="ai-messages" id="aiMessages">
            <div class="ai-message ai-message-bot">سلام 👋<br>دنبال چه نوع فایلی هستید؟ می‌توانم برای پیدا کردن محصول مناسب کمکتان کنم.</div>
        </div>

        <form class="ai-form" id="aiForm">
            <textarea id="aiInput" rows="2" maxlength="1000" placeholder="مثلاً برای پروژه دانشگاهی مدیریت یک فایل مناسب می‌خواهم..."></textarea>
            <div class="ai-actions">
                <button type="button" class="ai-voice" id="aiVoice" title="گفت‌وگوی صوتی">🎙</button>
                <button type="submit" class="ai-send">ارسال</button>
            </div>
        </form>
    </section>
</div>

@push('styles')
<style>
.ai-assistant{position:fixed;left:22px;bottom:22px;z-index:1200;font-family:inherit}.ai-fab{border:0;cursor:pointer;display:flex;align-items:center;gap:9px;padding:12px 16px;border-radius:999px;background:#111827;color:#fff;box-shadow:0 14px 40px rgba(15,23,42,.22);font:inherit;font-weight:800}.ai-fab-icon{font-size:20px}.ai-panel{position:absolute;left:0;bottom:58px;width:min(390px,calc(100vw - 30px));height:min(600px,calc(100vh - 100px));background:#fff;border:1px solid #e5e7eb;border-radius:22px;overflow:hidden;box-shadow:0 24px 70px rgba(15,23,42,.2);display:flex;flex-direction:column}.ai-head{padding:17px 18px;display:flex;justify-content:space-between;gap:10px;border-bottom:1px solid #eef0f4;background:linear-gradient(135deg,#f8faff,#fff)}.ai-head strong{display:block;font-size:14px}.ai-head small{display:block;color:#6b7280;margin-top:5px;font-size:10px}.ai-close{border:0;background:#f3f4f6;border-radius:10px;width:34px;height:34px;cursor:pointer;font-size:20px}.ai-messages{flex:1;overflow:auto;padding:16px;background:#fafbfe}.ai-message{max-width:88%;padding:11px 13px;border-radius:15px;margin-bottom:10px;font-size:12px;line-height:1.9;white-space:pre-wrap}.ai-message-bot{background:#fff;border:1px solid #e8ebf0}.ai-message-user{margin-right:auto;background:#111827;color:#fff}.ai-form{padding:12px;border-top:1px solid #eef0f4;background:#fff}.ai-form textarea{width:100%;resize:none;border:1px solid #dfe3ea;border-radius:13px;padding:10px 12px;outline:0;font:inherit;font-size:12px;box-sizing:border-box}.ai-form textarea:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.09)}.ai-actions{display:flex;justify-content:space-between;margin-top:8px}.ai-voice,.ai-send{border:0;border-radius:11px;cursor:pointer;height:38px;padding:0 14px;font:inherit;font-weight:800}.ai-voice{background:#f3f4f6}.ai-send{background:#111827;color:#fff}.ai-disabled{opacity:.45;cursor:not-allowed}@media(max-width:600px){.ai-assistant{left:12px;bottom:12px}.ai-fab-label{display:none}.ai-fab{width:50px;height:50px;padding:0;justify-content:center}.ai-panel{bottom:60px;width:calc(100vw - 24px);height:70vh}}
</style>
@endpush

@push('scripts')
<script>
(function(){
 const fab=document.getElementById('aiFab'),panel=document.getElementById('aiPanel'),close=document.getElementById('aiClose'),form=document.getElementById('aiForm'),input=document.getElementById('aiInput'),messages=document.getElementById('aiMessages'),voice=document.getElementById('aiVoice');
 if(!fab||!panel)return;
 const add=(text,who)=>{const el=document.createElement('div');el.className='ai-message '+(who==='user'?'ai-message-user':'ai-message-bot');el.textContent=text;messages.appendChild(el);messages.scrollTop=messages.scrollHeight;return el};
 const toggle=open=>{panel.hidden=!open;fab.setAttribute('aria-expanded',open?'true':'false')};
 fab.onclick=()=>toggle(panel.hidden);close.onclick=()=>toggle(false);
 form.onsubmit=async e=>{e.preventDefault();const text=input.value.trim();if(!text)return;input.value='';add(text,'user');const loading=add('در حال بررسی اطلاعات فروشگاه…','bot');try{const r=await fetch('{{ route('ai.chat') }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},body:JSON.stringify({message:text})});const d=await r.json();loading.remove();add(d.message||'در حال حاضر پاسخ قابل اتکایی برای این درخواست ندارم.','bot')}catch(err){loading.remove();add('ارتباط با دستیار برقرار نشد. لطفاً دوباره تلاش کنید.','bot')}};
 const SR=window.SpeechRecognition||window.webkitSpeechRecognition;if(!SR){voice.classList.add('ai-disabled');voice.title='مرورگر شما ورودی صوتی را پشتیبانی نمی‌کند';return}const rec=new SR();rec.lang='fa-IR';rec.interimResults=false;voice.onclick=()=>{try{rec.start()}catch(e){}};rec.onresult=e=>{input.value=e.results[0][0].transcript;input.focus()};
})();
</script>
@endpush
