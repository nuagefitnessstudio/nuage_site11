<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gallery — NuAge Fitness Studios</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <style>
    :root{ --ink:#111418; --muted:#6a6d74; --line:#e9e6e1; --bone:#faf7f2; --navy:#002D72; --coral:#EB1F48; }
    *{box-sizing:border-box} html,body{height:100%}
    body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,Helvetica,sans-serif;background:#fff;color:var(--ink);line-height:1.6}
    h1,h2{font-family:'Playfair Display',serif;margin:0 0 .25em;line-height:1.15}
    h1{font-size:clamp(40px,6vw,68px);font-weight:700}
    .container{max-width:1200px;margin:0 auto;padding:0 24px}

    /* Floating rounded navbar (matches Classes page) */
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

    /* Hero */
    .hero{background:var(--navy);color:#fff;text-align:center;padding:110px 16px 90px}
    .hero p{opacity:.95;margin:8px 0 0}

    /* Gallery grid styled like Classes cards */
    .wrap{background:var(--bone);border-top:1px solid var(--line);padding:48px 0}
    .grid{display:grid;grid-template-columns:repeat(1,1fr);gap:16px}
    @media(min-width:700px){.grid{grid-template-columns:repeat(2,1fr)}}
    @media(min-width:1024px){.grid{grid-template-columns:repeat(3,1fr)}}
    @media(min-width:1280px){.grid{grid-template-columns:repeat(4,1fr)}}
    .tile{position:relative;border:1px solid var(--line);border-radius:16px;overflow:hidden;background:#fff}
    .ratio{width:100%;padding-top:72%;position:relative}
    .ratio>img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:transform .35s}
    .tile:hover .ratio>img{transform:scale(1.02)}
    .cap{position:absolute;left:10px;bottom:10px;background:rgba(17,20,24,.6);color:#fff;padding:6px 10px;border-radius:12px;font-weight:800;font-size:12px;letter-spacing:.02em}

    /* Lightbox */
    .lb{position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(6,10,18,.92);z-index:70}
    .lb.open{display:flex}
    .lb img{max-width:92vw;max-height:86vh;border-radius:16px;box-shadow:0 24px 80px rgba(0,0,0,.55)}
    .lb .x{position:fixed;top:18px;right:22px;font-size:34px;background:transparent;border:none;color:#fff;cursor:pointer}

    /* CTA band + footer */
    .cta-band{background:var(--navy);color:#fff;text-align:center;padding:68px 16px}
    .cta-band p{opacity:.9;margin:8px 0 0}
    footer{padding:30px 0;color:#7a7e85;text-align:center;font-size:14px}
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
      <a class="pill" href="classes.php">Classes</a>
      <a class="pill" href="team.php">Meet the Team</a>
      <a class="pill" href="pricing.php">Pricing</a>
      <a class="pill" href="gallery.php">Gallery</a>
    </nav>
  </aside>

  <!-- Hero -->
  <section class="hero">
    <div class="container">
      <h1>Gallery</h1>
      <p>Replace the placeholder image paths with your own photos in <code>assets/gallery/</code>.</p>
    </div>
  </section>

  <!-- Static Gallery Grid (placeholders you can replace) -->
  <section class="wrap">
    <div class="container">
      <div class="grid">
        <!-- Each tile: change the src to your real file -->
        <figure class="tile">
          <div class="ratio">
            <img src="assets/gallery/placeholder-01.jpg" alt="Main Floor" onclick="openLB(this.src, this.alt)" loading="lazy">
          </div>
          <figcaption class="cap">Main Floor</figcaption>
        </figure>
        <figure class="tile">
          <div class="ratio">
            <img src="assets/gallery/placeholder-02.jpg" alt="Strength Rig" onclick="openLB(this.src, this.alt)" loading="lazy">
          </div>
          <figcaption class="cap">Strength Rig</figcaption>
        </figure>
        <figure class="tile">
          <div class="ratio">
            <img src="assets/gallery/placeholder-03.jpg" alt="Cardio Lineup" onclick="openLB(this.src, this.alt)" loading="lazy">
          </div>
          <figcaption class="cap">Cardio Lineup</figcaption>
        </figure>
        <figure class="tile">
          <div class="ratio">
            <img src="assets/gallery/placeholder-04.jpg" alt="Blue Turf" onclick="openLB(this.src, this.alt)" loading="lazy">
          </div>
          <figcaption class="cap">Blue Turf</figcaption>
        </figure>
        <figure class="tile">
          <div class="ratio">
            <img src="assets/gallery/placeholder-05.jpg" alt="Dumbbells" onclick="openLB(this.src, this.alt)" loading="lazy">
          </div>
          <figcaption class="cap">Dumbbells</figcaption>
        </figure>
        <figure class="tile">
          <div class="ratio">
            <img src="assets/gallery/placeholder-06.jpg" alt="Rowers & Echo Bike" onclick="openLB(this.src, this.alt)" loading="lazy">
          </div>
          <figcaption class="cap">Rowers & Echo Bike</figcaption>
        </figure>
        <figure class="tile">
          <div class="ratio">
            <img src="assets/gallery/placeholder-07.jpg" alt="Mirror Wall" onclick="openLB(this.src, this.alt)" loading="lazy">
          </div>
          <figcaption class="cap">Mirror Wall</figcaption>
        </figure>
        <figure class="tile">
          <div class="ratio">
            <img src="assets/gallery/placeholder-08.jpg" alt="Torque Bays" onclick="openLB(this.src, this.alt)" loading="lazy">
          </div>
          <figcaption class="cap">Torque Bays</figcaption>
        </figure>
        <figure class="tile">
          <div class="ratio">
            <img src="assets/gallery/placeholder-09.jpg" alt="Functional Zone" onclick="openLB(this.src, this.alt)" loading="lazy">
          </div>
          <figcaption class="cap">Functional Zone</figcaption>
        </figure>
        <figure class="tile">
          <div class="ratio">
            <img src="assets/gallery/placeholder-10.jpg" alt="Sled & Ropes" onclick="openLB(this.src, this.alt)" loading="lazy">
          </div>
          <figcaption class="cap">Sled & Ropes</figcaption>
        </figure>
        <figure class="tile">
          <div class="ratio">
            <img src="assets/gallery/placeholder-11.jpg" alt="Weightlifting Area" onclick="openLB(this.src, this.alt)" loading="lazy">
          </div>
          <figcaption class="cap">Weightlifting Area</figcaption>
        </figure>
        <figure class="tile">
          <div class="ratio">
            <img src="assets/gallery/placeholder-12.jpg" alt="Member View" onclick="openLB(this.src, this.alt)" loading="lazy">
          </div>
          <figcaption class="cap">Member View</figcaption>
        </figure>
      </div>
    </div>
  </section>

  <!-- CTA band -->
  <section class="cta-band">
    <div class="container">
      <h2 style="font-family:'Playfair Display',serif;margin:0 0 .25em">Train with Intention</h2>
      <p>Science-backed classes, motivating coaches, real results.</p>
    </div>
  </section>

  <footer class="container">© <span id="y"></span> NuAge Fitness Studio. All rights reserved.</footer>

<script>
// Year
document.getElementById('y').textContent = new Date().getFullYear();

// Drawer behavior (same IDs as Classes page)
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

// Simple login stub
function openModal(){ alert('Login modal would open here.'); }

// Lightbox
const lb = document.createElement('div'); lb.className='lb'; lb.innerHTML = '<button class="x" aria-label="Close">×</button>'; document.body.appendChild(lb);
const lbImg = document.createElement('img'); lb.appendChild(lbImg);
lb.querySelector('.x').onclick = ()=> lb.classList.remove('open');
lb.addEventListener('click', (e)=>{ if(e.target === lb) lb.classList.remove('open'); });
document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') lb.classList.remove('open'); });

function openLB(src, title){
  lbImg.src = src; lbImg.alt = title || '';
  lb.classList.add('open');
}
</script>
</body>
</html>
