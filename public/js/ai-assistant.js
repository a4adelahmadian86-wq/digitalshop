/* ==========================================================
   FILE MARKET - GLOBAL AI ASSISTANT
   Persists open/closed state and conversation context across
   normal Laravel page navigations using sessionStorage.
========================================================== */
(function(){
    'use strict';
    if(document.getElementById('global-ai-assistant')) return;

    const KEY='filemarket_ai_assistant_open';
    const messagesKey='filemarket_ai_messages';
    const esc=s=>String(s).replace(/[&<>\"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','\"':'&quot;'}[c]));

    const root=document.createElement('div');
    root.id='global-ai-assistant';
    root.innerHTML=`
      <button class="ai-assistant-launcher" type="button" aria-label="دستیار هوش مصنوعی" aria-expanded="false">
        <span>✦</span><i class="ai-dot"></i>
      </button>
      <section class="ai-assistant-panel" aria-label="دستیار هوش مصنوعی">
        <header class="ai-assistant-head">
          <div class="ai-assistant-avatar">✦</div>
          <div><strong>دستیار هوشمند فایل‌مارکت</strong><small>راهنمای خرید و استفاده از سایت</small></div>
          <button class="ai-assistant-close" type="button" aria-label="بستن">×</button>
        </header>
        <div class="ai-assistant-messages" data-ai-messages></div>
        <div class="ai-assistant-suggestions">
          <button class="ai-suggestion" data-ai-text="چطور فایل مناسبم را پیدا کنم؟">پیدا کردن فایل</button>
          <button class="ai-suggestion" data-ai-text="چطور خرید کنم؟">راهنمای خرید</button>
          <button class="ai-suggestion" data-ai-text="فایل‌های خریداری‌شده کجاست؟">فایل‌های من</button>
        </div>
        <form class="ai-assistant-form"><input name="message" autocomplete="off" placeholder="سوالت را بنویس..." aria-label="پیام"><button type="submit" aria-label="ارسال">➤</button></form>
      </section>`;
    document.body.appendChild(root);

    const panel=root.querySelector('.ai-assistant-panel');
    const launcher=root.querySelector('.ai-assistant-launcher');
    const close=root.querySelector('.ai-assistant-close');
    const list=root.querySelector('[data-ai-messages]');
    const form=root.querySelector('form');
    const input=form.querySelector('input');

    function render(){
        let saved=[];
        try{saved=JSON.parse(sessionStorage.getItem(messagesKey)||'[]')}catch(e){}
        if(!saved.length) saved=[{role:'bot',text:'سلام 👋 من دستیار هوشمند فایل‌مارکت هستم. در پیدا کردن محصول، خرید، کیف پول و بخش حساب کاربری کمکت می‌کنم.'}];
        list.innerHTML=saved.map(m=>`<div class="ai-message ${m.role==='user'?'user':'bot'}">${esc(m.text)}</div>`).join('');
        list.scrollTop=list.scrollHeight;
    }
    function save(role,text){
        let saved=[];try{saved=JSON.parse(sessionStorage.getItem(messagesKey)||'[]')}catch(e){}
        saved.push({role,text}); saved=saved.slice(-12); sessionStorage.setItem(messagesKey,JSON.stringify(saved)); render();
    }
    function setOpen(open){
        panel.classList.toggle('open',open); launcher.setAttribute('aria-expanded',open?'true':'false');
        sessionStorage.setItem(KEY,open?'1':'0');
        if(open) setTimeout(()=>input.focus(),180);
    }
    launcher.addEventListener('click',()=>setOpen(!panel.classList.contains('open')));
    close.addEventListener('click',()=>setOpen(false));
    root.querySelectorAll('.ai-suggestion').forEach(b=>b.addEventListener('click',()=>{input.value=b.dataset.aiText;form.requestSubmit()}));
    form.addEventListener('submit',e=>{
        e.preventDefault(); const text=input.value.trim(); if(!text)return;
        save('user',text); input.value='';
        setTimeout(()=>save('bot','برای پاسخ دقیق‌تر، از منوی مربوط به همین بخش استفاده کن. دستیار سایت در حال اتصال به سرویس هوش مصنوعی پروژه است.'),250);
    });
    render();
    if(sessionStorage.getItem(KEY)==='1') setOpen(true);
})();
