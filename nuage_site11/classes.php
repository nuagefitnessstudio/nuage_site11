<?php
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Classes — NuAge Fitness Studio</title>
  <style>

/* ===== NAVBAR STYLES ===== */
.topbar {
  position: fixed;
  top: 0; left: 0; right: 0;
  height: 64px;
  background: #fff;
  border-bottom: 1px solid #e9e6e1;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 1rem;
  z-index: 1000;
}
.topbar .brand { display: flex; align-items: center; gap: .5rem; }
.topbar .brand img { height: 40px; }
.topbar .brand-name { font-weight: 700; font-size: 1rem; }

.hamburger {
  background: none; border: none; cursor: pointer;
  padding: .5rem; display: flex; align-items: center; justify-content: center;
}

.overlay {
  position: fixed; inset: 0; background: rgba(0,0,0,0.5);
  backdrop-filter: blur(2px);
  z-index: 999;
}

.drawer {
  position: fixed; top: 0; right: 0;
  width: 280px; height: 100%;
  background: #fff;
  box-shadow: -2px 0 8px rgba(0,0,0,0.15);
  transform: translateX(100%);
  transition: transform .3s ease;
  z-index: 1000;
  padding: 1rem;
}
.drawer[open] { transform: translateX(0); }
.drawer-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
.drawer-close { font-size: 2rem; background: none; border: none; cursor: pointer; }
.drawer-nav { display: flex; flex-direction: column; gap: 1rem; }
.drawer-nav a { text-decoration: none; color: #002D72; font-weight: 500; }

.pill-link {
  display: inline-block; padding: .5rem 1rem;
  border-radius: 999px; border: 1px solid #002D72;
  text-align: center;
}
.pill-link.primary {
  background: #EB1F48; color: #fff; border: none;
}

body { padding-top: 70px; } /* prevent overlap */

:root{
    --navy:#002D72;
    --coral:#EB1F48;
    --ink:#111418;
    --muted:#6a6d74;
    --line:#e9e6e1;
    --bone:#faf7f2;
  }

  *{box-sizing:border-box}
  html,body{height:100%}
  body{margin:0;color:var(--ink);font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif}
  
  a{text-decoration:none;color:inherit}
  img{max-width:100%;display:block}

  .btn{display:inline-block;font-weight:600;padding:.9rem 1.25rem;border-radius:.75rem;border:2px solid transparent;transition:transform .2s ease,opacity .2s ease}
  .btn:active{transform:scale(.98)}
  .btn-primary{background:var(--coral);color:#fff}
  .btn-outline{border-color:#fff;color:#fff}
  .btn-light{background:#fff;color:var(--navy)}

  .hero-ot{
    min-height:40vh;
    display:grid;
    place-items:center;
    text-align:center;
    color:#fff;
    background: linear-gradient(180deg, var(--coral) 0%, var(--navy) 100%);
  }
  .hero-ot .hero-inner h1{font-size:clamp(2rem,5vw,3.5rem);margin:.25rem 0}
  .hero-ot .hero-inner p{opacity:.92;margin-bottom:1rem}
  .hero-ot .cta-row{display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap}

  /* Match pricing card style */
  .plans{
    display:grid;
    gap:1rem;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    max-width:1100px;
    margin:2rem auto;
    padding:0 1rem;
  }
  .plan{
    background:#fff;
    border:1px solid var(--line);
    border-radius:1rem;
    padding:1.25rem;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
  }
  .plan h3{
    margin:.25rem 0;
    color:var(--navy);
    font-size:1.5rem;
  }
  .plan p{
    color:var(--ink);
    font-size:1rem;
    line-height:1.4;
    margin:.5rem 0 0;
  }
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
    <a href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener" class="pill-link">Member Login</a>
    <a href="classes.php">Classes</a>
    <a href="team.php">Meet the Team</a>
    <a href="pricing.php">Pricing</a>
  </nav>
</aside>

  <section class="hero-ot">
    <div class="hero-inner">
      <h1>Classes</h1>
      <p>Find your pace — strength, HIIT, mobility & more.</p>
      <div class="cta-row">
        <a class="btn btn-primary" href="pricing.php">See Pricing</a>
        <a class="btn btn-light" href="index.php">Back Home</a>
      </div>
    </div>
  </section>

  <section class="plans">
  <div class="plan">
    <h3>Core & Restore</h3>
    <p>A hybrid class that blends dynamic core training with deep restorative stretches. Build strength and stability, then ease into flexibility, mobility, and recovery work.</p>
    <a class="btn btn-primary" href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener">View Schedule & Book</a>
  </div>

  <div class="plan">
    <h3>Full Body Bootcamp</h3>
    <p>Fun, high-energy sessions that combine strength, cardio, and bodyweight exercises. Boost endurance, burn calories, and tone muscles in a motivating atmosphere.</p>
    <a class="btn btn-primary" href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener">View Schedule & Book</a>
  </div>

  <div class="plan">
    <h3>Gravity X</h3>
    <p>TRX suspension training for strength, stability, and mobility. Use gravity and leverage to challenge every muscle group while keeping movements adaptable for all levels.</p>
    <a class="btn btn-primary" href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener">View Schedule & Book</a>
  </div>

  <div class="plan">
    <h3>Ignite 45</h3>
    <p>A fast-paced 45-minute HIIT class mixing bodyweight, resistance, and cardio drills. Perfect for maximum results in minimal time.</p>
    <a class="btn btn-primary" href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener">View Schedule & Book</a>
  </div>

  <div class="plan">
    <h3>The Grind</h3>
    <p>A raw strength-based class using kettlebells, barbells, resistance bands, and bodyweight. Build functional power for everyday life.</p>
    <a class="btn btn-primary" href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener">View Schedule & Book</a>
  </div>

  <div class="plan">
    <h3>Open Gym</h3>
    <p>Self-guided access to our state-of-the-art facility. Strength, cardio, or mobility—work on your own goals at your own pace.</p>
    <a class="btn btn-primary" href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener">View Schedule & Book</a>
  </div>
</section>

<section class="hero-ot" style="min-height:30vh">
  <div class="hero-inner">
    <h2>Train with Intention</h2>
    <p>Science-backed classes, motivating coaches, real results.</p>
  </div>
</section>

<!-- Add this inside your <head> or before </body> -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const appLinks = document.querySelectorAll('a[href*="apps.apple.com/us/app/glofox"]');

    appLinks.forEach(link => {
      link.addEventListener("click", function (e) {
        e.preventDefault();

        const choice = prompt("Download the NuAge Fitness Studios App?\nType 'A' for Apple Store or 'G' for Google Play:");

        if (!choice) return; // cancelled

        if (choice.toLowerCase() === "a") {
          window.open("https://apps.apple.com/us/app/glofox/id916224471", "_blank");
        } else if (choice.toLowerCase() === "g") {
          window.open("https://play.google.com/store/apps/details?id=ie.zappy.fennec.oneapp_glofox&hl=en_US", "_blank");
        } else {
          alert("Please enter A or G.");
        }
      });
    });
  });
</script>


</body>
</html>
