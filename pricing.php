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
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$60<span style="font-size:16px;">/mo</span></p>
        <ul style="list-style:none;padding:0;margin:20px 0;color:var(--muted);line-height:1.6;">
          <li>4 Classes Monthly</li>
          <li>avg. usage of 1x/week</li>
          <li>Discounted Add-On Classes</li>
          <li>Great starter plan</li>
        </ul>
        <a href="#" class="btn btn-primary">Choose Bronze</a>
      </div>

      <!-- Silver -->
      <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3 style="color:var(--navy);margin-bottom:12px;">Silver</h3>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$110<span style="font-size:16px;">/mo</span></p>
        <ul style="list-style:none;padding:0;margin:20px 0;color:var(--muted);line-height:1.6;">
          <li>8 Classes Monthly</li>
          <li>avg. usage of 2x/week</li>
          <li>Discounted Add-On Classes</li>
          <li>Balanced flexibility & value</li>
        </ul>
        <a href="#" class="btn btn-primary">Choose Silver</a>
      </div>

      <!-- Gold -->
      <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3 style="color:var(--navy);margin-bottom:12px;">Gold</h3>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$170<span style="font-size:16px;">/mo</span></p>
        <ul style="list-style:none;padding:0;margin:20px 0;color:var(--muted);line-height:1.6;">
          <li>Unlimited Classes</li>
          <li>recommended for 3x/week or more</li>
          <li>Discounted Add-On Classes</li>
          <li>Best for regular training</li>
        </ul>
        <a href="#" class="btn btn-primary">Choose Gold</a>
      </div>
    </div>
  </div>
</section>

<section class="hero-ot" style="min-height:40vh">
    <div class="hero-inner">
      <h1>Personal Training Pricing</h1>
      <p>Month-to-Month Contract • 30-Day Cancellation</p>
      <div class="cta-row">
        <a class="btn btn-primary" href="classes.php">View Classes</a>
        <a class="btn btn-light" href="index.php">Back Home</a>
      </div>
    </div>
  </section>


<!-- Personal Training & Add-Ons -->

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
        <a href="#" class="btn btn-primary">Choose Intro</a>
      </div>

      <!-- Bronze PT -->
      <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3 style="color:var(--navy);margin-bottom:12px;">Bronze PT</h3>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$220</p>
        <ul style="list-style:none;padding:0;margin:20px 0;color:var(--muted);line-height:1.6;">
          <li>4 Personal Training Sessions</li>
          <li>Monthly training support</li>
        </ul>
        <a href="#" class="btn btn-primary">Choose Bronze PT</a>
      </div>

      <!-- Silver PT -->
      <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3 style="color:var(--navy);margin-bottom:12px;">Silver PT</h3>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$400</p>
        <ul style="list-style:none;padding:0;margin:20px 0;color:var(--muted);line-height:1.6;">
          <li>8 Personal Training Sessions</li>
          <li>For steady progress</li>
        </ul>
        <a href="#" class="btn btn-primary">Choose Silver PT</a>
      </div>

      <!-- Gold PT -->
      <div style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3 style="color:var(--navy);margin-bottom:12px;">Gold PT</h3>
        <p style="font-size:32px;font-weight:700;color:var(--coral);margin:0;">$575</p>
        <ul style="list-style:none;padding:0;margin:20px 0;color:var(--muted);line-height:1.6;">
          <li>12 Personal Training Sessions</li>
          <li>Best for committed clients</li>
        </ul>
        <a href="#" class="btn btn-primary">Choose Gold PT</a>
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
        <a href="#" class="btn btn-primary">Choose Add-On</a>
      </div>

    </div>
  </div>
</section>





