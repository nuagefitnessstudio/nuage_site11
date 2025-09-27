<?php
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>NuAge Fitness Studio — Our Location</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --ink:#111418; --muted:#6a6d74; --line:#e9e6e1; --bone:#faf7f2;
      --pill:#efebe6cc; --navy:#002D72; --coral:#EB1F48;
    }
    *{box-sizing:border-box} html,body{height:100%} body{margin:0;background:var(--bone);color:var(--ink);font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
    a{text-decoration:none;color:inherit}

    /* ===== Topbar (matches index) ===== */
    .topbar{
      position:fixed;top:16px;left:50%;transform:translateX(-50%);
      display:flex;align-items:center;justify-content:center;gap:14px;
      width:min(92vw,980px);background:var(--pill);backdrop-filter:blur(8px);
      border:1px solid rgba(0,0,0,.08);border-radius:999px;
      padding:10px 16px;z-index:60
    }
    .brand{display:flex;align-items:center;gap:10px}
    .brand img{height:24px}
    .brand-name{font-weight:800;letter-spacing:.08em;color:var(--navy)}

    /* Force hamburger icon lines to black to match index */
    .hamburger svg { color:#000 !important; }

    .hamburger{
      position:absolute;right:8px;top:50%;transform:translateY(-50%);
      display:inline-flex;align-items:center;justify-content:center;
      width:42px;height:42px;border-radius:999px;border:1px solid rgba(0,0,0,.08);
      background:#fff9; backdrop-filter:blur(6px); cursor:pointer;
      transition:transform .15s ease, background .2s ease;
    }
    .hamburger:active{ transform:translateY(-50%) scale(.98) }

    /* ===== Drawer + overlay (matches index) ===== */
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
    .drawer-close{background:transparent;border:none;font-size:28px;line-height:1;cursor:pointer;padding:6px;border-radius:8px;color:var(--coral)}

    .drawer-nav{padding:10px 14px;display:grid;gap:10px}
    .pill-link{display:inline-flex;align-items:center;gap:10px;font-weight:700;justify-content:center;padding:14px 16px;border-radius:999px;transition:background .25s,transform .2s}
    .pill-link.primary{background:#0c2a52;color:#fff}
    .pill-link:not(.primary){color:var(--navy);border:1px solid var(--line);background:#fff}

    /* content spacing below topbar */
    main{padding-top:120px}

    /* Page content */
    h2{color:var(--navy);font-size:32px;margin:0 0 10px}
    .address{color:var(--muted);margin:0 0 30px;font-size:18px;text-align:center}
    .map-container{max-width:900px;margin:0 auto 10px;border-radius:12px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,.12)}
    iframe{width:100%;height:500px;border:0}
    .btn{display:inline-block;background:var(--coral);color:#fff;padding:14px 28px;border-radius:50px;font-weight:700;margin:18px auto;transition:.25s}
    .btn:hover{opacity:.92;transform:translateY(-1px)}
    .btn.navy{background:var(--navy)}

    footer{background:#fff;padding:22px 16px;margin-top:40px;color:var(--muted);font-size:14px;box-shadow:0 -2px 8px rgba(0,0,0,.04)}
  </style>
</head>
<body>

  <!-- Topbar -->
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
        <div class="brand-name">NuAge<span>Fitness</span><span>Studios</span></div>
      </div>
      <button class="drawer-close" id="navClose" aria-label="Close menu">&times;</button>
    </div>
    <nav class="drawer-nav">
      <a href="location.php" class="pill-link primary"><span style="font-weight:700">Find a Location</span></a>
      <a href="https://app.glofox.com/portal/#/branch/6765827de166ca71d60bd4e8/classes-day-view" target="_blank" rel="noopener" class="pill-link">Member Login</a>
      <a href="https://app.glofox.com/portal/#/branch/6765827de166ca71d60bd4e8/classes-day-view" target="_blank" rel="noopener">Classes</a>
      <a href="https://app.glofox.com/portal/#/branch/6765827de166ca71d60bd4e8/classes-day-view" target="_blank" rel="noopener">Meet the Team</a>
      <a href="https://app.glofox.com/portal/#/branch/6765827de166ca71d60bd4e8/classes-day-view" target="_blank" rel="noopener">Pricing</a>
    </nav>
  </aside>

  <main role="main">
    <h2 style="text-align:center">📍 Our Location</h2>
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
    <div style="text-align:center">
      <a href="https://www.google.com/maps/dir/?api=1&destination=7500+S+Crescent+Blvd,+Unit+A,+Pennsauken,+NJ+08109" target="_blank" class="btn">Get Directions</a>
      <br/>
      <a href="index.php" class="btn navy">Back to Home</a>
    </div>
  </main>

  <footer>
    <p>&copy; <?php echo date('Y'); ?> NuAge Fitness Studio. All rights reserved.</p>
  </footer>

  <script>
    (function() {
      const toggle = document.getElementById('navToggle');
      const drawer = document.getElementById('navDrawer');
      const overlay = document.getElementById('navOverlay');
      const closeBtn = document.getElementById('navClose');

      function open() {
        drawer.classList.add('show');
        overlay.classList.add('show');
        drawer.hidden = false;
        overlay.hidden = false;
        toggle.setAttribute('aria-expanded', 'true');
        drawer.setAttribute('aria-hidden', 'false');
      }
      function close() {
        drawer.classList.remove('show');
        overlay.classList.remove('show');
        toggle.setAttribute('aria-expanded', 'false');
        drawer.setAttribute('aria-hidden', 'true');
        setTimeout(() => { drawer.hidden = true; overlay.hidden = true; }, 280);
      }

      toggle.addEventListener('click', open);
      closeBtn.addEventListener('click', close);
      overlay.addEventListener('click', close);
      document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') close(); });
    })();
  </script>
</body>
</html>
