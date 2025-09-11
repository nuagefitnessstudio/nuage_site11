<!-- index_remote now uses local /assets/ files. Run ./download_assets.sh to fetch real photos. -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#0d2a55">
  <title>NuAge Fitness Studio — Live Stock Photos</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --ink:#111418; --muted:#6a6d74; --line:#e9e6e1; --bone:#faf7f2;
      --pill:#efebe6cc; --navy:#002D72; --coral:#EB1F48;
    }
    *{box-sizing:border-box} html,body{height:100%} body{margin:0;color:var(--ink);font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,Helvetica,sans-serif;line-height:1.6;background:#fff}
    h1,h2,h3{font-family:'Playfair Display',serif;letter-spacing:.02em;line-height:1.15;margin:0 0 .25em}
    h1{font-size:clamp(32px,5vw,54px);font-weight:600} h2{font-size:clamp(28px,3.5vw,40px);font-weight:600} h3{font-size:clamp(18px,2vw,22px);font-weight:600}
    a{color:inherit;text-decoration:none} .container{max-width:1200px;margin:0 auto;padding:0 24px}

    /* ===== Topbar (hamburger) ===== */
    .topbar{
      position:fixed;top:16px;left:50%;transform:translateX(-50%);
      display:flex;align-items:center;justify-content:center;gap:14px;
      width:min(92vw,980px);background:var(--pill);backdrop-filter:blur(8px);
      border:1px solid rgba(0,0,0,.08);border-radius:999px;
      padding:10px 16px;z-index:60
    }
    .pill-link{display:inline-flex;align-items:center;gap:10px;font-size:14px;letter-spacing:.04em;color:#2c2c2c;padding:8px 14px;border-radius:999px;transition:background .25s,transform .2s}
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
    .btn{display:inline-block;border:1px solid #ffffffcc;color:#fff;padding:12px 22px;border-radius:10px;letter-spacing:.08em;font-size:14px}

    .tiles{background:var(--bone);padding:56px 24px 72px}
    .tile-grid{display:grid;gap:24px;grid-template-columns:repeat(2,minmax(0,1fr))}
    .tile{position:relative;border-radius:18px;overflow:hidden;color:#fff;min-height:52svh;display:flex;align-items:flex-end;box-shadow:0 10px 28px rgba(0,0,0,.12)}
    .tile img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;filter:brightness(.8)}
    .tile .copy{position:relative;z-index:1;padding:24px}
    .tag{font-family:'Playfair Display',serif;font-size:clamp(28px,3.5vw,44px);font-weight:600;letter-spacing:.02em;margin-bottom:.4rem}

    .amen{background:var(--bone); padding:56px 24px 72px}
    .grid6{display:grid; grid-template-columns:repeat(6,1fr); gap:14px}
    .grid6 img{width:100%; height:160px; object-fit:cover; border-radius:12px}

    .legal{background:var(--bone);padding:48px 24px 24px;color:#51493f}
    .footer{background:#fff;border-top:1px solid var(--line);padding:32px 0 0}
    .links{display:grid;gap:24px;padding:24px;grid-template-columns:repeat(4,minmax(0,1fr))}
    .bottombar{padding:20px 24px 28px;display:flex;align-items:center;justify-content:space-between;gap:16px;color:#6a6d74;font-size:14px}
    .closing{background:linear-gradient(180deg,#a8b7cc 0%,#d8cfc7 100%);color:#fff;min-height:44svh;display:grid;place-items:center;text-align:center}
    .closing h2{color:#fff;letter-spacing:.22em;text-transform:uppercase;font-family:Inter,sans-serif;font-weight:600}
    @media (max-width:1024px){.split{grid-template-columns:1fr} .split .text{padding:52px 22px} .feature{grid-template-columns:1fr 140px}} 
    @media (max-width:720px){.tile{min-height:44svh} .tile-grid{grid-template-columns:1fr} .links{grid-template-columns:1fr 1fr} .bottombar{flex-direction:column;align-items:flex-start}}
    
    :root{
      --ink:#111418; --muted:#6c6f76; --line:#eae6e0; --bone:#fbf7f2;
      --pill:#f3efeacc; --navy:#0d2a55; --coral:#EB1F48;
      --shadow:0 8px 28px rgba(16,24,40,.12);
      --radius:18px;
    }
    body{letter-spacing:.01em}
    h1{font-size:clamp(36px,6vw,72px); font-weight:600}
    h2{font-size:clamp(28px,4vw,44px); font-weight:600}
    .topbar{width:min(94vw,1040px); background:var(--pill); box-shadow:var(--shadow)}
    .pill-link{font-weight:600}
    .brand img{height:26px}
    .brand-name{font-family:'Playfair Display',serif; font-weight:700; letter-spacing:.04em; font-size:18px}
    .hero{height:92svh; min-height:620px}
    .hero::after{background:linear-gradient(180deg,rgba(0,0,0,.15),rgba(0,0,0,.45) 55%,rgba(0,0,0,.35))}
    .hero .hero-center h1{font-size:clamp(40px,6.2vw,84px); text-shadow:0 12px 40px rgba(0,0,0,.45)}
    .split{background:var(--bone)}
    .split .text{padding:88px 40px}
    .eyebrow{color:#7f786e; letter-spacing:.22em}
    .divider{background:var(--line); margin:34px 0}
    .feature{grid-template-columns:1fr 180px}
    .feature img{width:180px; height:128px; border-radius:14px; box-shadow:var(--shadow)}
    .tile{border-radius:22px; box-shadow:var(--shadow)}
    .tile img{filter:brightness(.76) saturate(1.02); transform:scale(1.02); transition:transform .5s ease}
    .tile:hover img{transform:scale(1.06)}
    .btn{border-radius:12px; padding:14px 22px; font-weight:600; letter-spacing:.08em}
    .links a{opacity:.9}
    .bottombar{color:#737373}
    .grid6{gap:16px}
    .grid6 img{height:200px; border-radius:14px; box-shadow:0 8px 20px rgba(0,0,0,.08)}
    [data-reveal]{opacity:0; transform:translateY(14px); transition:all .7s ease}
    [data-reveal].show{opacity:1; transform:none}

    .hero .btn{box-shadow:0 8px 24px rgba(0,0,0,.25);}
    .hero .btn:hover{transform:translateY(-1px);}
    .hero-center>div{padding:0 12px;}
    .tag{ text-shadow: 0 6px 18px rgba(0,0,0,.35); }

    /* === NuAge: split section scroll fix (override) === */
    .split .text { align-items: flex-start; }
    @media (min-width:1025px){
      .split{ min-height:100vh; min-height:100svh; }
      .split .visual{ position: sticky; top: 0; height:100vh; height:100svh; }
      .split .visual img{ height:100%; object-fit:cover; }
      .split .text{
        max-height:100vh; max-height:100svh; overflow-y:auto; -webkit-overflow-scrolling:touch;
        padding-right:28px; padding-bottom:200px; scrollbar-gutter: stable both-edges; overscroll-behavior: contain;
      }
      .split .text::after{ content:none !important; }
      .split .text::-webkit-scrollbar{ width:10px }
      .split .text::-webkit-scrollbar-thumb{ background:#d9d3cc; border-radius:999px }
      .split .text::-webkit-scrollbar-track{ background:transparent }
    }

    /* === Cookie Consent (modal) === */
    .cookie-overlay{
      position:fixed; inset:0;
      background:rgba(17,20,24,.45); backdrop-filter:blur(2px);
      opacity:0; pointer-events:none; transition:opacity .25s ease; z-index:80;
    }
    .cookie-overlay.show{ opacity:1; pointer-events:auto; }

    .cookie-modal{
      position:fixed; bottom:24px; right:24px; width:min(560px,94vw);
      background:#f6f0e9;
      border:1px solid #cfc6bc; border-radius:12px;
      box-shadow:0 12px 40px rgba(0,0,0,.25);
      color:var(--ink); z-index:81; 
      opacity:0; transform:translateY(10px) scale(.98);
      transition:opacity .25s ease, transform .25s ease;
      pointer-events:none;
      font-size:14px; line-height:1.55;
    }
    .cookie-modal.show{ opacity:1; transform:none; pointer-events:auto; }

    .cc-inner{ padding:18px 18px 16px 18px; }
    .cc-title{ font-family:'Playfair Display',serif; font-size:18px; margin:0 0 10px; }
    .cc-body{ color:#3b3b3b; margin:0 0 12px; }
    .cc-body a{ text-decoration:underline; }

    .cc-close{
      position:absolute; top:8px; right:10px;
      border:0; background:transparent; font-size:22px; line-height:1;
      cursor:pointer; color:#444; padding:6px; border-radius:8px;
    }
    .cc-close:hover{ background:#00000010; }

    .cc-btn{ 
      display:block; width:100%; padding:12px 14px; margin-top:10px;
      border-radius:8px; border:1px solid #2a2a2a; font-weight:700; cursor:pointer;
    }
    .cc-btn.primary{ background:#2a2a2a; color:#fff; }
    .cc-btn.danger{ background:#2a2a2a; color:#fff; }
    .cc-btn.light{ background:#fff; color:#2a2a2a; }

    .cc-custom{ border-top:1px solid #ddd4ca; margin-top:12px; padding-top:12px; }
    .cc-row{ display:flex; gap:12px; align-items:center; justify-content:space-between; padding:8px 0; }
    .cc-toggle{ display:flex; align-items:center; gap:10px; }
    .cc-toggle input{ width:18px; height:18px; }
    .cc-note{ font-size:12px; color:#6a6d74; }

    @media (max-width:540px){
      .cookie-modal{ right:12px; left:12px; width:auto; bottom:12px; }
    }

    /* ====== Mobile-first responsive overrides ====== */

/* Base: fluid media and type */
img, video { max-width: 100%; height: auto; }
video { display:block; object-fit: cover; }

/* Use dynamic viewport on modern mobile for better 100vh handling */
:root { --vh: 1vh; }  /* fallback */
@supports (height: 100dvh) {
  .hero { min-height: 90dvh; }
}

/* Container padding tighter on small screens */
.container { padding-left: 16px; padding-right: 16px; }

/* Topbar: full-bleed, thumb-friendly */
.topbar{ width:calc(100% - 20px); left:50%; transform:translateX(-50%); padding:10px 12px; }
.hamburger{ width:44px; height:44px; }

/* Hero: reduce minimum height on narrow devices */
.hero { min-height: 70svh; }
@media (min-width: 768px){
  .hero { min-height: 84svh; }
}
.hero .btn { font-size: 14px; padding: 12px 18px; }

/* Split section: stack and remove sticky on mobile */
.split { grid-template-columns: 1fr; }
.split .visual { position: relative; height: 48svh; }
.split .visual img{ height: 100%; }
.split .text { padding: 28px 16px; max-height: none; overflow: visible; }
@media (min-width: 1025px){
  .split { grid-template-columns: 1.15fr 1fr; }
  .split .text { padding: 72px 32px; }
}

/* Feature rows: image under text on small screens */
.feature { grid-template-columns: 1fr; }
.feature img { width: 100%; height: 200px; }
@media (min-width: 640px){
  .feature { grid-template-columns: 1fr 160px; }
  .feature img { height: 140px; }
}
@media (min-width: 1024px){
  .feature { grid-template-columns: 1fr 180px; }
  .feature img { height: 128px; }
}

/* Tiles: single column on phones, two on tablets */
.tile-grid { grid-template-columns: 1fr; }
.tile { min-height: 42svh; }
@media (min-width: 720px){
  .tile-grid { grid-template-columns: repeat(2,minmax(0,1fr)); }
}

/* Amenities grid: 2 cols on phones, 3 on tablets, 6 on desktop */
.grid6 { grid-template-columns: repeat(2,1fr); }
.grid6 img { height: 160px; }
@media (min-width: 720px){
  .grid6 { grid-template-columns: repeat(3,1fr); }
  .grid6 img { height: 180px; }
}
@media (min-width: 1024px){
  .grid6 { grid-template-columns: repeat(6,1fr); }
  .grid6 img { height: 200px; }
}

/* Horizontal cards: make the track scrollable but comfy */
.section-cards .track{ display:flex; gap:16px; overflow-x:auto; -webkit-overflow-scrolling:touch; scroll-snap-type:x mandatory; padding-bottom:8px;}
.section-cards .card{ flex:0 0 80%; max-width:420px; scroll-snap-align:start; border-radius:14px; overflow:hidden; }

/* Footer links: 2 cols on phones */
.links { grid-template-columns: 1fr 1fr; gap:16px; }
@media (min-width: 900px){
  .links { grid-template-columns: repeat(4,minmax(0,1fr)); }
}

/* Cookie modal: fit phones nicely */
.cookie-modal{ right:12px; left:12px; bottom:12px; width:auto; }

/* Accessibility: larger tap targets on mobile */
a, button { -webkit-tap-highlight-color: transparent; }
.pill-link, .btn { padding: 12px 16px; }

/* Respect reduced motion */
@media (prefers-reduced-motion: reduce){
  * { animation: none !important; transition: none !important; }
}

  </style>
</head>
<body>
  <!-- Hamburger topbar -->
  <div class="topbar" role="navigation" aria-label="Main">
    <div class="brand" aria-label="NuAge">
      <img loading="lazy" referrerpolicy="no-referrer" src="assets/IMG_2413.png" alt="NuAge logo">
      <div class="brand-name">NuAge<span>Fitness</span><span>Studios</span></div>
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
        <img src="assets/IMG_2413.png" alt="NuAge logo">
        <div class="brand-name">NuAge<span>Fitness</span><span>Studios</span></div>
      </div>
      <button class="drawer-close" id="navClose" aria-label="Close menu">&times;</button>
    </div>
    <nav class="drawer-nav">
      <a href="#" class="pill-link primary"><span style="font-weight:700">Find a Location</span></a>
      <a href="#" class="pill-link" style="font-weight:700;color:var(--navy);border:1px solid var(--line);background:#fff">Member Login</a>
      <!-- More links if needed -->
    </nav>
  </aside>

  <header class="hero">
    <video id="heroVideo" autoplay muted playsinline loop poster="https://images.pexels.com/photos/4761669/pexels-photo-4761669.jpeg?auto=compress&cs=tinysrgb&w=1600&h=1000&dpr=1">
      <source src="assets/benchpress_hero.mp4" type="video/mp4">
      <source src="assets/benchpress_hero.webm" type="video/webm">
      <source src="assets/workout_hero.mp4" type="video/mp4">
      <source src="assets/hero.mp4" type="video/mp4">
    </video>
    <div class="hero-center">
      <div>
        <h1>This is the feeling of NuAge</h1>
        <p style="max-width:720px;margin:12px auto 20px;color:#f0f0f0;opacity:.95">Premium training, recovery, and community — all in one elegant club experience.</p>
        <a href="#" class="btn" style="background:#ffffffcc;color:#0d2a55;border-color:#ffffff;backdrop-filter:blur(4px)">Find a Location</a>
      </div>
    </div>
  </header>

  <section class="split" data-reveal>
    <div class="visual"><img loading="lazy" referrerpolicy="no-referrer" class="remote" src="assets/workout-01 2.17.36 AM.jpg" onerror="this.onerror=null;this.src='/assets/workout-01 2.17.36 AM.jpg';" data-remote="workout-01 2.17.36 AM.jpg" data-fallback="assets/workout-01.jpg" alt="Athletic"></div>
    <div class="text">
      <div class="stack container">
        <div class="eyebrow">Love Your Life</div>
        <h2 style="color:var(--navy)">Athletic Country Clubs</h2>
        <p>From the warm welcome of the studio to the cool shade of the cabana, every inch of NuAge is designed with your well-being in mind. It’s so much more than a gym.</p>
        <div class="divider"></div>
        <div class="feature">
          <div><div class="eyebrow">Results-Driven Programs</div><p>Programs. Lessons. Sessions. Workouts. So many wonderful ways to spend time and reach goals.</p></div>
          <img loading="lazy" referrerpolicy="no-referrer" class="remote" src="assets/yoga_pic.jpeg" onerror="this.onerror=null;this.src='assets/feature-1.jpg';" data-remote="feature-1.jpg" data-fallback="assets/workout-02.jpg" alt="Strength">
        </div>
        <div class="divider"></div>
        <div class="feature">
          <div><div class="eyebrow">Passionate People</div><p>Everyone here shares the same mission: helping you live your healthiest, happiest life.</p></div>
          <img loading="lazy" referrerpolicy="no-referrer" class="remote" src="https://images.pexels.com/photos/33239683/pexels-photo-33239683.jpeg?auto=compress&cs=tinysrgb&w=1600&h=1067&dpr=1" onerror="this.onerror=null;this.src='assets/feature-2.jpg';" data-remote="feature-2.jpg" data-fallback="assets/workout-03.jpg" alt="People">
        </div>
        <div class="divider"></div>
        <div class="feature">
          <div><div class="eyebrow">Recovery & Amenities</div><p>Recover harder than you train — spa, sauna, cold plunge, and café to keep you thriving.</p></div>
          <img loading="lazy" referrerpolicy="no-referrer" class="remote" src="assets/pickleball-6940609_960_720.jpeg" onerror="this.onerror=null;this.src='assets/spa.jpg';" data-remote="spa.jpg" data-fallback="assets/pickleball-6940609_960_720.jpeg" alt="Spa">
        </div>
        <div class="divider"></div>
        <a class="pill-link" href="#" style="background:#fff;border:1px solid var(--line);width:max-content;padding:10px 18px;color:var(--navy)">Find a Location</a>
      </div>
    </div>
  </section>

  <section class="bleed" data-reveal>
    <img loading="lazy" referrerpolicy="no-referrer" class="remote" src="assets/nuage_site11/assets/5D310F03-8220-4DB9-9DD7-2CAAFD7A09B5_1_105_c.jpeg" onerror="this.onerror=null;this.src='assets/work.jpg';" data-remote="work.jpg" data-fallback="assets/nuage_site11/assets/5D310F03-8220-4DB9-9DD7-2CAAFD7A09B5_1_105_c.jpeg" alt="WORK">
    <div class="inner">
      <h1>TRAIN</h1>
      <p style="max-width:620px;margin:12px auto 26px;opacity:.95">Purpose-built studios, elite equipment, and expert coaching to move you forward.</p>
      <a class="btn" href="#" style="border-color:#fff">Explore</a>
    </div>
  </section>

  <section class="tiles" data-reveal>
    <div class="container" style="margin-bottom:18px">
      <div class="eyebrow">Experience</div>
      <h2 style="margin-bottom:6px;color:var(--navy)">Explore More</h2>
    </div>
    <div class="container tile-grid">
      <article class="tile">
        <img loading="lazy" referrerpolicy="no-referrer" class="remote" src="https://images.pexels.com/photos/4162543/pexels-photo-4162543.jpeg?auto=compress&cs=tinysrgb&w=1600&h=1067&dpr=1" onerror="this.onerror=null;this.src='assets/digital.jpg';" data-remote="digital.jpg" data-fallback="assets/workout-06.jpg" alt="Digital">
        <div class="copy" style="background:linear-gradient(180deg,transparent 20%, rgba(0,0,0,.55) 100%);"><div class="tag">DIGITAL</div><p>Around-the-clock access to a healthy way of life.</p><a href="#" class="btn">Learn More</a></div>
      </article>
      <article class="tile">
        <img loading="lazy" referrerpolicy="no-referrer" class="remote" src="https://images.pexels.com/photos/2611029/pexels-photo-2611029.jpeg?auto=compress&cs=tinysrgb&w=1600&h=1067&dpr=1" onerror="this.onerror=null;this.src='assets/living.jpg';" data-remote="living.jpg" data-fallback="assets/workout-07.jpg" alt="Living">
        <div class="copy" style="background:linear-gradient(180deg,transparent 20%, rgba(0,0,0,.55) 100%);"><div class="tag">LIVING</div><p>Luxury living that minimizes environmental impact and maximizes well-being.</p><a href="#" class="btn">Learn More</a></div>
      </article>
    </div>
  </section>

  <section class="amen" data-reveal>
    <div class="container"><div class="eyebrow">Amenities</div><h2 style="color:var(--navy); margin-bottom:16px;">Everything You Need</h2></div>
    <div class="container grid6">
      <img loading="lazy" referrerpolicy="no-referrer" class="remote" src="https://images.pexels.com/photos/19980238/pexels-photo-19980238.jpeg?auto=compress&cs=tinysrgb&w=1600&h=1067&dpr=1" onerror="this.onerror=null;this.src='assets/spa.jpg';" data-remote="spa.jpg" data-fallback="assets/workout-08.jpg" alt="Spa">
      <img loading="lazy" referrerpolicy="no-referrer" class="remote" src="assets/box-1514845_960_720.jpeg" onerror="this.onerror=null;this.src='assets/box-1514845_960_720.jpeg';" data-remote="cafe.jpg" data-fallback="assets/box-1514845_960_720.jpeg" alt="Café">
      <img loading="lazy" referrerpolicy="no-referrer" class="remote" src="https://images.pexels.com/photos/2611029/pexels-photo-2611029.jpeg?auto=compress&cs=tinysrgb&w=1600&h=1067&dpr=1" onerror="this.onerror=null;this.src='assets/living.jpg';" data-remote="living.jpg" data-fallback="assets/workout-10.jpg" alt="Pool">
      <img loading="lazy" referrerpolicy="no-referrer" class="remote" src="https://images.unsplash.com/photo-1660779411729-80c07aca6df2?auto=format&fit=crop&w=1600&q=80" onerror="this.onerror=null;this.src='assets/courts.jpg';" data-remote="courts.jpg" data-fallback="assets/athletic-left.jpg" alt="Courts">
      <img loading="lazy" referrerpolicy="no-referrer" class="remote" src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=1600&q=80" onerror="this.onerror=null;this.src='assets/runtrack.jpg';" data-remote="runtrack.jpg" data-fallback="assets/feature-1.jpg" alt="Track">
      <img loading="lazy" referrerpolicy="no-referrer" class="remote" src="https://images.pexels.com/photos/8613362/pexels-photo-8613362.jpeg?auto=compress&cs=tinysrgb&w=1600&h=1067&dpr=1" onerror="this.onerror=null;this.src='assets/kids.jpg';" data-remote="kids.jpg" data-fallback="assets/feature-2.jpg" alt="Kids">
    </div>
  </section>

  <section style="background:var(--bone); padding:56px 0 40px;" data-reveal>
    <div class="container">
      <div class="eyebrow">Featured Workouts</div>
      <h2 style="color:var(--navy); margin-bottom:16px;">What’s Inside NuAge</h2>
    </div>
    <div class="container" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
      <div style="display:flex; gap:16px; min-width:960px; padding-bottom:8px;">
        <a href="#" style="flex:0 0 320px; display:block; border-radius:14px; overflow:hidden;"><img loading="lazy" referrerpolicy="no-referrer" class="remote" src="https://images.unsplash.com/photo-1599050765360-4d5e4e9a9163?auto=format&fit=crop&w=1200&q=80" onerror="this.onerror=null;this.src='assets/fw-strength.jpg';" data-remote="fw-strength.jpg" data-fallback="assets/spa.jpg" style="width:100%;height:220px;object-fit:cover;display:block"><div style="padding:10px 12px;font-weight:600;color:#222;">Strength Training</div></a>
        <a href="#" style="flex:0 0 320px; display:block; border-radius:14px; overflow:hidden;"><img loading="lazy" referrerpolicy="no-referrer" class="remote" src="https://images.pexels.com/photos/29720736/pexels-photo-29720736.jpeg?auto=compress&cs=tinysrgb&w=1200&h=800&dpr=1" onerror="this.onerror=null;this.src='assets/fw-yoga.jpg';" data-remote="fw-yoga.jpg" data-fallback="assets/work.jpg" style="width:100%;height:220px;object-fit:cover;display:block"><div style="padding:10px 12px;font-weight:600;color:#222;">Yoga Flow</div></a>
        <a href="#" style="flex:0 0 320px; display:block; border-radius:14px; overflow:hidden;"><img loading="lazy" referrerpolicy="no-referrer" class="remote" src="https://images.pexels.com/photos/4498151/pexels-photo-4498151.jpeg?auto=compress&cs=tinysrgb&w=1200&h=800&dpr=1" onerror="this.onerror=null;this.src='assets/fw-hiit.jpg';" data-remote="fw-hiit.jpg" data-fallback="assets/digital.jpg" style="width:100%;height:220px;object-fit:cover;display:block"><div style="padding:10px 12px;font-weight:600;color:#222;">HIIT Session</div></a>
        <a href="#" style="flex:0 0 320px; display:block; border-radius:14px; overflow:hidden;"><img loading="lazy" referrerpolicy="no-referrer" class="remote" src="https://images.unsplash.com/photo-1660779411729-80c07aca6df2?auto=format&fit=crop&w=1200&q=80" onerror="this.onerror=null;this.src='assets/fw-pickleball.jpg';" data-remote="fw-pickleball.jpg" data-fallback="assets/living.jpg" style="width:100%;height:220px;object-fit:cover;display:block"><div style="padding:10px 12px;font-weight:600;color:#222;">Pickleball</div></a>
      </div>
    </div>
  </section>

  <section class="legal"><div class="container"><p style="max-width:940px">
    Membership levels, types, amenities, services, programs and their associated dues, pricing, fees and/or charges may vary by location and are subject to change. Access to certain services may be limited by terms and/or supplemental fees. Always consult your physician before beginning any new exercise program.
  </p></div></section>

  <footer class="footer">
    <div class="links container">
      <div><h4>Experience</h4><a href="#">Athletic Country Clubs</a><a href="#">Work</a><a href="#">Living</a><a href="#">Stay</a><a href="#">Digital</a></div>
      <div><h4>About</h4><a href="#">Newsroom</a><a href="#">Investor Relations</a><a href="#">Corporate Responsibility</a><a href="#">Culture of Inclusion</a><a href="#">Foundation</a></div>
      <div><h4>Concierge</h4><a href="#">Membership</a><a href="#">Help &amp; FAQs</a></div>
      <div><h4>Partnership</h4><a href="#">Corporate Wellness</a><a href="#">Marketing Partnerships</a><a href="#">Construction</a></div>
    </div>
    <div class="bottombar container">
      <div style="display:flex;gap:14px;flex-wrap:wrap">
        <a href="#">Privacy</a><a href="#">Terms</a><a href="#">Guest &amp; Club Policies</a><a href="#">Accessibility</a><a href="#">Sitemap</a>
      </div>
      <div>&copy; <span id="year"></span> NuAge Fitness Studio. All Rights Reserved.</div>
    </div>
  </footer>

  <section class="closing"><div class="container"><h2>Healthy Way of Life</h2></div></section>

  <!-- Cookie Consent -->
  <div id="ccOverlay" class="cookie-overlay" hidden></div>
  <div id="ccModal" class="cookie-modal" role="dialog" aria-modal="true" aria-labelledby="ccTitle" hidden>
    <button id="ccClose" class="cc-close" aria-label="Close">&times;</button>
    <div class="cc-inner">
      <h3 id="ccTitle" class="cc-title">This website uses cookies</h3>
      <p class="cc-body">
        We use cookies and similar technologies to enhance your experience, measure performance,
        and analyze traffic. We also share information about your use of our site with our
        analytics and advertising partners. You can manage your choices below. See our
        <a href="#">Privacy Policy</a> for more.
      </p>
      <button id="ccAcceptAll" class="cc-btn primary">Accept All</button>
      <button id="ccRejectAll" class="cc-btn danger">Reject All</button>
      <button id="ccCustomizeBtn" class="cc-btn light" aria-expanded="false" aria-controls="ccCustom">Customize</button>
      <div id="ccCustom" class="cc-custom" hidden>
        <div class="cc-row">
          <div class="cc-toggle"><input type="checkbox" checked disabled> <strong>Necessary</strong></div>
          <div class="cc-note">Always active</div>
        </div>
        <div class="cc-row">
          <label class="cc-toggle" for="ccAnalytics"><input id="ccAnalytics" type="checkbox" checked> <strong>Analytics</strong></label>
          <div class="cc-note">Helps us improve the site</div>
        </div>
        <div class="cc-row">
          <label class="cc-toggle" for="ccMarketing"><input id="ccMarketing" type="checkbox"> <strong>Marketing</strong></label>
          <div class="cc-note">Personalized offers</div>
        </div>
        <button id="ccSave" class="cc-btn primary" style="margin-top:6px;">Save Preferences</button>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('year').textContent = new Date().getFullYear();
    // Optional: click to pause/play hero video
    (function(){
      const v = document.getElementById('heroVideo');
      if(v){ v.addEventListener('click', ()=>{ v.paused ? v.play() : v.pause(); }); }
    })();
  </script>

  <script>
  // Ensure remote images don't break presentation if blocked by network/privacy tools
  (function(){
    function swapToFallback(img){
      if(!img.dataset.fallback) return;
      img.src = img.dataset.fallback;
      img.removeAttribute('srcset');
      img.classList.remove('remote');
    }
    document.querySelectorAll('img.remote').forEach(function(img){
      img.addEventListener('error', function(){ swapToFallback(img); }, {once:true});
      const t = setTimeout(function(){
        if(!img.complete || img.naturalWidth === 0){ swapToFallback(img); }
      }, 4000);
      img.addEventListener('load', function(){ clearTimeout(t); }, {once:true});
    });
  })();
  </script>

  <script>
  (function(){
    const els = document.querySelectorAll('[data-reveal]');
    const io = new IntersectionObserver((entries)=>{
      entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('show'); io.unobserve(e.target); } });
    }, {threshold:0.12});
    els.forEach(el=>io.observe(el));
  })();
  </script>

  <!-- Drawer script: open/close + accessibility -->
  <script>
  (function(){
    const toggle = document.getElementById('navToggle');
    const drawer = document.getElementById('navDrawer');
    const overlay = document.getElementById('navOverlay');
    const closeBtn = document.getElementById('navClose');

    function openDrawer(){
      drawer.hidden = false; overlay.hidden = false;
      requestAnimationFrame(()=>{
        drawer.classList.add('show'); overlay.classList.add('show');
        toggle.setAttribute('aria-expanded','true');
        drawer.setAttribute('aria-hidden','false');
      });
      const first = drawer.querySelector('a,button'); if(first) first.focus();
      document.documentElement.style.overflow = 'hidden';
    }
    function closeDrawer(){
      drawer.classList.remove('show'); overlay.classList.remove('show');
      toggle.setAttribute('aria-expanded','false');
      drawer.setAttribute('aria-hidden','true');
      setTimeout(()=>{ drawer.hidden = true; overlay.hidden = true; document.documentElement.style.overflow = ''; }, 280);
      toggle.focus();
    }
    toggle.addEventListener('click', openDrawer);
    closeBtn.addEventListener('click', closeDrawer);
    overlay.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape' && !drawer.hidden) closeDrawer(); });
  })();
  </script>

  <!-- Cookie Consent logic -->
  <script>
  (function(){
    const KEY = 'nuage.cookies.v1';
    const $ = (id)=>document.getElementById(id);

    const modal   = $('ccModal');
    const overlay = $('ccOverlay');
    const btnX    = $('ccClose');
    const accept  = $('ccAcceptAll');
    const reject  = $('ccRejectAll');
    const custom  = $('ccCustomizeBtn');
    const panel   = $('ccCustom');
    const save    = $('ccSave');
    const a       = $('ccAnalytics');
    const m       = $('ccMarketing');
    const manageLink = document.getElementById('manageCookies'); // optional external link

    let prevFocus = null;

    function open(){
      prevFocus = document.activeElement;
      modal.hidden = false; overlay.hidden = false;
      requestAnimationFrame(()=>{
        modal.classList.add('show'); overlay.classList.add('show');
      });
      document.documentElement.style.overflow = 'hidden';
      modal.querySelector('button, a, input')?.focus();
    }
    function close(){
      modal.classList.remove('show'); overlay.classList.remove('show');
      setTimeout(()=>{ modal.hidden = true; overlay.hidden = true; }, 250);
      document.documentElement.style.overflow = '';
      prevFocus?.focus();
    }

    function saveConsent(val){
      localStorage.setItem(KEY, JSON.stringify({ ...val, ts: Date.now() }));
      close();
    }

    accept.addEventListener('click', ()=>saveConsent({necessary:true, analytics:true, marketing:true}));
    reject.addEventListener('click', ()=>saveConsent({necessary:true, analytics:false, marketing:false}));
    save.addEventListener('click', ()=>saveConsent({necessary:true, analytics:a.checked, marketing:m.checked}));

    custom.addEventListener('click', ()=>{
      const show = panel.hasAttribute('hidden');
      if(show){ panel.removeAttribute('hidden'); } else { panel.setAttribute('hidden',''); }
      custom.setAttribute('aria-expanded', String(show));
    });

    btnX.addEventListener('click', close);
    overlay.addEventListener('click', close);
    document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape' && !modal.hidden) close(); });

    if(manageLink){ manageLink.addEventListener('click', (e)=>{ e.preventDefault(); open(); }); }

    const existing = localStorage.getItem(KEY);
    if(!existing){
      setTimeout(open, 600);
    }

    window.getNuAgeConsent = function(){
      try{ return JSON.parse(localStorage.getItem(KEY) || '{}'); }catch{return {}}
    };
  })();
  </script>
</body>
</html>
