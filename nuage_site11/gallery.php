<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gallery — NuAge Fitness Studios</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <style>
    :root{ --ink:#111418; --muted:#6a6d74; --line:#e9e6e1; --bone:#faf7f2; --navy:#002D72; --coral:#EB1F48; --pill:#efebe6cc; }
    *{box-sizing:border-box} html,body{height:100%}
    body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,Helvetica,sans-serif;background:#fff;color:var(--ink);line-height:1.6}
    h1,h2,h3{font-family:'Playfair Display',serif;margin:0 0 .25em;line-height:1.15;letter-spacing:.02em}
    h1{font-size:clamp(40px,6vw,68px);font-weight:700}
    h2{font-size:clamp(22px,3.2vw,28px);font-weight:700;color:var(--navy)}
    .container{max-width:1200px;margin:0 auto;padding:0 24px}
    a{color:inherit;text-decoration:none}

    /* ===== Floating pill topbar (exact rhythm) ===== */
    .topbar{position:fixed;top:16px;left:50%;transform:translateX(-50%);width:min(92vw,980px);z-index:60}
    .topbar-inner{position:relative;height:56px;display:flex;align-items:center;justify-content:center;background:var(--pill);backdrop-filter:blur(8px);border:1px solid rgba(0,0,0,.08);border-radius:999px;box-shadow:0 10px 26px rgba(0,0,0,.08);padding:10px 16px}
    .brand{display:flex;align-items:center;gap:10px}
    .brand img{height:24px}
    .brand-name{font-family:'Playfair Display',serif;font-weight:700;letter-spacing:.08em;color:var(--navy)}
    .brand-name .age{color:var(--coral)}
    .hamburger{position:absolute;right:8px;top:50%;transform:translateY(-50%);display:inline-flex;align-items:center;justify-content:center;width:42px;height:42px;border-radius:999px;border:1px solid rgba(0,0,0,.08);background:#fff9;backdrop-filter:blur(6px);cursor:pointer}
    .hamburger svg{width:22px;height:22px;color:#000}

    /* Drawer */
    .cta-band{background:var(--navy);color:#fff;text-align:center;padding:68px 16px}
    .cta-band p{opacity:.9;margin:8px 0 0}
    footer{padding:30px 0;color:#7a7e85;text-align:center;font-size:14px}
  
/* ==== Drawer styles copied from index.php ==== */

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

    /* ===== Rest of original styles ===== */
    .hero{position:relative;height:92svh;min-height:520px;overflow:hidden;color:#fff}
    .hero img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
    .hero::after{content:'';position:absolute;inset:0;background:linear-gradient(to bottom, rgba(0,0,0,.18), rgba(0,0,0,.35) 55%, rgba(0,0,0,.25));pointer-events:none}
    .hero-center{position:absolute;inset:0;display:grid;place-items:center;text-align:center;z-index:1;padding:0 16px}
    .hero-center h1{text-shadow:0 2px 16px rgba(0,0,0,.35);color:#fff}

    .split{display:grid;grid-template-columns:1.15fr 1fr;min-height:90svh;background:var(--bone)}
    .split .visual{position:relative;overflow:hidden}
    .split .visual img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transform:scale(1.03)}
    .split .text{padding:72px 32px;display:flex;align-items:center}
    .stack{width:100%}
    .eyebrow{text-transform:uppercase;letter-spacing:.18em;font-weight:700;color:#7c756b;font-size:12px;margin-bottom:20px}
    .divider{height:1px;background:var(--line);margin:28px 0}
    .feature{display:grid;grid-template-columns:1fr 160px;gap:24px;align-items:center;padding:14px 0}
    .feature img{width:160px;height:112px;object-fit:cover;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,.08)}

    .bleed{position:relative;min-height:60svh;display:grid;place-items:center;text-align:center;color:#fff;overflow:hidden}
    .bleed img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;filter:brightness(.75)}
    .bleed .inner{position:relative;z-index:1;padding:48px 16px}
    .btn{display:inline-block;border:1px solid #fffff
</style>
</head>
<body>

  <!-- Topbar -->
  <div class="topbar" role="navigation" aria-label="Main">
    <div class="topbar-inner">
      <div class="brand">
        <img src="assets/IMG_2413.png" alt="NuAge logo" />
        <div class="brand-name"><span>Nu</span><span class="age">Age</span> Fitness Studios</div>
      </div>
      <button class="hamburger" id="navToggle" aria-label="Open menu" aria-expanded="false" aria-controls="navDrawer">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
      </button>
    </div>
  </div>

  <!-- Drawer + overlay -->
  <div id="navOverlay" hidden></div>
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
    <a href="javascript:void(0)" onclick="openLogin()" class="pill-link">Member Login</a>
    <a href="classes.php">Classes</a>
    <a href="team.php">Meet the Team</a>
    <a href="pricing.php">Pricing</a>
  </nav>
</aside>

  <!-- HERO -->
  <section class="hero">
    <div class="container">
      <h1>Gallery</h1>
      <p>Facilities • Classes • Community • Transformations</p>
      <div class="cta-row">
        <a class="btn coral" href="#photos">View Photos</a>
        <a class="btn light" href="index.php">Back Home</a>
      </div>
    </div>
  </section>

  <!-- CARDS: image-first tiles styled like Classes cards -->
  <section id="photos" class="cards">
    <div class="container">
      <div class="grid">
        <!-- Replace src paths with your real files -->
        <article class="card">
          <div class="img-wrap"><img src="assets/gallery/placeholder-01.jpg" alt="Main Floor"></div>
          <div class="content">
            <h3>Main Floor</h3>
            <p>Full view of the training floor and multi-bay rig.</p>
          </div>
          <div class="cta"><a class="btn coral" href="assets/gallery/placeholder-01.jpg" target="_blank" rel="noopener">Open Photo</a></div>
        </article>

        <article class="card">
          <div class="img-wrap"><img src="assets/gallery/placeholder-02.jpg" alt="Strength Rig"></div>
          <div class="content">
            <h3>Strength Rig</h3>
            <p>Racks, pull-up bars, and accessories ready for work.</p>
          </div>
          <div class="cta"><a class="btn coral" href="assets/gallery/placeholder-02.jpg" target="_blank" rel="noopener">Open Photo</a></div>
        </article>

        <article class="card">
          <div class="img-wrap"><img src="assets/gallery/placeholder-03.jpg" alt="Cardio Lineup"></div>
          <div class="content">
            <h3>Cardio Lineup</h3>
            <p>Treadmills, rowers, and bikes in our cardio zone.</p>
          </div>
          <div class="cta"><a class="btn coral" href="assets/gallery/placeholder-03.jpg" target="_blank" rel="noopener">Open Photo</a></div>
        </article>

        <article class="card">
          <div class="img-wrap"><img src="assets/gallery/placeholder-04.jpg" alt="Blue Turf"></div>
          <div class="content">
            <h3>Blue Turf</h3>
            <p>Sled pushes, battle ropes, and conditioning work.</p>
          </div>
          <div class="cta"><a class="btn coral" href="assets/gallery/placeholder-04.jpg" target="_blank" rel="noopener">Open Photo</a></div>
        </article>

        <article class="card">
          <div class="img-wrap"><img src="assets/gallery/placeholder-05.jpg" alt="Dumbbells"></div>
          <div class="content">
            <h3>Dumbbells</h3>
            <p>Full dumbbell range with benches and mirrors.</p>
          </div>
          <div class="cta"><a class="btn coral" href="assets/gallery/placeholder-05.jpg" target="_blank" rel="noopener">Open Photo</a></div>
        </article>

        <article class="card">
          <div class="img-wrap"><img src="assets/gallery/placeholder-06.jpg" alt="Rowers & Echo Bike"></div>
          <div class="content">
            <h3>Rowers & Echo Bike</h3>
            <p>Rogue Echos and Concept2 rowers lined up.</p>
          </div>
          <div class="cta"><a class="btn coral" href="assets/gallery/placeholder-06.jpg" target="_blank" rel="noopener">Open Photo</a></div>
        </article>

        <article class="card">
          <div class="img-wrap"><img src="assets/gallery/placeholder-07.jpg" alt="Mirror Wall"></div>
          <div class="content">
            <h3>Mirror Wall</h3>
            <p>Perfect for form-checks and coaching cues.</p>
          </div>
          <div class="cta"><a class="btn coral" href="assets/gallery/placeholder-07.jpg" target="_blank" rel="noopener">Open Photo</a></div>
        </article>

        <article class="card">
          <div class="img-wrap"><img src="assets/gallery/placeholder-08.jpg" alt="Torque Bays"></div>
          <div class="content">
            <h3>Torque Bays</h3>
            <p>Suspension trainers, kettlebells, and accessories.</p>
          </div>
          <div class="cta"><a class="btn coral" href="assets/gallery/placeholder-08.jpg" target="_blank" rel="noopener">Open Photo</a></div>
        </article>
      </div>
    </div>
  </section>

  <!-- CTA band (same as Classes) -->
  <section class="cta-band">
    <div class="container">
      <h2>Train with Intention</h2>
      <p>Science-backed classes, motivating coaches, real results.</p>
    </div>
  </section>

  <footer class="container">© <span id="y"></span> NuAge Fitness Studio. All rights reserved.</footer>

<script>
// Year
document.getElementById('y').textContent = new Date().getFullYear();

// Drawer controls (IDs match Classes)
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

// Optional modal stub
function openModal(){ alert('Login modal would open here.'); }
</script>
</body>
</html>
