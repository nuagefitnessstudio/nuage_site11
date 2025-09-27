<?php
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Our Location — NuAge Fitness Studio</title>
  <style>
    :root{
      --navy:#002D72;
      --coral:#EB1F48;
      --ink:#111418;
      --muted:#6a6d74;
      --line:#e9e6e1;
      --bone:#faf7f2;
    }
    body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;color:var(--ink);background:var(--bone)}
    a{text-decoration:none;color:inherit}
    header,footer{background:#fff;padding:1rem 2rem;box-shadow:0 2px 4px rgba(0,0,0,.05)}
    header h1{margin:0;font-size:20px;color:var(--navy)}
    main{padding:60px 20px;text-align:center}
    h2{color:var(--navy);font-size:32px;margin-bottom:10px}
    .address{color:var(--muted);margin-bottom:30px;font-size:18px}
    .map-container{max-width:900px;margin:0 auto;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,.1)}
    iframe{width:100%;height:500px;border:0}
    .btn{display:inline-block;background:var(--coral);color:#fff;padding:14px 28px;border-radius:50px;font-weight:600;margin-top:20px;transition:.3s}
    .btn:hover{opacity:.9}
    footer{text-align:center;color:var(--muted);font-size:14px;margin-top:40px}
  </style>
</head>
<body>
  <header>
    <h1>NuAge Fitness Studio</h1>
  </header>

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
    <a href="https://www.google.com/maps/dir/?api=1&destination=7500+S+Crescent+Blvd+Unit+A+Pennsauken+NJ+08109" target="_blank" class="btn">Get Directions</a>
  </main>

  <footer>
    <p>&copy; <?php echo date('Y'); ?> NuAge Fitness Studio. All rights reserved.</p>
  </footer>
</body>
</html>
