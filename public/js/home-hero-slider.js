document.addEventListener('DOMContentLoaded',function(){
 const root=document.getElementById('homeHero');if(!root)return;
 const slides=[...root.querySelectorAll('.hero-slide')],dots=[...root.querySelectorAll('[data-hero-dot]')],progress=root.querySelector('[data-hero-progress]'),progressWrap=root.querySelector('.hero-progress'),count=root.querySelector('[data-hero-count]');
 if(slides.length<2)return;
 const DURATION=6000,reduce=window.matchMedia&&window.matchMedia('(prefers-reduced-motion: reduce)').matches;
 let active=0,timer=null,startedAt=0,remaining=DURATION,paused=false,startPercent=0;
 function setProgress(percent,running){if(!progress)return;progress.style.animation='none';progress.style.width=Math.max(0,Math.min(100,percent))+'%';if(progressWrap)progressWrap.classList.toggle('is-running',!!running);}
 function render(index){
  active=(index+slides.length)%slides.length;
  slides.forEach((s,i)=>{const on=i===active;s.classList.toggle('is-active',on);s.setAttribute('aria-hidden',on?'false':'true');});
  dots.forEach((d,i)=>d.setAttribute('aria-selected',i===active?'true':'false'));
  if(count)count.textContent=String(active+1).padStart(2,'0')+' / '+String(slides.length).padStart(2,'0');
  remaining=DURATION;startPercent=0;setProgress(0,false);resetTimer();
 }
 function startTimer(ms){clearTimeout(timer);if(reduce||paused||document.hidden||root.matches(':focus-within'))return;remaining=ms||DURATION;startedAt=performance.now();if(progress){progress.style.transition='none';progress.style.width=startPercent+'%';requestAnimationFrame(()=>{progress.style.transition='width '+(remaining/1000)+'s linear';progress.style.width='100%';});}timer=setTimeout(()=>render(active+1),remaining);}
 function resetTimer(){clearTimeout(timer);remaining=DURATION;startPercent=0;setProgress(0,false);startTimer(DURATION);}
 function pause(){if(paused)return;paused=true;clearTimeout(timer);if(startedAt){const elapsed=Math.max(0,performance.now()-startedAt);remaining=Math.max(250,remaining-elapsed);startPercent=Math.min(100,((DURATION-remaining)/DURATION)*100);}if(progress){progress.style.transition='none';progress.style.width=startPercent+'%';}if(progressWrap)progressWrap.classList.remove('is-running');}
 function resume(){if(!paused)return;paused=false;if(document.hidden||root.matches(':focus-within'))return;startTimer(remaining||DURATION);}
 root.querySelector('[data-hero-next]')?.addEventListener('click',()=>render(active+1));
 root.querySelector('[data-hero-prev]')?.addEventListener('click',()=>render(active-1));
 dots.forEach(d=>d.addEventListener('click',()=>{const n=Number(d.dataset.heroDot);if(Number.isInteger(n))render(n);}));
 root.addEventListener('mouseenter',pause);root.addEventListener('mouseleave',resume);root.addEventListener('focusin',pause);root.addEventListener('focusout',e=>{if(!root.contains(e.relatedTarget))resume();});
 document.addEventListener('visibilitychange',()=>{if(document.hidden)pause();else if(paused){paused=false;startTimer(remaining||DURATION);}});
 let startX=0,startY=0,moved=false;
 root.addEventListener('pointerdown',e=>{if(e.pointerType==='mouse')return;startX=e.clientX;startY=e.clientY;moved=false;});
 root.addEventListener('pointermove',e=>{if(!startX)return;const dx=e.clientX-startX,dy=e.clientY-startY;if(Math.abs(dx)>10&&Math.abs(dx)>Math.abs(dy))moved=true;});
 root.addEventListener('pointerup',e=>{if(!moved){startX=0;return;}const dx=e.clientX-startX;if(Math.abs(dx)>45)render(dx<0?active+1:active-1);startX=0;});root.addEventListener('pointercancel',()=>{startX=0;});
 root.addEventListener('keydown',e=>{if(e.key==='ArrowLeft'){e.preventDefault();render(active+1);}else if(e.key==='ArrowRight'){e.preventDefault();render(active-1);}});
 render(0);
});