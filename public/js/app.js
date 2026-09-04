function toggleCart(force){
    const panel=document.getElementById('cart-panel');
    const overlay=document.getElementById('cart-overlay');
    if(!panel)return;
    const open=typeof force==='boolean'?force:!panel.classList.contains('open');
    panel.classList.toggle('open',open);
    if(overlay) overlay.style.display=open?'block':'none';
}

document.addEventListener('DOMContentLoaded',()=>{
    const added=document.body.dataset.cartAdded;
    if(added==='1') toggleCart(true);
});
