<?php
// NuAge Fitness — Gallery Page (matched to Classes page aesthetic)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>Gallery — NuAge Fitness Studio</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --ink:#111418; --muted:#6a6d74; --line:#e9e6e1; --bone:#faf7f2;
      --pill:#efebe6cc; --navy:#002D72; --coral:#EB1F48;
      --shadow:0 10px 28px rgba(0,0,0,.10);
    }
    *{box-sizing:border-box} html,body{height:100%}
    body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,Helvetica,sans-serif;line-height:1.6;background:#fff;color:var(--ink)}
    h1,h2,h3{font-family:'Playfair Display',serif;letter-spacing:.02em;line-height:1.15;margin:0 0 .25em}
    h1{font-size:clamp(40px,6vw,72px);font-weight:700}
    h2{font-size:clamp(22px,3.5vw,30px);font-weight:700}
    a{color:inherit;text-decoration:none}
    .container{max-width:1200px;margin:0 auto;padding:0 24px}

    /* Topbar simplified (same placement/feel as Classes) */
    .topbar{position:sticky;top:0;z-index:60;height:64px;background:#fff;border-bottom:1px solid #f1f1f1}
    .topbar-inner{position:relative;max-width:1200px;margin:0 auto;height:64px;display:flex;align-items:center;justify-content:center}
    .brand-name{font-family:'Playfair Display',serif;font-weight:700;letter-spacing:.04em;font-size:18px;color:#0d2a55}
    .hamburger{
      position:absolute;right:8px;top:50%;transform:translateY(-50%);
      display:inline-flex;align-items:center;justify-content:center;
      width:42px;height:42px;border-radius:999px;border:1px solid rgba(0,0,0,.08);
      background:#fff9; backdrop-filter:blur(6px); cursor:pointer;
    }

    /* Drawer */
    .overlay{position:fixed;inset:0;background:rgba(17,20,24,.4);backdrop-filter:blur(2px);opacity:0;pointer-events:none;transition:opacity .25s ease;z-index:59}
    .overlay.show{opacity:1;pointer-events:auto}
    .drawer{position:fixed;top:0;right:0;height:100%;width:min(88vw,360px);background:#fff;border-left:1px solid var(--line);box-shadow:0 10px 32px rgba(0,0,0,.16);transform:translateX(100%);transition:transform .28s ease;z-index:60;display:flex;flex-direction:column}
    .drawer.show{transform:none}
    .drawer-nav{padding:14px;display:grid;gap:10px}
    .pill-link{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:11px 16px;border-radius:999px;border:1px solid #e8e8e8;background:#f7f7f7;color:#222;font-weight:700}
    .pill-link.primary{background:#0d2a55;color:#fff;border-color:#0d2a55}

    /* Hero band (matches screenshot) */
    .hero{background:var(--navy);color:#fff;text-align:center;padding:72px 16px 88px}
    .hero p{opacity:.9;margin:6px 0 20px}
    .cta-row{display:flex;gap:14px;justify-content:center;flex-wrap:wrap}
    .btn{display:inline-flex;align-items:center;justify-content:center;font-weight:800;border-radius:14px;padding:14px 22px;border:1px solid transparent;cursor:pointer}
    .btn.coral{background:var(--coral);color:#fff}
    .btn.light{background:#fff;color:#16223a;border-color:#eee}

    /* Filter row (styled like pill links) */
    .toolbar{display:flex;flex-wrap:wrap;gap:10px;align-items:center;justify-content:center;background:#fff;margin:-36px auto 0;padding:12px;border-radius:999px;box-shadow:var(--shadow);width:min(92%, 860px)}
    .chip{border:1px solid #e6e6e6;background:#f7f7f7;padding:9px 14px;border-radius:999px;font-weight:700;cursor:pointer}
    .chip.active{background:var(--coral);color:#fff;border-color:var(--coral)}

    /* Gallery area */
    .wrap{background:var(--bone);border-top:1px solid var(--line)}
    .grid{columns:1 280px;column-gap:18px;padding:34px 0}
    @media(min-width:640px){.grid{columns:2 280px}}
    @media(min-width:940px){.grid{columns:3 280px}}
    @media(min-width:1200px){.grid{columns:4 280px}}
    .card{break-inside:avoid;margin:0 0 18px;border-radius:22px;border:1px solid var(--line);background:#fff;box-shadow:0 2px 4px rgba(0,0,0,.03)}
    .media{border-bottom:1px solid #f3f0ea}
    .card img,.card video{width:100%;height:auto;display:block;border-top-left-radius:22px;border-top-right-radius:22px}
    .body{padding:14px 16px}
    .title{font-family:'Playfair Display',serif;font-weight:700;font-size:18px;margin:0 0 6px}
    .muted{color:var(--muted);font-size:14px}
    .actions{padding:14px 16px 16px}
    .btn.full{width:100%;border-radius:12px}

    /* Lightbox (minimal) */
    .lightbox{position:fixed;inset:0;background:rgba(6,10,18,.92);display:none;align-items:center;justify-content:center;z-index:70}
    .lightbox.open{display:flex}
    .lb-content{position:relative;max-width:min(92vw,1200px);max-height:86vh;display:grid;place-items:center}
    .lb-img,.lb-video{max-width:100%;max-height:86vh;border-radius:16px;box-shadow:0 24px 80px rgba(0,0,0,.55)}
    .lb-btn{position:absolute;background:rgba(255,255,255,.12);color:#fff;border:none;border-radius:12px;padding:10px 14px;cursor:pointer;font-weight:800}
    .lb-close{top:-52px;right:0}
    .lb-prev{left:-56px;top:50%;transform:translateY(-50%)}
    .lb-next{right:-56px;top:50%;transform:translateY(-50%)}
    @media(max-width:700px){.lb-prev{left:6px}.lb-next{right:6px}.lb-close{top:8px;right:8px}}

    /* CTA band at bottom (navy) */
    .cta-band{background:var(--navy);color:#fff;text-align:center;padding:64px 16px}
    .cta-band p{opacity:.88}
  </style>
</head>
<body>

  <!-- Topbar -->
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
  <aside id="drawer" class="drawer" aria-hidden="true">
    <nav class="drawer-nav">
      <a class="pill-link" href="index.php">Home</a>
      <a class="pill-link" href="classes.php">Classes</a>
      <a class="pill-link primary" href="gallery.php" aria-current="page">Gallery</a>
    </nav>
  </aside>

  <!-- Hero -->
  <section class="hero">
    <div class="container">
      <h1>Gallery</h1>
      <p>Facilities, classes, community, & transformations.</p>
      <div class="cta-row">
        <a class="btn coral" href="classes.php">See Classes</a>
        <a class="btn light" href="index.php">Back Home</a>
      </div>
    </div>
  </section>

  <!-- Filters -->
  <div class="container" style="position:relative">
    <div class="toolbar" id="filterBar" role="tablist">
      <button class="chip active" data-filter="all" aria-selected="true">All</button>
      <button class="chip" data-filter="classes">Classes</button>
      <button class="chip" data-filter="facilities">Facilities</button>
      <button class="chip" data-filter="members">Members</button>
      <button class="chip" data-filter="transformations">Transformations</button>
      <button class="chip" data-filter="video">Video</button>
    </div>
  </div>

  <!-- Gallery -->
  <section class="wrap">
    <main class="container">
      <div id="grid" class="grid" aria-live="polite"></div>
    </main>
  </section>

  <!-- CTA band -->
  <section class="cta-band">
    <div class="container">
      <h2>Train with Intention</h2>
      <p>Science‑backed classes, motivating coaches, real results.</p>
    </div>
  </section>

  <footer class="container" style="text-align:center;padding:32px 0;color:#7a7e85;font-size:14px">
    © <?php echo date('Y'); ?> NuAge Fitness Studio. All rights reserved.
  </footer>

<script>
function toggleDrawer(force){
  const d = document.getElementById('drawer');
  const o = document.getElementById('overlay');
  const show = (typeof force === 'boolean') ? force : !d.classList.contains('show');
  d.classList.toggle('show', show);
  o.classList.toggle('show', show);
  d.setAttribute('aria-hidden', String(!show));
}

// Data
const items = [
  {src:'assets/gallery/facility-weights.jpg', type:'image', tag:'facilities', title:'Strength Zone'},
  {src:'assets/gallery/class-hiit.jpg', type:'image', tag:'classes', title:'Full Body Bootcamp'},
  {src:'assets/gallery/members-strong.jpg', type:'image', tag:'members', title:'Member Spotlight'},
  {src:'assets/gallery/transform-1.jpg', type:'image', tag:'transformations', title:'8‑Week Progress'},
  {src:'assets/gallery/videos/nuage-tour.mp4', type:'video', tag:'video', title:'Studio Walkthrough'},
  {src:'assets/gallery/class-yoga.jpg', type:'image', tag:'classes', title:'Mobility & Flow'},
  {src:'assets/gallery/facility-cardio.jpg', type:'image', tag:'facilities', title:'Cardio Deck'},
  {src:'assets/gallery/members-class.jpg', type:'image', tag:'members', title:'Group Energy'},
  {src:'assets/gallery/transform-2.jpg', type:'image', tag:'transformations', title:'Glute Gains'}
];

const grid = document.getElementById('grid');
function card(item, idx){
  const f = document.createElement('figure'); f.className='card'; f.dataset.tag=item.tag; f.dataset.index=idx;
  const wrap = document.createElement('div'); wrap.className='media';

  if(item.type==='video'){
    const v = document.createElement('video'); v.src=item.src; v.muted=true; v.playsInline=true; v.loop=true;
    v.addEventListener('mouseenter', ()=> v.play());
    v.addEventListener('mouseleave', ()=> v.pause());
    v.addEventListener('click', ()=> openLB(idx));
    wrap.appendChild(v);
  }else{
    const img = document.createElement('img'); img.src=item.src; img.alt=item.title; img.loading='lazy';
    img.addEventListener('click', ()=> openLB(idx));
    wrap.appendChild(img);
  }
  f.appendChild(wrap);

  const body = document.createElement('div'); body.className='body';
  body.innerHTML = `<div class="title">${item.title}</div><div class="muted">#${item.tag}</div>`;
  f.appendChild(body);

  const actions = document.createElement('div'); actions.className='actions';
  const btn = document.createElement('a'); btn.className='btn coral full'; btn.textContent = 'View';
  btn.href = 'javascript:void(0)'; btn.addEventListener('click', ()=> openLB(idx));
  actions.appendChild(btn);
  f.appendChild(actions);

  return f;
}
items.forEach((it,i)=> grid.appendChild(card(it,i)));

// Filters
document.getElementById('filterBar').addEventListener('click', (e)=>{
  const b = e.target.closest('.chip'); if(!b) return;
  document.querySelectorAll('.chip').forEach(c=>c.classList.remove('active'));
  b.classList.add('active');
  const f = b.dataset.filter;
  [...grid.children].forEach(el=>{
    el.style.display = (f==='all' || el.dataset.tag===f) ? '' : 'none';
  });
});

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
