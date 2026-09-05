document.addEventListener('DOMContentLoaded',function(){
 const root=document.getElementById('homeHero'); if(!root)return;
 const slides=[...root.querySelectorAll('.hero-slide')],dots=[...root.querySelectorAll('[data-hero-dot]')],progress=root.querySelector('[data-hero-progress]'),progressWrap=root.querySelector('.hero-progress'),count=root.querySelector('[data-hero-count]');
 if(slides.length<2)return;
 let active=0,timer=null,startedAt=0,remaining=6000,paused=false,animating=false;
 const reduce=window.matchMedia&&window.matchMedia('(prefers-reduced-motion: reduce)').matches;
 function render(index){
   active=(index+slides.length)%slides.length; animating=true;
   slides.forEach((s,i)=>{const on=i===active;s.classList.toggle('is-active',on);s.setAttribute('aria-hidden',on?'false':'true');});
   dots.forEach((d,i)=>d.setAttribute('aria-selected',i===active?'true':'false'));
   if(count)count.textContent=String(active+1).padStart(2,'0')+' / '+String(slides.length).padStart(2,'0');
   if(progress){progress.style.animation='none';progress.offsetHeight;progress.style.width='0';}
   if(progressWrap)progressWrap.classList.remove('is-running');
   setTimeout(()=>{animating=false;},reduce?50:650);
   resetTimer();
 }
 function startTimer(ms=6000){clearTimeout(timer);if(reduce||paused||document.hidden||document.activeElement&&root.contains(document.activeElement))return;remaining=ms;startedAt=performance.now();if(progressWrap)progressWrap.classList.add('is-running');timer=setTimeout(()=>render(active+1),ms);}
 function resetTimer(){clearTimeout(timer);if(progressWrap)progressWrap.classList.remove('is-running');if(!reduce&&!paused&&!document.hidden&&!root.matches(':focus-within'))startTimer(6000);}
 function pause(){if(paused)return;paused=true;clearTimeout(timer);if(progressWrap)progressWrap.classList.remove('is-running');if(startedAt)remaining=Math.max(400,6000-(performance.now()-startedAt));}
 function resume(){if(!paused)return;paused=false;if(document.hidden||root.matches(':focus-within'))return;startTimer(remaining||6000);}
 root.querySelector('[data-hero-next]')?.addEventListener('click',()=>{render(active+1);});
 root.querySelector('[data-hero-prev]')?.addEventListener('click',()=>{render(active-1);});
 dots.forEach(d=>d.addEventListener('click',()=>{const n=Number(d.dataset.heroDot);if(Number.isInteger(n))render(n);}));
 root.addEventListener('mouseenter',pause);root.addEventListener('mouseleave',resume);
 root.addEventListener('focusin',pause);root.addEventListener('focusout',e=>{if(!root.contains(e.relatedTarget))resume();});
 document.addEventListener('visibilitychange',()=>{if(document.hidden)pause();else{paused=false;startTimer(remaining||6000);}});
 let startX=0,startY=0,moved=false;
 root.addEventListener('pointerdown',e=>{if(e.pointerType==='mouse')return;startX=e.clientX;startY=e.clientY;moved=false;});
 root.addEventListener('pointermove',e=>{if(!startX)return;const dx=e.clientX-startX,dy=e.clientY-startY;if(Math.abs(dx)>10&&Math.abs(dx)>Math.abs(dy))moved=true;});
 root.addEventListener('pointerup',e=>{if(!moved){startX=0;return;}const dx=e.clientX-startX;if(Math.abs(dx)>45)render(dx<0?active+1:active-1);startX=0;});
 root.addEventListener('pointercancel',()=>{startX=0;});
 root.addEventListener('keydown',e=>{if(e.key==='ArrowLeft'){e.preventDefault();render(active+1);}if(e.key==='ArrowRight'){e.preventDefault();render(active-1);}});
 render(0);
});