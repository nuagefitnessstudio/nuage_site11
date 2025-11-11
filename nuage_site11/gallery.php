<?php
// NuAge Fitness — Gallery page styled like Classes (navbar, hero, cards, CTA band)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gallery — NuAge Fitness Studios</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <style>
    :root{ --ink:#111418; --muted:#6a6d74; --line:#e9e6e1; --bone:#faf7f2; --navy:#002D72; --coral:#EB1F48; }
    *{box-sizing:border-box} html,body{height:100%}
    body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,Helvetica,sans-serif;background:#fff;color:var(--ink);line-height:1.6}
    h1,h2,h3{font-family:'Playfair Display',serif;margin:0 0 .25em;line-height:1.15}
    h1{font-size:clamp(40px,6vw,68px);font-weight:700}
    h2{font-size:clamp(22px,3.2vw,28px);font-weight:700;color:var(--navy)}
    h3{font-size:clamp(18px,2.2vw,22px);font-weight:700;color:var(--navy)}
    .container{max-width:1200px;margin:0 auto;padding:0 24px}
    a{color:inherit;text-decoration:none}

    /* Floating rounded navbar (matches Classes) */
    .topbar{position:fixed;top:16px;left:50%;transform:translateX(-50%);width:min(92vw,980px);z-index:60}
    .topbar-inner{position:relative;height:56px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.96);backdrop-filter:blur(8px);border:1px solid rgba(0,0,0,.08);border-radius:999px;box-shadow:0 10px 26px rgba(0,0,0,.08);padding:10px 16px}
    .brand{display:flex;align-items:center;gap:10px}
    .brand img{height:24px}
    .brand-name{font-family:'Playfair Display',serif;font-weight:700;letter-spacing:.04em;color:#0d2a55}
    .brand-name .nu{color:var(--navy)} .brand-name .age{color:var(--coral)}
    .hamburger{position:absolute;right:8px;top:50%;transform:translateY(-50%);display:inline-flex;align-items:center;justify-content:center;width:42px;height:42px;border-radius:999px;border:1px solid rgba(0,0,0,.08);background:#fff9;backdrop-filter:blur(6px);cursor:pointer}
    .hamburger svg{width:22px;height:22px;color:#000}

    /* Drawer */
    .overlay{position:fixed;inset:0;background:rgba(17,20,24,.4);backdrop-filter:blur(2px);opacity:0;pointer-events:none;transition:.25s ease;z-index:59}
    .overlay.show{opacity:1;pointer-events:auto}
    .drawer{position:fixed;top:0;right:0;height:100%;width:min(88vw,360px);background:#fff;border-left:1px solid var(--line);box-shadow:0 10px 32px rgba(0,0,0,.16);transform:translateX(100%);transition:transform .28s ease;z-index:60;display:flex;flex-direction:column}
    .drawer.show{transform:none}
    .drawer-header{display:flex;align-items:center;justify-content:space-between;padding:16px 18px;border-bottom:1px solid var(--line)}
    .drawer-nav{padding:10px 14px;display:grid;gap:10px}
    .pill{display:inline-flex;align-items:center;justify-content:center;padding:11px 16px;border-radius:999px;border:1px solid #e8e8e8;background:#f7f7f7;font-weight:700}
    .pill.primary{background:#0d2a55;color:#fff;border-color:#0d2a55}

    /* Hero (same vibe as Classes) */
    .hero{background:var(--navy);color:#fff;text-align:center;padding:110px 16px 90px}
    .hero p{opacity:.95;margin:8px 0 0}
    .cta-row{display:flex;gap:14px;justify-content:center;margin-top:18px;flex-wrap:wrap}
    .btn{display:inline-flex;align-items:center;justify-content:center;padding:14px 22px;border-radius:12px;font-weight:800;border:1px solid transparent}
    .btn.coral{background:var(--coral);color:#fff;border-color:var(--coral)}
    .btn.light{background:#fff;color:#16223a;border-color:#eee}

    /* Cards section (matches Classes card look) */
    .cards{background:var(--bone);border-top:1px solid var(--line);padding:50px 0}
    .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px}
    .card{background:#fff;border:1px solid var(--line);border-radius:16px;padding:20px;display:flex;flex-direction:column;gap:10px;box-shadow:0 1px 0 rgba(0,0,0,.02)}
    .card p{color:#494c53;margin:0 0 8px}
    .card .cta{margin-top:auto}
    .btn.full{width:100%}

    /* CTA band */
    .cta-band{background:var(--navy);color:#fff;text-align:center;padding:68px 16px}
    .cta-band p{opacity:.9;margin:8px 0 0}

    /* Footer */
    footer{border-top:1px solid var(--line);padding:32px 0;color:#7a7e85;text-align:center;font-size:14px}
  </style>
</head>
<body>

  <!-- Topbar -->
  <div class="topbar" role="navigation" aria-label="Main">
    <div class="topbar-inner">
      <div class="brand">
        <img src="assets/IMG_2413.png" alt="NuAge logo" />
        <div class="brand-name"><span class="nu">Nu</span><span class="age">Age</span> Fitness Studios</div>
      </div>
      <button class="hamburger" id="navToggle" aria-label="Open menu" aria-expanded="false" aria-controls="navDrawer">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
      </button>
    </div>
  </div>

  <!-- Drawer + overlay -->
  <div id="navOverlay" class="overlay" hidden></div>
  <aside id="navDrawer" class="drawer" hidden aria-hidden="true">
    <div class="drawer-header">
      <div class="brand">
        <img src="assets/IMG_2413.png" alt="NuAge logo" />
        <div class="brand-name"><span class="nu">Nu</span><span class="age">Age</span> Fitness Studios</div>
      </div>
      <button id="navClose" aria-label="Close menu" style="background:transparent;border:none;font-size:28px;line-height:1;cursor:pointer;">×</button>
    </div>
    <nav class="drawer-nav">
      <a class="pill primary" href="location.php">Find a Location</a>
      <a class="pill" href="javascript:void(0)" onclick="openModal()">Member Login</a>
      <a href="classes.php" class="pill">Classes</a>
      <a href="team.php" class="pill">Meet the Team</a>
      <a href="pricing.php" class="pill">Pricing</a>
      <a href="gallery.php" class="pill">Gallery</a>
    </nav>
  </aside>

  <!-- Hero -->
  <section class="hero">
    <div class="container">
      <h1>Gallery</h1>
      <p>Browse highlights from our facilities, classes, and community.</p>
      <div class="cta-row">
        <a class="btn coral" href="#collections">Open Collections</a>
        <a class="btn light" href="index.php">Back Home</a>
      </div>
    </div>
  </section>

  <!-- Cards laid out like Classes (but for Gallery Collections) -->
  <section id="collections" class="cards">
    <div class="container">
      <div class="grid">
        <article class="card">
          <h3>Facilities</h3>
          <p>Strength zone, cardio deck, studio rooms, recovery areas, and more.</p>
          <div class="cta"><a class="btn coral full" href="javascript:void(0)" onclick="openCollection('facilities')">View Photos</a></div>
        </article>
        <article class="card">
          <h3>Classes</h3>
          <p>Bootcamp, mobility & flow, HIIT, and specialty sessions in action.</p>
          <div class="cta"><a class="btn coral full" href="javascript:void(0)" onclick="openCollection('classes')">View Photos</a></div>
        </article>
        <article class="card">
          <h3>Members</h3>
          <p>Community energy — PRs, group vibes, and authentic progress.</p>
          <div class="cta"><a class="btn coral full" href="javascript:void(0)" onclick="openCollection('members')">View Photos</a></div>
        </article>
        <article class="card">
          <h3>Transformations</h3>
          <p>Before/after moments and milestone highlights from NuAge athletes.</p>
          <div class="cta"><a class="btn coral full" href="javascript:void(0)" onclick="openCollection('transformations')">View Photos</a></div>
        </article>
        <article class="card">
          <h3>Videos</h3>
          <p>Short studio walkthroughs, class clips, and member spotlights.</p>
          <div class="cta"><a class="btn coral full" href="javascript:void(0)" onclick="openCollection('video')">Play Videos</a></div>
        </article>
      </div>
    </div>
  </section>

  <!-- CTA band -->
  <section class="cta-band">
    <div class="container">
      <h2>Train with Intention</h2>
      <p>Science-backed classes, motivating coaches, real results.</p>
    </div>
  </section>

  <footer>
    <div class="container">&copy; <?php echo date('Y'); ?> NuAge Fitness Studio. All rights reserved.</div>
  </footer>

<script>
// Drawer identical behavior
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

// Simple openModal stub (replace with real modal if present globally)
function openModal(){
  alert("Login modal goes here (Apple/Google options).");
}

// Gallery data
const items = [
  {src:'assets/gallery/facility-weights.jpg', type:'image', tag:'facilities', title:'Strength Zone'},
  {src:'assets/gallery/class-hiit.jpg', type:'image', tag:'classes', title:'Full Body Bootcamp'},
  {src:'assets/gallery/members-strong.jpg', type:'image', tag:'members', title:'Member Spotlight'},
  {src:'assets/gallery/transform-1.jpg', type:'image', tag:'transformations', title:'8-Week Progress'},
  {src:'assets/gallery/videos/nuage-tour.mp4', type:'video', tag:'video', title:'Studio Walkthrough'},
  {src:'assets/gallery/class-yoga.jpg', type:'image', tag:'classes', title:'Mobility & Flow'},
  {src:'assets/gallery/facility-cardio.jpg', type:'image', tag:'facilities', title:'Cardio Deck'},
  {src:'assets/gallery/members-class.jpg', type:'image', tag:'members', title:'Group Energy'},
  {src:'assets/gallery/transform-2.jpg', type:'image', tag:'transformations', title:'Glute Gains'}
];

// "Pricing-style" buttons open collection slideshows
function openCollection(tag){
  const filtered = items.filter(it=> tag==='video' ? it.type==='video' : (it.tag===tag && it.type!=='video'));
  if(filtered.length===0){ alert('No media in this collection yet.'); return; }
  let idx = 0;
  const overlay = document.createElement('div'); overlay.className='overlay show'; overlay.style.background='rgba(6,10,18,.92)'; document.body.appendChild(overlay);
  const box = document.createElement('div'); box.style.cssText='position:fixed;inset:0;display:grid;place-items:center;z-index:71;'; overlay.appendChild(box);
  function render(){
    box.innerHTML='';
    const it = filtered[idx];
    if(it.type==='video'){ const v=document.createElement('video'); v.src=it.src; v.controls=true; v.autoplay=true; v.style.maxWidth='92vw'; v.style.maxHeight='86vh'; v.style.borderRadius='16px'; box.appendChild(v); }
    else { const img=document.createElement('img'); img.src=it.src; img.alt=it.title||''; img.style.maxWidth='92vw'; img.style.maxHeight='86vh'; img.style.borderRadius='16px'; box.appendChild(img); }
    const title = document.createElement('div'); title.textContent = it.title||''; title.style.cssText='color:#fff;margin-top:12px;text-align:center;font-weight:700'; box.appendChild(title);
    const close=document.createElement('button'); close.textContent='×'; close.style.cssText='position:fixed;top:18px;right:22px;font-size:34px;background:transparent;border:none;color:#fff;cursor:pointer;z-index:72'; close.onclick=()=>{document.removeEventListener('keydown',onKey); overlay.remove();}; box.appendChild(close);
  }
  function onKey(e){
    if(e.key==='Escape'){document.removeEventListener('keydown',onKey); overlay.remove();}
    if(e.key==='ArrowRight'){ idx=(idx+1)%filtered.length; render(); }
    if(e.key==='ArrowLeft'){ idx=(idx-1+filtered.length)%filtered.length; render(); }
  }
  document.addEventListener('keydown', onKey);
  overlay.addEventListener('click', (e)=>{ if(e.target===overlay){document.removeEventListener('keydown',onKey); overlay.remove();} });
  render();
}
  </script>
</body>
</html>
