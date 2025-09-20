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

  <section class="plans">
    <!-- Memberships -->
    <div class="plan">
      <h3>Bronze</h3>
      <div class="price">$60<span class="small">/mo</span></div>
      <ul>
        <li>4 Classes Monthly</li>
        <li class="small">avg. usage of 1x/week</li>
        <li>Discounted Add-On Classes</li>
      </ul>
      <p class="small">Great starter plan</p>
      <a class="btn btn-primary" href="#">Choose Bronze</a>
    </div>

    <div class="plan">
      <h3>Silver</h3>
      <div class="price">$110<span class="small">/mo</span></div>
      <ul>
        <li>8 Classes Monthly</li>
        <li class="small">avg. usage of 2x/week</li>
        <li>Discounted Add-On Classes</li>
      </ul>
      <p class="small">Balanced flexibility & value</p>
      <a class="btn btn-primary" href="#">Choose Silver</a>
    </div>

    <div class="plan">
      <h3>Gold</h3>
      <div class="price">$170<span class="small">/mo</span></div>
      <ul>
        <li>Unlimited Classes</li>
        <li class="small">recommended for usage of 3x/week or more</li>
        <li>Discounted Add-On Classes</li>
      </ul>
      <p class="small">Best for regular training</p>
      <a class="btn btn-primary" href="#">Choose Gold</a>
    </div>

    <!-- Personal Training & Add-Ons -->
    <div class="plan">
      <h3>Intro Training</h3>
      <div class="price">$60<span class="small">/session</span></div>
      <ul>
        <li>1 Personal Training Session</li>
      </ul>
      <p class="small">Perfect for beginners</p>
      <a class="btn btn-primary" href="#">Choose Intro</a>
    </div>

    <div class="plan">
      <h3>Bronze PT</h3>
      <div class="price">$220<span class="small"></span></div>
      <ul>
        <li>4 Personal Training Sessions</li>
      </ul>
      <p class="small">Monthly training support</p>
      <a class="btn btn-primary" href="#">Choose Bronze PT</a>
    </div>

    <div class="plan">
      <h3>Silver PT</h3>
      <div class="price">$400<span class="small"></span></div>
      <ul>
        <li>8 Personal Training Sessions</li>
      </ul>
      <p class="small">For steady progress</p>
      <a class="btn btn-primary" href="#">Choose Silver PT</a>
    </div>

    <div class="plan">
      <h3>Gold PT</h3>
      <div class="price">$575<span class="small"></span></div>
      <ul>
        <li>12 Personal Training Sessions</li>
      </ul>
      <p class="small">Best for committed clients</p>
      <a class="btn btn-primary" href="#">Choose Gold PT</a>
    </div>

    <div class="plan">
      <h3>Add-Ons</h3>
      <div class="price">$30<span class="small"></span></div>
      <ul>
        <li>2 Class Open Gym Pass</li>
        <li>Discounted Extra Classes</li>
      </ul>
      <p class="small">Flexible extras</p>
      <a class="btn btn-primary" href="#">Choose Add-On</a>
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
        <h3 style="color:var(--navy);margin-bottom:12px;">Bronze</h3>
        <p style="color:var(--muted);margin-bottom:16px;">Essential access to gym and cardio zones.</p>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$29<span style="font-size:16px;">/mo</span></p>
      </div>
      <!-- Silver -->
      <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3 style="color:var(--navy);margin-bottom:12px;">Silver</h3>
        <p style="color:var(--muted);margin-bottom:16px;">Includes Bronze + group classes.</p>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$49<span style="font-size:16px;">/mo</span></p>
      </div>
      <!-- Gold -->
      <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3 style="color:var(--navy);margin-bottom:12px;">Gold</h3>
        <p style="color:var(--muted);margin-bottom:16px;">Full access + premium amenities + classes.</p>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$79<span style="font-size:16px;">/mo</span></p>
      </div>
    </div>
  </div>
</section>

<!-- Personal Training Pricing -->
<section class="personal-training" style="background:#fff;padding:80px 20px;text-align:center;">
  <div class="container" style="max-width:1200px;margin:auto;">
    <h2 style="font-family:'Playfair Display',serif;color:var(--navy);margin-bottom:40px;">
      Personal Training Packages
    </h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
      <!-- Single Session -->
      <div style="background:var(--bone);border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.05);">
        <h3 style="color:var(--navy);margin-bottom:12px;">Single Session</h3>
        <p style="color:var(--muted);margin-bottom:16px;">One-on-one personalized coaching.</p>
        <p style="font-size:28px;font-weight:700;color:var(--coral);margin:0;">$60</p>
      </div>
      <!-- 5-Pack -->
      <div style="background:var(--bone);border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.05);">
        <h3 style="color:var(--navy);margin-bottom:12px;">5-Session Pack</h3>
        <p style="color:var(--muted);margin-bottom:16px;">Bundle for consistent results.</p>
        <p style="font-size:28px;font-weight:700;color:var(--coral);margin:0;">$275</p>
      </div>
      <!-- 10-Pack -->
      <div style="background:var(--bone);border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.05);">
        <h3 style="color:var(--navy);margin-bottom:12px;">10-Session Pack</h3>
        <p style="color:var(--muted);margin-bottom:16px;">Best value for long-term training.</p>
        <p style="font-size:28px;font-weight:700;color:var(--coral);margin:0;">$500</p>
      </div>
    </div>
  </div>
</section>

</body>
</html>
