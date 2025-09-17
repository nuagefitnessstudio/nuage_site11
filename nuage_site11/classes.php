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
  
  .previews{padding:3rem 1rem;display:grid;gap:1.25rem;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));max-width:1200px;margin:0 auto}
  .preview{background:#fff;border:1px solid var(--line);border-radius:1rem;overflow:hidden;display:grid;grid-template-columns:1fr}
  .preview-img{aspect-ratio:16/9;object-fit:cover;width:100%}
  .preview-text{padding:1rem 1.25rem}
  .preview-text h2{color:var(--navy);margin:0 0 .25rem}
  .preview-text p{margin:0;color:var(--ink)}
  
  .closing-ot{
    padding:3rem 1rem;
    text-align:center;
    color:#fff;
    background: linear-gradient(180deg, var(--coral) 0%, var(--navy) 100%);
  }
  .closing-ot h2{letter-spacing:.12em;text-transform:uppercase}
  
</style>
</head>
<body>
  <section class="hero-ot" style="min-height:40vh">
    <div class="hero-inner">
      <h1>Classes</h1>
      <p>Find your pace — strength, HIIT, mobility & more.</p>
      <div class="cta-row">
        <a class="btn btn-primary" href="pricing.php">See Pricing</a>
        <a class="btn btn-light" href="index.php">Back Home</a>
      </div>
    </div>
  </section>

  <section class="previews">
    <div class="preview">
      <img src="assets/class_core.jpg" alt="Core & Restore" class="preview-img" />
      <div class="preview-text">
        <h2>Core & Restore</h2>
        <p>A hybrid class that blends dynamic core training with deep restorative stretches. The first half focuses on building strength and stability through core-focused exercises; the second half eases into flexibility, mobility, and recovery work to leave you feeling balanced.</p>
      </div>
    </div>

    <div class="preview">
      <img src="assets/class_bootcamp.jpg" alt="Full Body Bootcamp" class="preview-img" />
      <div class="preview-text">
        <h2>Full Body Bootcamp</h2>
        <p>Join our full-body bootcamp for a fun, high-energy workout that combines strength, cardio, and bodyweight exercises. Boost endurance, burn calories, and tone muscles in a motivating, fast-paced session. Suitable for all fitness levels—get ready to see results!</p>
      </div>
    </div>

    <div class="preview">
      <img src="assets/class_gravityx.jpg" alt="Gravity X" class="preview-img" />
      <div class="preview-text">
        <h2>Gravity X</h2>
        <p>Harness the power of your own bodyweight with TRX suspension training. This class focuses on strength, stability, and mobility by using gravity and leverage to challenge every muscle group. From explosive pulls and presses to core-shredding holds, you’ll build functional strength and balance like never before—all while keeping joints safe and movements adaptable to every fitness level.</p>
      </div>
    </div>

    <div class="preview">
      <img src="assets/class_ignite45.jpg" alt="Ignite 45" class="preview-img" />
      <div class="preview-text">
        <h2>Ignite 45</h2>
        <p>A high-intensity interval training (HIIT) class designed to push your limits in just 45 minutes. Using a mix of bodyweight, resistance, and cardio drills, Ignite 45 keeps your heart pumping and your muscles burning. Perfect for anyone looking for maximum results in minimal time.</p>
      </div>
    </div>

    <div class="preview">
      <img src="assets/class_grind.jpg" alt="The Grind" class="preview-img" />
      <div class="preview-text">
        <h2>The Grind</h2>
        <p>A strength-based class built around functional movement patterns. Expect kettlebells, barbells, resistance bands, and bodyweight exercises that target every muscle group. No frills—just raw, powerful training that builds strength you can feel in everyday life.</p>
      </div>
    </div>

    <div class="preview">
      <img src="assets/class_open.jpg" alt="Open Gym" class="preview-img" />
      <div class="preview-text">
        <h2>Open Gym</h2>
        <p>Unlock your potential during our Open Gym sessions at Nu Age Fitness. Enjoy full access to our state-of-the-art equipment, functional training areas, and free weights in a supportive, self-guided environment. Whether you're working on strength, cardio, or mobility, it's your time—your pace.</p>
      </div>
    </div>
  </section>

  <section class="closing-ot">
    <div class="hero-inner">
      <h2>Train with Intention</h2>
      <p>Science-backed classes, motivating coaches, real results.</p>
    </div>
  </section>
</body>
</html>
