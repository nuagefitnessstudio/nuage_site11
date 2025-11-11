<?php
// NuAge Fitness — Simple Gallery (no tabs/filters, themed to Classes page)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Gallery — NuAge Fitness Studio</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root{ --ink:#111418; --muted:#6a6d74; --line:#e9e6e1; --bone:#faf7f2; --navy:#002D72; --coral:#EB1F48; }
    *{box-sizing:border-box} html,body{height:100%}
    body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,Helvetica,sans-serif;line-height:1.6;background:#fff;color:var(--ink)}
    h1{font-family:'Playfair Display',serif;font-weight:700;letter-spacing:.02em;font-size:clamp(40px,6vw,72px);line-height:1.15;margin:0}
    .container{max-width:1200px;margin:0 auto;padding:0 24px}

    /* Topbar (center brand like Classes page) */
    .topbar{position:sticky;top:0;z-index:60;height:64px;background:#fff;border-bottom:1px solid #f1f1f1}
    .topbar-inner{position:relative;max-width:1200px;margin:0 auto;height:64px;display:flex;align-items:center;justify-content:center}
    .brand-name{font-family:'Playfair Display',serif;font-weight:700;letter-spacing:.04em;font-size:18px;color:#0d2a55}
    .hamburger{position:absolute;right:8px;top:50%;transform:translateY(-50%);display:inline-flex;align-items:center;justify-content:center;width:42px;height:42px;border-radius:999px;border:1px solid rgba(0,0,0,.08);background:#fff9;backdrop-filter:blur(6px);cursor:pointer}

    /* Drawer */
    .overlay{position:fixed;inset:0;background:rgba(17,20,24,.4);backdrop-filter:blur(2px);opacity:0;pointer-events:none;transition:opacity .25s ease;z-index:59}
    .overlay.show{opacity:1;pointer-events:auto}
    .drawer{position:fixed;top:0;right:0;height:100%;width:min(88vw,360px);background:#fff;border-left:1px solid var(--line);box-shadow:0 10px 32px rgba(0,0,0,.16);transform:translateX(100%);transition:transform .28s ease;z-index:60;display:flex;flex-direction:column}
    .drawer.show{transform:none}
    .drawer-nav{padding:14px;display:grid;gap:10px}
    .pill-link{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:11px 16px;border-radius:999px;border:1px solid #e8e8e8;background:#f7f7f7;color:#222;font-weight:700}
    .pill-link.primary{background:#0d2a55;color:#fff;border-color:#0d2a55}

    /* Hero band */
    .hero{background:var(--navy);color:#fff;text-align:center;padding:72px 16px 72px}
    .hero p{opacity:.9;margin:10px 0 0}

    /* Simple Gallery Grid */
    .wrap{background:var(--bone);border-top:1px solid var(--line)}
    .grid{display:grid;grid-template-columns:repeat(1, 1fr);gap:14px;padding:34px 0}
    @media(min-width:560px){.grid{grid-template-columns:repeat(2, 1fr)}}
    @media(min-width:900px){.grid{grid-template-columns:repeat(3, 1fr)}}
    @media(min-width:1200px){.grid{grid-template-columns:repeat(4, 1fr)}}
    .tile{position:relative;border-radius:18px;overflow:hidden;background:#fff;border:1px solid var(--line)}
    .ratio{position:relative;width:100%;padding-top:72%} /* uniform 4:3 aspect for clean rows */
    .ratio>img,.ratio>video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
    .ratio>img{transition:transform .35s ease}
    .tile:hover .ratio>img{transform:scale(1.02)}
    .cap{position:absolute;left:10px;bottom:10px;background:rgba(17,20,24,.6);color:#fff;padding:6px 10px;border-radius:12px;font-weight:700;font-size:12px;backdrop-filter:blur(2px)}

    /* Lightbox */
    .lightbox{position:fixed;inset:0;background:rgba(6,10,18,.92);display:none;align-items:center;justify-content:center;z-index:70}
    .lightbox.open{display:flex}
    .lb-content{position:relative;max-width:min(92vw,1200px);max-height:86vh;display:grid;place-items:center}
    .lb-img,.lb-video{max-width:100%;max-height:86vh;border-radius:16px;box-shadow:0 24px 80px rgba(0,0,0,.55)}
    .lb-btn{position:absolute;background:rgba(255,255,255,.12);color:#fff;border:none;border-radius:12px;padding:10px 14px;cursor:pointer;font-weight:800}
    .lb-close{top:-52px;right:0}
    .lb-prev{left:-56px;top:50%;transform:translateY(-50%)}
    .lb-next{right:-56px;top:50%;transform:translateY(-50%)}
    @media(max-width:700px){.lb-prev{left:6px}.lb-next{right:6px}.lb-close{top:8px;right:8px}}

    /* Footer */
    footer{color:#7a7e85;text-align:center;padding:30px 0;font-size:14px}
  
/* ===== Drawer CSS from classes.php ===== */
.overlay{
      position:fixed;inset:0;background:rgba(17,20,24,.4);backdrop-filter:blur(2px);
      opacity:0;pointer-events:none;transition:opacity .25s ease;z-index:59;
    }
    .overlay.show{opacity:1;pointer-events:auto}
    .drawer{
      position:fixed;top:0;right:0;height:100%;width:min(88vw,360px);
      background:#fff;border-left:1px solid var(--line);box-shadow:0 10px 32px rgba(0,0,0,.16);
      transform:translateX(100%);transition:transform .28s ease;z-index:60;display:flex;flex-direction:column;
    }
    .drawer.show{transform:none}
    .drawer-header{display:flex;align-items:center;justify-content:space-between;padding:16px 18px;border-bottom:1px solid var(--line)}
    .drawer-header .brand img{height:24px}
    .drawer-close{background:transparent;border:none;font-size:28px;line-height:1;cursor:pointer;padding:6px;border-radius:8px}
    .drawer-nav{padding:10px 14px;display:grid;gap:10px}
    .drawer .pill-link{background:#f7f7f7}
    .drawer .pill-link.primary{background:#0d2a55;color:#fff;border:1px solid #0d2a55}

    
</style>
</head>
<body>

  <header class="topbar">
    <div class="topbar-inner">
      <div class="brand-name">NuAge Fitness Studios</div>
      <button class="hamburger" aria-label="Menu" onclick="toggleDrawer()">
        <svg viewBox="0 0 24 24" fill="none"><path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </button>
    </div>
  </header>

  <!-- Drawer -->
  <div id="overlay" class="overlay" onclick="toggleDrawer(false)"></div>
  <!-- Drawer + overlay -->
<div class="overlay" id="navOverlay" hidden></div>
<aside class="drawer" id="navDrawer" hidden aria-hidden="true">
  <div class="drawer-header">
    <div class="brand">
      <img loading="eager" src="assets/IMG_2413.png" alt="NuAge logo">
      <div class="brand-name">
        <span style="color:var(--navy);">Nu</span><span style="color:var(--coral);">Age</span>
        <span style="color:var(--navy);">Fitness</span>
        <span style="color:var(--navy);">Studios</span>
      </div>
    </div>
    <button class="drawer-close" id="navClose" aria-label="Close menu">&times;</button>
  </div>
  <nav class="drawer-nav">
    <a href="location.php" class="pill-link primary"><span style="font-weight:700">Find a Location</span></a>
    <a href="javascript:void(0)" onclick="openModal()" class="pill-link">Member Login</a>
    <a href="classes.php">Classes</a>
    <a href="team.php">Meet the Team</a>
    <a href="pricing.php">Pricing</a>
  </nav>
</aside>

  <!-- Hero -->
  <section class="hero">
    <div class="container">
      <h1>Gallery</h1>
      <p>Facilities • Classes • Community • Transformations</p>
    </div>
  </section>

  <!-- Gallery -->
  <section class="wrap">
    <main class="container">
      <div id="grid" class="grid" aria-live="polite"></div>
    </main>
  </section>

  <footer class="container">© <?php echo date('Y'); ?> NuAge Fitness Studio. All rights reserved.</footer>

<script>
function toggleDrawer(force){
  const d = document.getElementById('drawer');
  const o = document.getElementById('overlay');
  const show = (typeof force === 'boolean') ? force : !d.classList.contains('show');
  d.classList.toggle('show', show);
  o.classList.toggle('show', show);
  d.setAttribute('aria-hidden', String(!show));
}

// Replace with your asset files
const items = [
  {src:'assets/gallery/facility-weights.jpg', type:'image', title:'Strength Zone'},
  {src:'assets/gallery/class-hiit.jpg', type:'image', title:'Full Body Bootcamp'},
  {src:'assets/gallery/members-strong.jpg', type:'image', title:'Member Spotlight'},
  {src:'assets/gallery/transform-1.jpg', type:'image', title:'8-Week Progress'},
  {src:'assets/gallery/videos/nuage-tour.mp4', type:'video', title:'Studio Walkthrough'},
  {src:'assets/gallery/class-yoga.jpg', type:'image', title:'Mobility & Flow'},
  {src:'assets/gallery/facility-cardio.jpg', type:'image', title:'Cardio Deck'},
  {src:'assets/gallery/members-class.jpg', type:'image', title:'Group Energy'},
  {src:'assets/gallery/transform-2.jpg', type:'image', title:'Glute Gains'}
];

const grid = document.getElementById('grid');
function tile(item, idx){
  const t = document.createElement('figure'); t.className='tile'; t.dataset.index=idx;
  const r = document.createElement('div'); r.className='ratio';
  if(item.type==='video'){
    const v = document.createElement('video'); v.src=item.src; v.muted=true; v.playsInline=true; v.loop=true;
    v.addEventListener('mouseenter', ()=> v.play());
    v.addEventListener('mouseleave', ()=> v.pause());
    v.addEventListener('click', ()=> openLB(idx));
    r.appendChild(v);
  } else {
    const img = document.createElement('img'); img.src=item.src; img.alt=item.title; img.loading='lazy';
    img.addEventListener('click', ()=> openLB(idx));
    r.appendChild(img);
  }
  t.appendChild(r);
  const cap = document.createElement('figcaption'); cap.className='cap'; cap.textContent=item.title;
  t.appendChild(cap);
  return t;
}
items.forEach((it,i)=> grid.appendChild(tile(it,i)));

// Lightbox
let cur=-1; const lb = document.createElement('div'); lb.className='lightbox'; lb.id='lb';
lb.innerHTML = `<div class="lb-content">
  <button class="lb-btn lb-close" aria-label="Close">✕</button>
  <button class="lb-btn lb-prev" aria-label="Previous">‹</button>
  <div id="lbmedia"></div>
  <button class="lb-btn lb-next" aria-label="Next">›</button>
</div>`;
document.body.appendChild(lb);
const lbm = document.getElementById('lbmedia');
lb.addEventListener('click', (e)=>{ if(e.target.id==='lb') closeLB(); });
lb.querySelector('.lb-close').onclick = closeLB;
lb.querySelector('.lb-prev').onclick = ()=> navLB(-1);
lb.querySelector('.lb-next').onclick = ()=> navLB(1);
document.addEventListener('keydown', (e)=>{
  if(lb.classList.contains('open')){
    if(e.key==='Escape') closeLB();
    if(e.key==='ArrowLeft') navLB(-1);
    if(e.key==='ArrowRight') navLB(1);
  }
});
function openLB(i){
  cur=i; renderLB(); lb.classList.add('open'); document.body.style.overflow='hidden';
}
function closeLB(){ lb.classList.remove('open'); document.body.style.overflow=''; }
function navLB(d){ cur=(cur+d+items.length)%items.length; renderLB(); }
function renderLB(){
  const it = items[cur]; lbm.innerHTML='';
  if(it.type==='video'){ const v=document.createElement('video'); v.src=it.src; v.controls=true; v.autoplay=true; v.className='lb-video'; lbm.appendChild(v); }
  else { const img=document.createElement('img'); img.src=it.src; img.alt=it.title; img.className='lb-img'; lbm.appendChild(img); }
}
</script>
</body>
</html>
