/* ==========================================================
   FILE MARKET - GLOBAL AI ASSISTANT
   Persistent UI + conversation context + real Laravel AI endpoint.
========================================================== */
(function(){
    'use strict';

    if(document.getElementById('global-ai-assistant')) return;

    const OPEN_KEY='filemarket_ai_assistant_open';
    const MESSAGES_KEY='filemarket_ai_messages';
    const MAX_MESSAGES=12;
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
          <div><strong>دستیار هوشمند فایل‌مارکت</strong><small>همراه شما در تمام بخش‌های سایت</small></div>
          <button class="ai-assistant-close" type="button" aria-label="بستن">×</button>
        </header>
        <div class="ai-assistant-messages" data-ai-messages aria-live="polite"></div>
        <div class="ai-assistant-suggestions">
          <button class="ai-suggestion" data-ai-text="چطور فایل مناسبم را پیدا کنم؟">پیدا کردن فایل</button>
          <button class="ai-suggestion" data-ai-text="چطور خرید کنم؟">راهنمای خرید</button>
          <button class="ai-suggestion" data-ai-text="فایل‌های خریداری‌شده کجاست؟">فایل‌های من</button>
        </div>
        <form class="ai-assistant-form">
          <input name="message" autocomplete="off" maxlength="2000" placeholder="سوالت را بنویس..." aria-label="پیام">
          <button type="submit" aria-label="ارسال">➤</button>
        </form>
      </section>`;

    document.body.appendChild(root);

    const panel=root.querySelector('.ai-assistant-panel');
    const launcher=root.querySelector('.ai-assistant-launcher');
    const close=root.querySelector('.ai-assistant-close');
    const list=root.querySelector('[data-ai-messages]');
    const form=root.querySelector('form');
    const input=form.querySelector('input');

    function readMessages(){
        try{
            const value=JSON.parse(sessionStorage.getItem(MESSAGES_KEY)||'[]');
            return Array.isArray(value) ? value.slice(-MAX_MESSAGES) : [];
        }catch(e){return [];}
    }

    function render(){
        let saved=readMessages();
        if(!saved.length){
            saved=[{role:'bot',text:'سلام 👋 من دستیار هوشمند فایل‌مارکت هستم. برای پیدا کردن فایل، خرید، کیف پول، حساب کاربری و استفاده از سایت کنارت هستم.'}];
        }
        list.innerHTML=saved.map(m=>`<div class="ai-message ${m.role==='user'?'user':'bot'}">${esc(m.text)}</div>`).join('');
        list.scrollTop=list.scrollHeight;
    }

    function save(role,text){
        const saved=readMessages();
        saved.push({role,text:String(text).slice(0,2000)});
        sessionStorage.setItem(MESSAGES_KEY,JSON.stringify(saved.slice(-MAX_MESSAGES)));
        render();
    }

    function setTyping(active){
        const old=list.querySelector('.ai-typing');
        if(old) old.remove();
        if(active){
            const el=document.createElement('div');
            el.className='ai-message bot ai-typing';
            el.textContent='در حال بررسی سوال شما…';
            list.appendChild(el);
            list.scrollTop=list.scrollHeight;
        }
    }

    function setOpen(open){
        panel.classList.toggle('open',open);
        launcher.setAttribute('aria-expanded',open?'true':'false');
        sessionStorage.setItem(OPEN_KEY,open?'1':'0');
        if(open) setTimeout(()=>input.focus(),180);
    }

    async function askAI(text){
        const history=readMessages()
            .filter(m=>m.role==='user'||m.role==='bot')
            .slice(-10)
            .map(m=>({role:m.role==='bot'?'assistant':'user',content:m.text}));

        const csrf=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response=await fetch('/ai/assistant/chat',{
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'Accept':'application/json',
                ...(csrf?{'X-CSRF-TOKEN':csrf}:{})
            },
            body:JSON.stringify({
                message:text,
                messages:history,
                page:window.location.pathname
            })
        });

        const data=await response.json().catch(()=>({}));
        if(!response.ok || !data.message){
            throw new Error(data.message||'AI request failed');
        }
        return data.message;
    }

    launcher.addEventListener('click',()=>setOpen(!panel.classList.contains('open')));
    close.addEventListener('click',()=>setOpen(false));

    root.querySelectorAll('.ai-suggestion').forEach(button=>{
        button.addEventListener('click',()=>{
            input.value=button.dataset.aiText||'';
            form.requestSubmit();
        });
    });

    form.addEventListener('submit',async e=>{
        e.preventDefault();
        const text=input.value.trim();
        if(!text || form.dataset.busy==='1') return;

        form.dataset.busy='1';
        input.disabled=true;
        save('user',text);
        input.value='';
        setTyping(true);

        try{
            const answer=await askAI(text);
            setTyping(false);
            save('bot',answer);
        }catch(error){
            setTyping(false);
            save('bot','فعلاً نتوانستم پاسخ هوشمند را دریافت کنم. اگر اتصال AI سایت فعال باشد، چند لحظه دیگر دوباره امتحان کنید.');
        }finally{
            form.dataset.busy='0';
            input.disabled=false;
            input.focus();
        }
    });

    render();
    if(sessionStorage.getItem(OPEN_KEY)==='1') setOpen(true);
})();