<!-- Stripe wiring (auto-detects "Choose" buttons & prices) -->
<script src="https://js.stripe.com/v3/"></script>
<script>
(function(){
  var PUBLISHABLE_KEY = "pk_live_51SABsE5czUWB82DekGKNxAuMiE4S9JsP12r3ginPDrtVtir4MO2VDul0JCl2SNMvlaDlH6YF1Lv3yrZzn5gSRcIJ00IXE3LB7W";
  if (!window.Stripe) { console.error("Stripe.js not loaded"); return; }
  var stripe = Stripe(PUBLISHABLE_KEY);

  function dollarsFromText(txt){
    if (!txt) return 0;
    var s = String(txt).replace(/,/g, '');
    var m = s.match(/\$\s*(\d+(?:\.\d+)?)/);
    if (!m) return 0;
    var val = parseFloat(m[1]);
    if (!isFinite(val)) return 0;
    return Math.floor(val); // whole dollars for checkout price_data
  }

  function findCard(el){
    // climb to a container that looks like a pricing card
    while (el && el !== document.body) {
      if (el.matches && (el.matches('.card, .pricing-card, .plan, .pricing, .col, .col-md-4') || /box-shadow:/i.test(el.getAttribute('style')||''))) {
        return el;
      }
      el = el.parentElement;
    }
    return null;
  }

  function extractPlan(el){
    var card = findCard(el);
    var name = "Payment";
    var amount = 0;

    if (card) {
      // name from heading inside card
      var h = card.querySelector('h1,h2,h3,h4');
      if (h && h.textContent.trim()) name = h.textContent.trim();

      // search for any element with a $ amount inside the card
      var priceEl = null;
      var candidates = card.querySelectorAll('h1,h2,h3,h4,p,li,span,div');
      for (var i=0;i<candidates.length;i++){
        var t = candidates[i].textContent || '';
        if (/\$\s*\d/.test(t)) { priceEl = candidates[i]; break; }
      }
      if (priceEl) amount = dollarsFromText(priceEl.textContent);
    }

    // fallback: read data-amount attribute on the button
    if ((!amount || amount < 1) && el.getAttribute('data-amount')) {
      amount = Math.floor(Number(el.getAttribute('data-amount'))||0);
    }
    // fallback: read cents
    if ((!amount || amount < 1) && el.getAttribute('data-cents')) {
      amount = Math.floor((Number(el.getAttribute('data-cents'))||0)/100);
    }

    return {name:name, amount:amount};
  }

  function handleClick(e){
    var a = e.currentTarget;
    var txt = (a.textContent||'').trim();
    // Only hijack typical purchase buttons; otherwise let links work normally
    var isBuy = /^(choose|buy|get|subscribe|upgrade)/i.test(txt) || a.classList.contains('stripe-checkout');
    if (!isBuy) return; // don't prevent default for unrelated links

    e.preventDefault();
    if (a.__busy) return;
    a.__busy = true;

    var info = extractPlan(a);
    if (!info.amount || info.amount < 1) {
      alert("Couldn't detect a price for this plan. Add data-amount=\"9\" on the button or ensure a $ price is visible on the card.");
      a.__busy = false;
      return;
    }

    fetch('create_checkout_session.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({ amount: info.amount, description: info.name })
    })
    .then(function(res){ return res.json().then(function(d){ return {ok:res.ok, data:d}; }); })
    .then(function(resp){
      if (!resp.ok) throw new Error(resp.data && resp.data.error || "Server error");
      return stripe.redirectToCheckout({ sessionId: resp.data.id });
    })
    .then(function(result){
      if (result && result.error) throw new Error(result.error.message || "Stripe redirection error");
    })
    .catch(function(err){
      console.error(err);
      alert(err.message || "Payment error. Please try again.");
      a.__busy = false;
    });
  }

  function wire(){
    // Typical purchase buttons on pricing pages
    var buttons = Array.prototype.slice.call(document.querySelectorAll('a.btn, button.btn, .stripe-checkout'));
    buttons.forEach(function(b){
      b.addEventListener('click', handleClick);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', wire);
  } else {
    wire();
  }
})();
</script>

</body>
</html>
