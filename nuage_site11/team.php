<?php
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Meet the Team — NuAge Fitness Studio</title>
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
  
  .previews{padding:3rem 1rem;display:grid;gap:1.25rem;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));max-width:1100px;margin:0 auto}
  .preview{background:#fff;border:1px solid var(--line);border-radius:1rem;overflow:hidden;display:grid;grid-template-columns:1fr}
  .preview-img{aspect-ratio:16/9;object-fit:cover;width:100%}
  .preview-text{padding:1rem 1.25rem}
  .preview-text h2{color:var(--navy);margin:0 0 .25rem}
  .preview-text .link{color:var(--coral);font-weight:600}
  
  .testimonials{background:var(--bone);padding:3rem 1rem;text-align:center}
  .testimonials h2{color:var(--navy);margin:0 0 1rem}
  .quotes{display:grid;gap:1rem;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));max-width:1000px;margin:0 auto}
  .quote{background:#fff;border:1px solid var(--line);border-radius:1rem;padding:1rem}
  .quote blockquote{margin:0 0 .5rem;font-weight:600}
  .quote figcaption{color:var(--muted)}
  
  .newsletter{padding:2.5rem 1rem;text-align:center}
  .newsletter h2{color:var(--navy)}
  .newsletter-form{display:flex;gap:.5rem;justify-content:center;flex-wrap:wrap;margin-top:.75rem}
  .newsletter-form input{padding:.9rem 1rem;border:1px solid var(--line);border-radius:.75rem;min-width:240px}
  
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
      <h1>Meet the Team</h1>
      <p>Real coaches. Real accountability.</p>
      <div class="cta-row">
        <a class="btn btn-primary" href="classes.php">View Classes</a>
        <a class="btn btn-light" href="index.php">Back Home</a>
      </div>
    </div>
  </section>

  <section class="previews" style="max-width:1200px">
    <div class="preview">
      <img src="assets/trainer1.jpg" alt="Trainer J. Smith" class="preview-img" />
      <div class="preview-text">
        <h2>Izeem </h2>
        <p>HIIT & Strength Coach. NASM CPT.</p>
        <p class="muted"><em>“Your only limit is you.”</em></p>
      </div>
    </div>
    <div class="preview">
      <img src="assets/trainer3.jpg" alt="Trainer K. Patel" class="preview-img" />
      <div class="preview-text">
        <h2>K. Patel</h2>
        <p>Strength & Conditioning. CSCS.</p>
        <p class="muted"><em>“Consistency compounds.”</em></p>
      </div>
    </div>
  </section>
</body>
</html>
