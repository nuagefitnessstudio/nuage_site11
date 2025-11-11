<?php
// NuAge Fitness — Gallery (EXACT navbar/drawer/hero as Classes page)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gallery — NuAge Fitness Studio</title>
  <style>
:root{
      --ink:#111418; --muted:#6a6d74; --line:#e9e6e1; --bone:#faf7f2;
      --pill:#efebe6cc; --navy:#002D72; --coral:#EB1F48;
    }
    *{box-sizing:border-box} html,body{height:100%} body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,Helvetica,sans-serif;line-height:1.6;background:#fff}
    h1,h2,h3{font-family:'Playfair Display',serif;letter-spacing:.02em;line-height:1.15;margin:0 0 .25em}
    h1{font-size:clamp(32px,5vw,54px);font-weight:600} h2{font-size:clamp(20px,3vw,28px);font-weight:600} h3{font-size:clamp(18px,2vw,22px);font-weight:600}
    a{color:inherit;text-decoration:none} .container{max-width:1200px;margin:0 auto;padding:0 24px}

    /* ===== Topbar (hamburger) ===== */
    .topbar{
      position:fixed;top:16px;left:50%;transform:translateX(-50%);
      display:flex;align-items:center;justify-content:center;gap:14px;
      width:min(92vw,980px);background:var(--pill);backdrop-filter:blur(8px);
      border:1px solid rgba(0,0,0,.08);border-radius:999px;
      padding:10px 16px;z-index:60
    }
   /* Force hamburger icon lines to black */
   .hamburger svg { color:#000 !important; }
    .pill-link{display:inline-flex;align-items:center;gap:10px;font-weight:600;padding:10px 14px;border:1px solid rgba(0,0,0,.06);border-radius:999px;transition:background .25s,transform .2s}
    .pill-link:hover{background:#ffffffb3;transform:translateY(-1px)}
    .brand{display:flex;align-items:center;gap:10px}
    .brand img{height:28px}
    .brand-name{font-weight:800;letter-spacing:.08em;color:var(--navy)}
    .brand-name span{color:var(--coral)}

    /* Hamburger button */
    .hamburger{
      position:absolute;right:8px;top:50%;transform:translateY(-50%);
      display:inline-flex;align-items:center;justify-content:center;
      width:42px;height:42px;border-radius:999px;border:1px solid rgba(0,0,0,.08);
      background:#fff9; backdrop-filter:blur(6px); cursor:pointer;
      transition:transform .15s ease, background .2s ease;
    }
    .hamburger:active{transform:translateY(-50%) scale(.98)}
    .hamburger svg{width:22px;height:22px}

    /* Drawer + overlay */
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

    /* ===== Hero (uses same 'hero-ot' block) ===== */
    .hero-ot{
      min-height:40vh;display:grid;place-items:center;text-align:center;color:#fff;background:var(--navy);
    }
    .hero-ot .hero-inner h1{font-size:clamp(2rem,5vw,3.5rem);margin:.25rem 0}
    .hero-ot .hero-inner p{opacity:.92;margin-bottom:1rem}
    .hero-ot .cta-row{display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap}

    /* ===== Simple Gallery ===== */
    .wrap{background:var(--bone);border-top:1px solid var(--line)}
    .grid{display:grid;grid-template-columns:repeat(1, 1fr);gap:14px;padding:34px 0}
    @media(min-width:560px){.grid{grid-template-columns:repeat(2, 1fr)}}
    @media(min-width:900px){.grid{grid-template-columns:repeat(3, 1fr)}}
    @media(min-width:1200px){.grid{grid-template-columns:repeat(4, 1fr)}}
    .tile{position:relative;border-radius:18px;overflow:hidden;background:#fff;border:1px solid var(--line)}
    .ratio{position:relative;width:100%;padding-top:72%} /* uniform 4:3 aspect */
    .ratio>img,.ratio>video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
    .ratio>img{transition:transform .35s ease} .tile:hover .ratio>img{transform:scale(1.02)}
    .cap{position:absolute;left:10px;bottom:10px;background:rgba(17,20,24,.6);color:#fff;padding:6px 10px;border-radius:12px;font-weight:700;font-size:12px;backdrop-filter:blur(2px)}

    /* Footer */
    .footer{padding:34px 0;color:#666;text-align:center;font-size:14px}
  </style>
</head>
<body>

<!-- Hamburger topbar -->
<div class="topbar" role="navigation" aria-label="Main">
  <div class="brand" aria-label="NuAge">
    <img loading="eager" referrerpolicy="no-referrer" src="assets/IMG_2413.png" alt="NuAge logo">
    <div class="brand-name">
      <span style="color:var(--navy);">Nu</span><span style="color:var(--coral);">Age</span>
      <span style="color:var(--navy);">Fitness</span>
      <span style="color:var(--navy);">Studios</span>
    </div>
  </div>
  <button class="hamburger" id="navToggle" aria-label="Open menu" aria-expanded="false" aria-controls="navDrawer">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <path d="M3 6h18M3 12h18M3 18h18"/>
    </svg>
  </button>
</div>

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

  <!-- HERO (same structure, gallery copy) -->
  <section class="hero-ot">
    <div class="hero-inner">
      <h1>Gallery</h1>
      <p>Facilities • Classes • Community • Transformations</p>
      <!-- (Optional) could add buttons, but keeping hero minimal for gallery -->
    </div>
  </section>

  <!-- GALLERY GRID -->
  <section class="wrap">
    <div class="container">
      <div id="grid" class="grid" aria-live="polite"></div>
    </div>
  </section>

  <div class="footer">© <?php echo date('Y'); ?> NuAge Fitness Studio. All rights reserved.</div>

<script>
// Drawer behavior EXACT to classes page IDs
const navToggle = document.getElementById('navToggle');
const navDrawer = document.getElementById('navDrawer');
const navOverlay = document.getElementById('navOverlay');
const navClose = document.getElementById('navClose');
function setDrawer(show){
  if(show){
    navDrawer.removeAttribute('hidden'); navOverlay.removeAttribute('hidden');
    requestAnimationFrame(()=>{ navDrawer.classList.add('show'); navOverlay.classList.add('show'); });
    navToggle.setAttribute('aria-expanded','true');
  } else {
    navDrawer.classList.remove('show'); navOverlay.classList.remove('show'); navToggle.setAttribute('aria-expanded','false');
    setTimeout(()=>{ navDrawer.setAttribute('hidden',''); navOverlay.setAttribute('hidden',''); }, 250);
  }
}
navToggle.addEventListener('click', ()=> setDrawer(!navDrawer.classList.contains('show')));
navClose.addEventListener('click', ()=> setDrawer(false));
navOverlay.addEventListener('click', ()=> setDrawer(false));

// Minimal stub for Member Login if the modal isn't present on this page
function openModal(){
  alert("Once logged in, you’ll be able to:\n• Access your account\n• Purchase membership\n• Book classes\n• And more.\n\nType A for Apple\nType G for Google");
}

// Gallery items (swap with your assets)
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

// Build grid
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

// Lightbox (kept minimal to avoid style drift)
let cur=-1;
const lb = document.createElement('div'); lb.className='overlay'; lb.id='lb'; lb.style.background='rgba(6,10,18,.92)';
lb.addEventListener('click', (e)=>{ if(e.target===lb) closeLB(); });
document.body.appendChild(lb);
const mediaWrap = document.createElement('div'); mediaWrap.style.cssText='position:fixed;inset:0;display:grid;place-items:center;z-index:71;';
lb.appendChild(mediaWrap);
function openLB(i){
  cur=i; renderLB(); lb.classList.add('show');
}
function closeLB(){ lb.classList.remove('show'); mediaWrap.innerHTML=''; }
function renderLB(){
  mediaWrap.innerHTML='';
  const it = items[cur];
  if(it.type==='video'){ const v=document.createElement('video'); v.src=it.src; v.controls=true; v.autoplay=true; v.style.maxWidth='92vw'; v.style.maxHeight='86vh'; v.style.borderRadius='16px'; mediaWrap.appendChild(v); }
  else { const img=document.createElement('img'); img.src=it.src; img.alt=it.title; img.style.maxWidth='92vw'; img.style.maxHeight='86vh'; img.style.borderRadius='16px'; mediaWrap.appendChild(img); }
  // Close button
  const x=document.createElement('button'); x.textContent='×'; x.setAttribute('aria-label','Close'); x.style.cssText='position:fixed;top:18px;right:22px;font-size:34px;background:transparent;border:none;color:#fff;cursor:pointer;z-index:72';
  x.onclick=closeLB; mediaWrap.appendChild(x);
}
document.addEventListener('keydown', (e)=>{
  if(!lb.classList.contains('show')) return;
  if(e.key==='Escape') closeLB();
  if(e.key==='ArrowRight'){ cur=(cur+1)%items.length; renderLB(); }
  if(e.key==='ArrowLeft'){ cur=(cur-1+items.length)%items.length; renderLB(); }
});
</script>

</body>
</html>
