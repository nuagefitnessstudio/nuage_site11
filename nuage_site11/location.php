<?php
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NuAge Fitness Studio — Our Location</title>
  <style>
    :root {
      --navy:#002D72;
      --coral:#EB1F48;
      --ink:#111418;
      --muted:#6a6d74;
      --line:#e9e6e1;
      --bone:#faf7f2;
    }
    body {margin:0; font-family:Inter, system-ui, sans-serif; color:var(--ink); background:var(--bone);}
    a {text-decoration:none; color:inherit;}
    header {display:flex; justify-content:space-between; align-items:center; padding:1rem; background:white; box-shadow:0 2px 4px rgba(0,0,0,0.1);}
    .brand {display:flex; align-items:center; gap:.5rem;}
    .brand img {height:40px;}
    .brand-name {font-weight:700; font-size:1.2rem;}
    .hamburger {background:none; border:none; cursor:pointer;}
    /* Drawer */
    .drawer {position:fixed; top:0; right:-320px; width:300px; height:100%; background:white; box-shadow:-2px 0 8px rgba(0,0,0,0.2); transition:right .3s ease; padding:1rem; z-index:1000;}
    .drawer.open {right:0;}
    .drawer-header {display:flex; justify-content:space-between; align-items:center;}
    .drawer nav {margin-top:2rem; display:flex; flex-direction:column; gap:1rem;}
    .drawer a {padding:.75rem 1rem; border-radius:2rem; font-weight:600;}
    .drawer a.active {background:var(--navy); color:white;}
    .drawer a.primary {border:1px solid var(--line);}
    /* Main content */
    .hero {text-align:center; padding:3rem 1rem;}
    .hero h1 {color:var(--navy); font-size:2rem; margin-bottom:1rem;}
    .hero p a {color:var(--coral); font-weight:600;}
    .map-container {max-width:900px; margin:2rem auto; border-radius:12px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1);}
    iframe {width:100%; height:450px; border:0;}
    .btn {display:inline-block; padding:.75rem 1.5rem; margin:1rem auto; border-radius:2rem; font-weight:600;}
    .btn.coral {background:var(--coral); color:white;}
    .btn.navy {background:var(--navy); color:white;}
    footer {margin-top:3rem; padding:2rem; background:white; text-align:center; font-size:.9rem; color:var(--muted); border-top:1px solid var(--line);}
  </style>
  <script>
    function toggleDrawer(){document.getElementById('drawer').classList.toggle('open');}
  </script>
</head>
<body>

<header>
  <div class="brand">
    <img src="assets/IMG_2413.png" alt="NuAge logo">
    <div class="brand-name"><span style="color:var(--coral);">Nu</span><span style="color:var(--navy);">Age</span> Fitness Studios</div>
  </div>
  <button class="hamburger" onclick="toggleDrawer()" aria-label="Menu">
    <svg width="28" height="28" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none">
      <path d="M3 6h18M3 12h18M3 18h18"/>
    </svg>
  </button>
</header>

<div id="drawer" class="drawer">
  <div class="drawer-header">
    <div class="brand">
      <img src="assets/IMG_2413.png" alt="NuAge logo">
      <div class="brand-name"><span style="color:var(--coral);">Nu</span><span style="color:var(--navy);">Age</span>FitnessStudios</div>
    </div>
    <button class="hamburger" onclick="toggleDrawer()">✕</button>
  </div>
  <nav>
    <a href="location.php" class="active">Find a Location</a>
    <a href="#">Member Login</a>
    <a href="#">Classes</a>
    <a href="#">Meet the Team</a>
    <a href="#">Pricing</a>
  </nav>
</div>

<main>
  <section class="hero">
    <h1>📍 Our Location</h1>
    <p><a href="https://www.google.com/maps/place/7500+S+Crescent+Blvd+Unit+A,+Pennsauken,+NJ+08109" target="_blank">
      7500 S Crescent Blvd, Unit A, Pennsauken, NJ 08109
    </a></p>
  </section>

  <div class="map-container">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3055.5946222931463!2d-75.07380722368798!3d39.92809147152389!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c6c91a6f82d1f1%3A0x1e8da94ecf7a4e28!2s7500%20S%20Crescent%20Blvd%20Unit%20A%2C%20Pennsauken%20Township%2C%20NJ%2008109!5e0!3m2!1sen!2sus!4v1727295859000!5m2!1sen!2sus"
      allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
  </div>

  <div style="text-align:center;">
    <a href="https://goo.gl/maps/7iLxXqH8mDf2" target="_blank" class="btn coral">Get Directions</a>
    <a href="index.php" class="btn navy">Back to Home</a>
  </div>
</main>

<footer>
  &copy; <?php echo date('Y'); ?> NuAge Fitness Studio. All rights reserved.
</footer>

</body>
</html>
