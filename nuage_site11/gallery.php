<?php
// NuAge Fitness — Gallery (auto-load all images in /assets/gallery)
$galleryDir = __DIR__ . '/assets/gallery';
$webPrefix  = 'assets/gallery'; // web path used in <img src>

$exts = ['jpg','jpeg','png','webp','JPG','JPEG','PNG','WEBP'];
$files = [];
if (is_dir($galleryDir)) {
    foreach (scandir($galleryDir) as $f) {
        $path = $galleryDir . DIRECTORY_SEPARATOR . $f;
        if (is_file($path)) {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if (in_array($ext, $exts, true)) {
                $files[] = $f;
            }
        }
    }
    // natural sort so "1,2,10" is correct
    natsort($files);
    $files = array_values($files);
}

// Optional: seed titles map (filename => caption)
$titles = [
    '81676E0C-A9E9-44E6-9CC8-2A6F1B81D026.jpeg' => 'Strength Zone (wide)',
    '3A0BDC6A-0AF1-495E-A088-BA7A7A19C72D.jpeg' => 'Strength Zone (center)',
    '008F618D-22D1-4A3D-9E7A-2868F3FAD12D.jpeg' => 'Rig & Turf',
    '1148A97C-8A51-45BA-830F-7928769E2747.jpeg' => 'Torque Bays',
    '63C7A6E9-51C7-4A6E-BCFF-C01B7E0D19ED.jpeg' => 'Blue Turf & Sled',
    'A3EB1C79-E6FD-46E7-B149-E7C476E7C7F5.jpeg' => 'Cardio Corner',
    'E82960DE-9E08-4134-BF21-2960D7651113.jpeg' => 'Dumbbells + Mirror',
    'E61F46AB-E8F1-47B9-9733-199F9ACA8CE2.jpeg' => 'Rowers + Echo Bike',
    'AD8C1A0B-13B3-46CD-9FA7-920320D09EDF.jpeg' => 'Cardio Lineup',
    'E98AC29D-82B2-4D0A-977E-1D00356626C9.jpeg' => 'Strength Rigs',
    'A8C5943E-02D5-42BB-9DF9-577B8A56FB34.jpeg' => 'Mirror Wall',
];
?>
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

    /* Floating rounded navbar like Classes */
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

    /* Navy hero */
    .hero{background:var(--navy);color:#fff;text-align:center;padding:110px 16px 90px}
    .hero p{opacity:.95;margin:8px 0 0}

    /* Gallery grid (classes vibe: rounded, light border) */
    .wrap{background:var(--bone);border-top:1px solid var(--line);padding:48px 0}
    .grid{display:grid;grid-template-columns:repeat(1,1fr);gap:16px}
    @media(min-width:560px){.grid{grid-template-columns:repeat(2,1fr)}}
    @media(min-width:900px){.grid{grid-template-columns:repeat(3,1fr)}}
    @media(min-width:1200px){.grid{grid-template-columns:repeat(4,1fr)}}
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
      <p>All photos load automatically from <code>/assets/gallery</code>. Just drop more in.</p>
    </div>
  </section>

  <!-- Gallery Grid -->
  <section class="wrap">
    <div class="container">
      <div class="grid">
        <?php foreach ($files as $f): 
          $src = $webPrefix . '/' . rawurlencode($f);
          $title = $titles[$f] ?? pathinfo($f, PATHINFO_FILENAME);
        ?>
        <figure class="tile">
          <div class="ratio">
            <img src="<?php echo htmlspecialchars($src, ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($title, ENT_QUOTES); ?>" loading="lazy" onclick="openLB('<?php echo htmlspecialchars($src, ENT_QUOTES); ?>','<?php echo htmlspecialchars($title, ENT_QUOTES); ?>')">
          </div>
          <figcaption class="cap"><?php echo htmlspecialchars($title); ?></figcaption>
        </figure>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- CTA band (same vibe as Classes) -->
  <section style="background:var(--navy);color:#fff;text-align:center;padding:68px 16px">
    <div class="container">
      <h2 style="font-family:'Playfair Display',serif;margin:0 0 .25em">Train with Intention</h2>
      <p style="opacity:.9;margin:8px 0 0">Science-backed classes, motivating coaches, real results.</p>
    </div>
  </section>

  <footer class="container" style="color:#7a7e85;text-align:center;padding:30px 0;font-size:14px">
    © <?php echo date('Y'); ?> NuAge Fitness Studio. All rights reserved.
  </footer>

<script>
// Drawer behavior
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
const img = document.createElement('img'); lb.appendChild(img);
lb.querySelector('.x').onclick = ()=> lb.classList.remove('open');
lb.addEventListener('click', (e)=>{ if(e.target === lb) lb.classList.remove('open'); });
document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') lb.classList.remove('open'); });

function openLB(src, title){
  img.src = src; img.alt = title || '';
  lb.classList.add('open');
}
</script>
</body>
</html>
