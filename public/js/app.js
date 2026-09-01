function toggleCart(){
    const panel=document.getElementById('cart-panel');
    const overlay=document.getElementById('cart-overlay');

    if(!panel)return;

    panel.classList.toggle('open');

    if(overlay)
        overlay.style.display=panel.classList.contains('open')?'block':'none';
}

document.addEventListener('DOMContentLoaded',()=>{
    const added=document.body.dataset.cartAdded;

    if(added==='1') toggleCart();
});