<?php
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Membership Pricing — NuAge Fitness Studio</title>
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
    min-height:72vh;
    display:grid;
    place-items:center;
    text-align:center;
    color:#fff;
    background: linear-gradient(180deg, var(--coral) 0%, var(--navy) 100%);
  }
  .hero-ot .hero-inner h1{font-size:clamp(2rem,5vw,3.5rem);margin:.25rem 0}
  .hero-ot .hero-inner p{opacity:.92;margin-bottom:1rem}
  .hero-ot .cta-row{display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap}
  
  .plans{display:grid;gap:1rem;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));max-width:1100px;margin:2rem auto;padding:0 1rem}
  .plan{background:#fff;border:1px solid var(--line);border-radius:1rem;padding:1.25rem}
  .plan h3{margin:.25rem 0;color:var(--navy)}
  .plan .price{font-size:1.75rem;font-weight:800;color:var(--coral)}
  .plan ul{padding-left:1rem;margin:.5rem 0 1rem}
  .small{color:var(--muted);font-size:.9rem}

  .footer {
  background: #fff;
  border-top: 1px solid var(--line);
  padding: 40px 16px 80px; /* extra padding for iOS home bar */
  text-align: center; /* centers all text inside footer */
}

.footer .bottombar {
  display: flex;
  justify-content: center; /* center horizontally */
  align-items: center;      /* center vertically */
  gap: 12px;
  margin-top: 0; /* no extra space since it's just one line */
  font-size: 14px;
  color: #666;
  flex-wrap: wrap;
}

.footer .links {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
  margin-bottom: 20px; /* spacing above bottombar if links exist */
}

.footer .links h4 {
  margin-bottom: 10px;
  font-size: 16px;
  font-weight: 600;
}

.footer .links a {
  display: block;
  margin: 6px 0;
  line-height: 1.4;
}

/* Responsive */
@media (max-width: 768px) {
  .footer .links {
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 480px) {
  .footer .links {
    grid-template-columns: 1fr;
  }
  .footer .bottombar {
    flex-direction: column;
    align-items: center; /* keep centered on small screens */
  }
}
  </style>
</head>
<body>
  <section class="hero-ot" style="min-height:40vh">
    <div class="hero-inner">
      <h1>Membership Pricing</h1>
      <p>Month-to-Month Contract • 30-Day Cancellation</p>
      <div class="cta-row">
        <a class="btn btn-primary" href="classes.php">View Classes</a>
        <a class="btn btn-light" href="index.php">Back Home</a>
      </div>
    </div>
  </section>

  


<!-- Membership Pricing -->
<section class="pricing" style="background:var(--bone);padding:80px 20px;text-align:center;">
  <div class="container" style="max-width:1200px;margin:auto;">
    <h2 style="font-family:'Playfair Display',serif;color:var(--navy);margin-bottom:40px;">
      Membership Plans
    </h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
      
      <!-- Bronze -->
      <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3 style="color:var(--navy);margin-bottom:12px;">NuAge Fit </h3>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$60<span style="font-size:16px;">/mo</span></p>
        <ul style="list-style:none;padding:0;margin:20px 0;color:var(--muted);line-height:1.6;">
          <li>4 Classes Monthly</li>
          <li>avg. usage of 1x/week</li>
          <li>Discounted Add-On Classes</li>
          <li>Great starter plan</li>
        </ul>
        <a href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener" class="btn btn-primary">Choose Bronze</a>
      </div>

      <!-- Silver -->
      <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3 style="color:var(--navy);margin-bottom:12px;">NuAge Grind</h3>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$110<span style="font-size:16px;">/mo</span></p>
        <ul style="list-style:none;padding:0;margin:20px 0;color:var(--muted);line-height:1.6;">
          <li>8 Classes Monthly</li>
          <li>avg. usage of 2x/week</li>
          <li>Discounted Add-On Classes</li>
          <li>Balanced flexibility & value</li>
        </ul>
        <a href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener" class="btn btn-primary">Choose Silver</a>
      </div>

      <!-- Gold -->
      <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3 style="color:var(--navy);margin-bottom:12px;">NuAge Dedicated</h3>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$170<span style="font-size:16px;">/mo</span></p>
        <ul style="list-style:none;padding:0;margin:20px 0;color:var(--muted);line-height:1.6;">
          <li>Unlimited Classes</li>
          <li>recommended for 3x/week or more</li>
          <li>Discounted Add-On Classes</li>
          <li>Best for regular training</li>
        </ul>
        <a href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener" class="btn btn-primary">Choose Gold</a>
      </div>
    </div>
  </div>
</section>

<section class="hero-ot" style="min-height:40vh">
    <div class="hero-inner">
      <h1>Personal Training Pricing</h1>
      <p>Month-to-Month Contract • 30-Day Cancellation</p>
      <div class="cta-row">
        <a class="btn btn-primary" href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener">View Classes</a>
        <a class="btn btn-light" href="index.php">Back Home</a>
      </div>
    </div>
</section>

<!-- Personal Training & Add-Ons -->
<section class="personal-training" style="background:var(--bone);padding:80px 20px;text-align:center;">
  <div class="container" style="max-width:1200px;margin:auto;">
    <h2 style="font-family:'Playfair Display',serif;color:var(--navy);margin-bottom:40px;">
      Personal Training & Add-Ons
    </h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
      
      <!-- Intro Training -->
      <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3 style="color:var(--navy);margin-bottom:12px;">Intro Training</h3>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$60<span style="font-size:16px;">/session</span></p>
        <ul style="list-style:none;padding:0;margin:20px 0;color:var(--muted);line-height:1.6;">
          <li>1 Personal Training Session</li>
          <li>Perfect for beginners</li>
        </ul>
        <a href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener" class="btn btn-primary">Choose Intro</a>
      </div>

      <!-- Bronze PT -->
      <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3 style="color:var(--navy);margin-bottom:12px;">Bronze PT</h3>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$220</p>
        <ul style="list-style:none;padding:0;margin:20px 0;color:var(--muted);line-height:1.6;">
          <li>4 Personal Training Sessions</li>
          <li>Monthly training support</li>
        </ul>
        <a href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener" class="btn btn-primary">Choose Bronze PT</a>
      </div>

      <!-- Silver PT -->
      <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3 style="color:var(--navy);margin-bottom:12px;">Silver PT</h3>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$400</p>
        <ul style="list-style:none;padding:0;margin:20px 0;color:var(--muted);line-height:1.6;">
          <li>8 Personal Training Sessions</li>
          <li>For steady progress</li>
        </ul>
        <a href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener" class="btn btn-primary">Choose Silver PT</a>
      </div>

      <!-- Gold PT -->
      <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3 style="color:var(--navy);margin-bottom:12px;">Gold PT</h3>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$575</p>
        <ul style="list-style:none;padding:0;margin:20px 0;color:var(--muted);line-height:1.6;">
          <li>12 Personal Training Sessions</li>
          <li>Best for committed clients</li>
        </ul>
        <a href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener" class="btn btn-primary">Choose Gold PT</a>
      </div>

      <!-- Add-Ons -->
      <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3 style="color:var(--navy);margin-bottom:12px;">Add-Ons</h3>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$30</p>
        <ul style="list-style:none;padding:0;margin:20px 0;color:var(--muted);line-height:1.6;">
          <li>2 Class Open Gym Pass</li>
          <li>Discounted Extra Classes</li>
          <li>Flexible extras</li>
        </ul>
        <a href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener" class="btn btn-primary">Choose Add-On</a>
      </div>
    </div>
  </div>
</section>

<footer class="footer">
  <div class="bottombar">
    <p>&copy; <?php echo date('Y'); ?> NuAge Fitness Studio. All rights reserved.</p>
  </div>
</footer>


<!-- Add this inside your <head> or before 
<script>
document.addEventListener("DOMContentLoaded", function () {
  const navToggle = document.getElementById("navToggle");
  const navClose = document.getElementById("navClose");
  const navDrawer = document.getElementById("navDrawer");
  const navOverlay = document.getElementById("navOverlay");

  function openNav(){
    if (navDrawer) { navDrawer.classList.add("show"); navDrawer.removeAttribute("hidden"); navDrawer.setAttribute("aria-hidden","false"); }
    if (navOverlay) { navOverlay.classList.add("show"); navOverlay.removeAttribute("hidden"); }
  }
  function closeNav(){
    if (navDrawer) { navDrawer.classList.remove("show"); navDrawer.setAttribute("hidden",""); navDrawer.setAttribute("aria-hidden","true"); }
    if (navOverlay) { navOverlay.classList.remove("show"); navOverlay.setAttribute("hidden",""); }
  }

  if (navToggle) navToggle.addEventListener("click", openNav);
  if (navClose) navClose.addEventListener("click", closeNav);
  if (navOverlay) navOverlay.addEventListener("click", closeNav);

  document.addEventListener("keydown", (e)=>{ if (e.key === "Escape") closeNav(); });
});
</script>

</body> -->
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
