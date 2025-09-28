<?php
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Classes — NuAge Fitness Studio</title>
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
    // Select all buttons that go to the Glofox app
    const appLinks = document.querySelectorAll('a[href*="apps.apple.com/us/app/glofox"]');

    appLinks.forEach(link => {
      link.addEventListener("click", function (e) {
        e.preventDefault(); // stop immediate redirect
        const confirmDownload = confirm("Download the NuAge Fitness Studios App?");
        if (confirmDownload) {
          window.open(this.href, "_blank"); // open App Store link
        }
      });
    });
  });
</script>


</body>
</html>
