<?php
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>NuAge Fitness Studio — Our Location</title>
  <style>
    :root{
      --navy:#002D72;
      --coral:#EB1F48;
      --ink:#111418;
      --muted:#6a6d74;
      --line:#e9e6e1;
      --bone:#faf7f2;
    }
    *{box-sizing:border-box}
    body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;color:var(--ink);background:var(--bone)}
    a{text-decoration:none;color:inherit}

    /* ===== Fixed pill navbar (to match index) ===== */
    .nav-wrap{
      position:fixed; z-index:1000; left:12px; right:12px; top:10px;
      background:rgba(255,255,255,.85);
      border-radius:9999px;
      box-shadow:0 6px 24px rgba(0,0,0,.18);
      backdrop-filter:saturate(160%) blur(8px);
      -webkit-backdrop-filter:saturate(160%) blur(8px);
    }
    header.nav{
      display:grid; grid-template-columns:1fr auto; align-items:center;
      padding:10px 12px;
    }
    .brand{
      display:flex; align-items:center; gap:.5rem; justify-content:center;
      pointer-events:none; /* match index look: centered brand */
    }
    .brand img{height:30px}
    .brand-name{font-weight:800; font-size:18px; letter-spacing:.2px}
    .hamburger{
      background:#fff; border:1px solid rgba(0,0,0,.08); width:40px; height:40px;
      border-radius:9999px; display:flex; align-items:center; justify-content:center;
      cursor:pointer; margin-left:auto;
      box-shadow:0 4px 10px rgba(0,0,0,.08);
    }
    .hamburger svg{display:block}

    /* ===== Drawer ===== */
    .drawer-backdrop{
      position:fixed; inset:0; background:rgba(0,0,0,.25); opacity:0; pointer-events:none; transition:opacity .35s ease;
      z-index:1000;
    }
    .drawer{
      position:fixed; top:0; right:-340px; width:320px; height:100%;
      background:#fff; box-shadow:-12px 0 24px rgba(0,0,0,.2);
      transition:right .35s ease; padding:16px; z-index:1001;
      overflow-y:auto;
    }
    .drawer.open{ right:0; }
    .drawer-backdrop.open{ opacity:1; pointer-events:auto; }
    .drawer-header{ display:flex; align-items:center; justify-content:space-between; gap:.5rem; }
    .drawer .brand{ justify-content:flex-start; pointer-events:auto; }
    .drawer .brand img{ height:28px; }
    .drawer .brand-name{ font-weight:800; font-size:16px; }
    .drawer-close{
      background:none; border:none; font-size:26px; color:var(--coral); cursor:pointer; line-height:1;
    }
    .drawer nav{ margin-top:18px; display:flex; flex-direction:column; gap:14px; }

    /* Pills up top (match screenshot) */
    .pill-link{
      display:block; padding:14px 16px; border-radius:9999px; font-weight:700;
      border:1px solid rgba(0,0,0,.12);
    }
    .pill-link.primary{
      background:var(--navy); color:#fff; border-color:transparent;
    }
    .drawer a.simple{ padding:6px 4px; border-radius:8px; font-weight:600; }
    .drawer a.simple:hover{ text-decoration:underline; text-underline-offset:3px; }

    /* Push content below fixed nav */
    main{ padding-top:90px; text-align:center; }

    /* Page content */
    h2{color:var(--navy);font-size:32px;margin-bottom:10px}
    .address{color:var(--muted);margin-bottom:30px;font-size:18px}
    .map-container{max-width:900px;margin:0 auto;border-radius:12px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,.12)}
    iframe{width:100%;height:500px;border:0}
    .btn{display:inline-block;background:var(--coral);color:#fff;padding:14px 28px;border-radius:50px;font-weight:700;margin-top:20px;transition:.25s}
    .btn:hover{opacity:.92;transform:translateY(-1px)}
    .btn.navy{background:var(--navy)}
    footer{background:#fff;padding:22px 16px;margin-top:40px;color:var(--muted);font-size:14px;box-shadow:0 -2px 8px rgba(0,0,0,.04)}

    @media (min-width: 900px){
      .brand img{height:34px}
      .brand-name{font-size:20px}
      iframe{height:520px}
    }
  </style>
</head>
<body>

  <!-- Fixed pill navbar -->
  <div class="nav-wrap">
    <header class="nav" aria-label="Top Navigation">
      <div class="brand">
        <img src="assets/IMG_2413.png" alt="NuAge logo">
        <div class="brand-name">
          <span style="color:var(--navy);">Nu</span><span style="color:var(--coral);">Age</span> Fitness Studios
        </div>
      </div>
      <button class="hamburger" onclick="openDrawer()" aria-label="Open menu">
        <svg width="20" height="20" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none">
          <path d="M3 6h18M3 12h18M3 18h18"/>
        </svg>
      </button>
    </header>
  </div>

  <!-- Drawer + backdrop -->
  <div id="drawerBackdrop" class="drawer-backdrop" onclick="closeDrawer()"></div>
  <aside id="drawer" class="drawer" aria-label="Mobile Menu">
    <div class="drawer-header">
      <div class="brand">
        <img src="assets/IMG_2413.png" alt="NuAge logo">
        <div class="brand-name">
          <span style="color:var(--navy);">Nu</span><span style="color:var(--coral);">Age</span>FitnessStudios
        </div>
      </div>
      <button class="drawer-close" onclick="closeDrawer()" aria-label="Close menu">✕</button>
    </div>
    <nav>
      <a href="location.php" class="pill-link primary"><span style="font-weight:700">Find a Location</span></a>
      <a href="https://app.glofox.com/portal/#/branch/6765827de166ca71d60bd4e8/classes-day-view" target="_blank" rel="noopener" class="pill-link"><span style="font-weight:700">Member Login</span></a>

      <a href="https://app.glofox.com/portal/#/branch/6765827de166ca71d60bd4e8/classes-day-view" target="_blank" rel="noopener" class="simple">Classes</a>
      <a href="https://app.glofox.com/portal/#/branch/6765827de166ca71d60bd4e8/classes-day-view" target="_blank" rel="noopener" class="simple">Meet the Team</a>
      <a href="https://app.glofox.com/portal/#/branch/6765827de166ca71d60bd4e8/classes-day-view" target="_blank" rel="noopener" class="simple">Pricing</a>
    </nav>
  </aside>

  <main>
    <h2>📍 Our Location</h2>
    <p class="address">
      <a href="https://www.google.com/maps?q=7500+S+Crescent+Blvd+Unit+A+Pennsauken+NJ+08109" target="_blank">
        7500 S Crescent Blvd, Unit A, Pennsauken, NJ 08109
      </a>
    </p>
    <div class="map-container">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3055.5946222931463!2d-75.07380722368798!3d39.92809147152389!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c6c91a6f82d1f1%3A0x1e8da94ecf7a4e28!2s7500%20S%20Crescent%20Blvd%20Unit%20A%2C%20Pennsauken%20Township%2C%20NJ%2008109!5e0!3m2!1sen!2sus!4v1727295859000!5m2!1sen!2sus"
        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <a href="https://www.google.com/maps/dir/?api=1&destination=7500+S+Crescent+Blvd,+Unit+A,+Pennsauken,+NJ+08109"
       target="_blank" class="btn">Get Directions</a>
    <br/>
    <a href="index.php" class="btn navy">Back to Home</a>
  </main>

  <footer>
    <p>&copy; <?php echo date('Y'); ?> NuAge Fitness Studio. All rights reserved.</p>
  </footer>

  <script>
    function openDrawer(){
      document.getElementById('drawer').classList.add('open');
      document.getElementById('drawerBackdrop').classList.add('open');
    }
    function closeDrawer(){
      document.getElementById('drawer').classList.remove('open');
      document.getElementById('drawerBackdrop').classList.remove('open');
    }
    // Close on ESC
    document.addEventListener('keydown', function(e){
      if(e.key === 'Escape') closeDrawer();
    });
  </script>
</body>
</html>
