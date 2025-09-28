<?php
// Newsletter / Inquiry handler (top of file)
$inq_msg = null; $inq_ok = false;

function nf_clean($s){ return trim(filter_var($s ?? '', FILTER_UNSAFE_RAW)); }
function nf_email($s){ $s = trim($s ?? ''); return filter_var($s, FILTER_VALIDATE_EMAIL) ? $s : ''; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inquire_form'])) {
  if (!empty($_POST['website'])) {
    $inq_ok = true; $inq_msg = "Thanks! We received your inquiry.";
  } else {
    $to   = "info@nuagefitness-studio.com";
    $name = nf_clean($_POST['name'] ?? '');
    $email = nf_email($_POST['email'] ?? '');
    $message = nf_clean($_POST['message'] ?? '');

    $errs = [];
    if (!$email) $errs[] = "Please enter a valid email address.";

    if (!$errs) {
      $subject = "New Website Inquiry";
      $from = "info@nuagefitness-studio.com";
      $headers = "From: NuAge Website <{$from}>\r\n";
      if ($email) $headers .= "Reply-To: {$email}\r\n";
      $headers .= "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\n";

      $body = "A new inquiry was submitted on the website:\n\n"
            . "Name: {$name}\n"
            . "Email: {$email}\n"
            . "Message: {$message}\n";

      $ok = @mail($to, $subject, $body, $headers, "-f {$from}");
      if ($ok) { $inq_ok = true; $inq_msg = "Thanks! We received your inquiry."; }
      else { $inq_msg = "Sorry, we couldn't send your inquiry. Please email info@nuagefitness-studio.com."; }
    } else {
      $inq_msg = implode(" ", $errs);
    }
  }
}
?>
<!-- index_remote now uses local /assets/ files. Run ./download_assets.sh to fetch real photos. -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#000000">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
  <title>NuAge Fitness Studio — Live Stock Photos</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
  <style>


    :root{
      --ink:#111418; --muted:#6a6d74; --line:#e9e6e1; --bone:#faf7f2;
      --pill:#efebe6cc; --navy:#002D72; --coral:#EB1F48;
    }
    *{box-sizing:border-box} html,body{height:100%} body{margin:0;color:var(--ink);font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,Helvetica,sans-serif;line-height:1.6;background:#fff}
    h1,h2,h3{font-family:'Playfair Display',serif;letter-spacing:.02em;line-height:1.15;margin:0 0 .25em}
    h1{font-size:clamp(32px,5vw,54px);font-weight:600} h2{font-size:clamp(28px,3.5vw,40px);font-weight:600} h3{font-size:clamp(18px,2vw,22px);font-weight:600}
    a{color:inherit;text-decoration:none} .container{max-width:1200px;margin:0 auto;padding:0 24px}

    /* ===== Topbar (hamburger) ===== */
    .topbar{
      position:fixed;top:16px;left:50%;transform:translateX(-50%);
      display:flex;align-items:center;justify-content:center;gap:14px;
      width:min(92vw,980px);background:var(--pill);backdrop-filter:blur(8px);
      border:1px solid rgba(0,0,0,.08);border-radius:999px;
      padding:10px 16px;z-index:60
    }

    /* Force hamburger icon lines to black */
   .hamburger svg {
  color: #000 !important;
    }
    .pill-link{display:inline-flex;align-items:center;gap:10px;font-size:14px;letter-spacing:.04em;color:#2c2c2c;padding:8px 14px;border-radius:999px;transition:background .25s,transform .2s}
    .pill-link:hover{background:#ffffffb3;transform:translateY(-1px)}
    .brand{display:flex;align-items:center;gap:10px}
    .brand img{height:28px}
    .brand-name{font-weight:800;letter-spacing:.08em;color:var(--navy)}
    .brand-name span{color:var(--coral)}

    /* Hamburger button */
    .hamburger{
      position:absolute;right:8px;top:50%;transform:translateY(-50%);
      display:inline-flex;align-items:center;justify-content:center;
      width:42px;height:42px;border-radius:999px;border:1px solid rgba(0,0,0,.08);
      background:#fff9; backdrop-filter:blur(6px); cursor:pointer;
      transition:transform .15s ease, background .2s ease;
    }
    .hamburger:active{transform:translateY(-50%) scale(.98)}
    .hamburger svg{width:22px;height:22px}

    /* Drawer + overlay */
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
    .drawer-close{background:transparent;border:none;font-size:28px;line-height:1;cursor:pointer;padding:6px;border-radius:8px}
    .drawer-nav{padding:10px 14px;display:grid;gap:10px}
    .drawer .pill-link{background:#f7f7f7}
    .drawer .pill-link.primary{background:#0d2a55;color:#fff;border:1px solid #0d2a55}

    /* ===== Rest of original styles ===== */
    .hero{position:relative;height:92svh;min-height:520px;overflow:hidden;color:#fff}
    .hero img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
    .hero::after{content:'';position:absolute;inset:0;background:linear-gradient(to bottom, rgba(0,0,0,.18), rgba(0,0,0,.35) 55%, rgba(0,0,0,.25));pointer-events:none}
    .hero-center{position:absolute;inset:0;display:grid;place-items:center;text-align:center;z-index:1;padding:0 16px}
    .hero-center h1{text-shadow:0 2px 16px rgba(0,0,0,.35);color:#fff}

    .split{display:grid;grid-template-columns:1.15fr 1fr;min-height:90svh;background:var(--bone)}
    .split .visual{position:relative;overflow:hidden}
    .split .visual img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transform:scale(1.03)}
    .split .text{padding:72px 32px;display:flex;align-items:center}
    .stack{width:100%}
    .eyebrow{text-transform:uppercase;letter-spacing:.18em;font-weight:700;color:#7c756b;font-size:12px;margin-bottom:20px}
    .divider{height:1px;background:var(--line);margin:28px 0}
    .feature{display:grid;grid-template-columns:1fr 160px;gap:24px;align-items:center;padding:14px 0}
    .feature img{width:160px;height:112px;object-fit:cover;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,.08)}

    .bleed{position:relative;min-height:60svh;display:grid;place-items:center;text-align:center;color:#fff;overflow:hidden}
    .bleed img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;filter:brightness(.75)}
    .bleed .inner{position:relative;z-index:1;padding:48px 16px}
    .btn{display:inline-block;border:1px solid #ffffffcc;color:#fff;padding:12px 22px;border-radius:10px;letter-spacing:.08em;font-size:14px}

    .tiles{background:var(--bone);padding:56px 24px 72px}
    .tile-grid{display:grid;gap:24px;grid-template-columns:repeat(2,minmax(0,1fr))}
    .tile{position:relative;border-radius:18px;overflow:hidden;color:#fff;min-height:52svh;display:flex;align-items:flex-end;box-shadow:0 10px 28px rgba(0,0,0,.12)}
    .tile img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;filter:brightness(.8)}
    .tile .copy{position:relative;z-index:1;padding:24px}
    .tag{font-family:'Playfair Display',serif;font-size:clamp(28px,3.5vw,44px);font-weight:600;letter-spacing:.02em;margin-bottom:.4rem}

    .amen{background:var(--bone); padding:56px 24px 72px}
    .grid6{display:grid; grid-template-columns:repeat(6,1fr); gap:14px}
    .grid6 img{width:100%; height:160px; object-fit:cover; border-radius:12px}

    .legal{background:var(--bone);padding:48px 24px 24px;color:#51493f}
    .links{display:grid;gap:24px;padding:24px;grid-template-columns:repeat(4,minmax(0,1fr))}
    .bottombar{padding:20px 24px 28px;display:flex;align-items:center;justify-content:space-between;gap:16px;color:#6a6d74;font-size:14px}
    .closing {
  background: linear-gradient(180deg, #EB1F48 0%, #002D72 100%);
  color: #fff;
  min-height: 44svh;
  display: grid;
  place-items: center;
  text-align: center;
   }

  .closing h2 {
  color: #fff;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  font-family: Inter, sans-serif;
  font-weight: 600;
  }

    @media (max-width:1024px){.split{grid-template-columns:1fr} .split .text{padding:52px 22px} .feature{grid-template-columns:1fr 140px}} 
    @media (max-width:720px){.tile{min-height:44svh} .tile-grid{grid-template-columns:1fr} .links{grid-template-columns:1fr 1fr} .bottombar{flex-direction:column;align-items:flex-start}}
    
    :root{
      --ink:#111418; --muted:#6c6f76; --line:#eae6e0; --bone:#fbf7f2;
      --pill:#f3efeacc; --navy:#0d2a55; --coral:#EB1F48;
      --shadow:0 8px 28px rgba(16,24,40,.12);
      --radius:18px;
    }
    body{letter-spacing:.01em}
    h1{font-size:clamp(36px,6vw,72px); font-weight:600}
    h2{font-size:clamp(28px,4vw,44px); font-weight:600}
    .topbar{width:min(94vw,1040px); background:var(--pill); box-shadow:var(--shadow)}
    .pill-link{font-weight:600}
    .brand img{height:26px}
    .brand-name{font-family:'Playfair Display',serif; font-weight:700; letter-spacing:.04em; font-size:18px}
    .hero{height:92svh; min-height:620px}
    .hero::after{background:linear-gradient(180deg,rgba(0,0,0,.15),rgba(0,0,0,.45) 55%,rgba(0,0,0,.35))}
    .hero .hero-center h1{font-size:clamp(40px,6.2vw,84px); text-shadow:0 12px 40px rgba(0,0,0,.45)}
    .split{background:var(--bone)}
    .split .text{padding:88px 40px}
    .eyebrow{color:#7f786e; letter-spacing:.22em}
    .divider{background:var(--line); margin:34px 0}
    .feature{grid-template-columns:1fr 180px}
    .feature img{width:180px; height:128px; border-radius:14px; box-shadow:var(--shadow)}
    .tile{border-radius:22px; box-shadow:var(--shadow)}
    .tile img{filter:brightness(.76) saturate(1.02); transform:scale(1.02); transition:transform .5s ease}
    .tile:hover img{transform:scale(1.06)}
    .btn{border-radius:12px; padding:14px 22px; font-weight:600; letter-spacing:.08em}
    .links a{opacity:.9}
    .bottombar{color:#737373}
    .grid6{gap:16px}
    .grid6 img{height:200px; border-radius:14px; box-shadow:0 8px 20px rgba(0,0,0,.08)}
    [data-reveal]{opacity:0; transform:translateY(14px); transition:all .7s ease}
    [data-reveal].show{opacity:1; transform:none}

    .hero .btn{box-shadow:0 8px 24px rgba(0,0,0,.25);}
    .hero .btn:hover{transform:translateY(-1px);}
    .hero-center>div{padding:0 12px;}
    .tag{ text-shadow: 0 6px 18px rgba(0,0,0,.35); }

    /* === NuAge: split section scroll fix (override) === */
    .split .text { align-items: flex-start; }
    @media (min-width:1025px){
      .split{ min-height:100vh; min-height:100svh; }
      .split .visual{ position: sticky; top: 0; height:100vh; height:100svh; }
      .split .visual img{ height:100%; object-fit:cover; }
      .split .text{
        max-height:100vh; max-height:100svh; overflow-y:auto; -webkit-overflow-scrolling:touch;
        padding-right:28px; padding-bottom:200px; scrollbar-gutter: stable both-edges; overscroll-behavior: contain;
      }
      .split .text::after{ content:none !important; }
      .split .text::-webkit-scrollbar{ width:10px }
      .split .text::-webkit-scrollbar-thumb{ background:#d9d3cc; border-radius:999px }
      .split .text::-webkit-scrollbar-track{ background:transparent }
    }

    /* === Cookie Consent (modal) === */
    .cookie-overlay{
      position:fixed; inset:0;
      background:rgba(17,20,24,.45); backdrop-filter:blur(2px);
      opacity:0; pointer-events:none; transition:opacity .25s ease; z-index:80;
    }
    .cookie-overlay.show{ opacity:1; pointer-events:auto; }

    .cookie-modal{
      position:fixed; bottom:24px; right:24px; width:min(560px,94vw);
      background:#f6f0e9;
      border:1px solid #cfc6bc; border-radius:12px;
      box-shadow:0 12px 40px rgba(0,0,0,.25);
      color:var(--ink); z-index:81; 
      opacity:0; transform:translateY(10px) scale(.98);
      transition:opacity .25s ease, transform .25s ease;
      pointer-events:none;
      font-size:14px; line-height:1.55;
    }
    .cookie-modal.show{ opacity:1; transform:none; pointer-events:auto; }

    .cc-inner{ padding:18px 18px 16px 18px; }
    .cc-title{ font-family:'Playfair Display',serif; font-size:18px; margin:0 0 10px; }
    .cc-body{ color:#3b3b3b; margin:0 0 12px; }
    .cc-body a{ text-decoration:underline; }

    .cc-close{
      position:absolute; top:8px; right:10px;
      border:0; background:transparent; font-size:22px; line-height:1;
      cursor:pointer; color:#444; padding:6px; border-radius:8px;
    }
    .cc-close:hover{ background:#00000010; }

    .cc-btn{ 
      display:block; width:100%; padding:12px 14px; margin-top:10px;
      border-radius:8px; border:1px solid #2a2a2a; font-weight:700; cursor:pointer;
    }
    .cc-btn.primary{ background:#2a2a2a; color:#fff; }
    .cc-btn.danger{ background:#2a2a2a; color:#fff; }
    .cc-btn.light{ background:#fff; color:#2a2a2a; }

    .cc-custom{ border-top:1px solid #ddd4ca; margin-top:12px; padding-top:12px; }
    .cc-row{ display:flex; gap:12px; align-items:center; justify-content:space-between; padding:8px 0; }
    .cc-toggle{ display:flex; align-items:center; gap:10px; }
    .cc-toggle input{ width:18px; height:18px; }
    .cc-note{ font-size:12px; color:#6a6d74; }

    @media (max-width:540px){
      .cookie-modal{ right:12px; left:12px; width:auto; bottom:12px; }
    }

    /* ====== Mobile-first responsive overrides ====== */

/* Base: fluid media and type */
img, video { max-width: 100%; height: auto; }
video { display:block; object-fit: cover; }

/* Use dynamic viewport on modern mobile for better 100vh handling */
:root { --vh: 1vh; }  /* fallback */
@supports (height: 100dvh) {
  .hero { min-height: 90dvh; }
}

/* Container padding tighter on small screens */
.container { padding-left: 16px; padding-right: 16px; }

/* Topbar: full-bleed, thumb-friendly */
.topbar{ width:calc(100% - 20px); left:50%; transform:translateX(-50%); padding:10px 12px; }
.hamburger{ width:44px; height:44px; }

/* Hero: reduce minimum height on narrow devices */
.hero { min-height: 70svh; }
@media (min-width: 768px){
  .hero { min-height: 84svh; }
}
.hero .btn { font-size: 14px; padding: 12px 18px; }

/* Split section: stack and remove sticky on mobile */
.split { grid-template-columns: 1fr; }
.split .visual { position: relative; height: 48svh; }
.split .visual img{ height: 100%; }
.split .text { padding: 28px 16px; max-height: none; overflow: visible; }
@media (min-width: 1025px){
  .split { grid-template-columns: 1.15fr 1fr; }
  .split .text { padding: 72px 32px; }
}

/* Feature rows: image under text on small screens */
.feature { grid-template-columns: 1fr; }
.feature img { width: 100%; height: 200px; }
@media (min-width: 640px){
  .feature { grid-template-columns: 1fr 160px; }
  .feature img { height: 140px; }
}
@media (min-width: 1024px){
  .feature { grid-template-columns: 1fr 180px; }
  .feature img { height: 128px; }
}

/* Tiles: single column on phones, two on tablets */
.tile-grid { grid-template-columns: 1fr; }
.tile { min-height: 42svh; }
@media (min-width: 720px){
  .tile-grid { grid-template-columns: repeat(2,minmax(0,1fr)); }
}

/* Amenities grid: 2 cols on phones, 3 on tablets, 6 on desktop */
.grid6 { grid-template-columns: repeat(2,1fr); }
.grid6 img { height: 160px; }
@media (min-width: 720px){
  .grid6 { grid-template-columns: repeat(3,1fr); }
  .grid6 img { height: 180px; }
}
@media (min-width: 1024px){
  .grid6 { grid-template-columns: repeat(6,1fr); }
  .grid6 img { height: 200px; }
}

/* Horizontal cards: make the track scrollable but comfy */
.section-cards .track{ display:flex; gap:16px; overflow-x:auto; -webkit-overflow-scrolling:touch; scroll-snap-type:x mandatory; padding-bottom:8px;}
.section-cards .card{ flex:0 0 80%; max-width:420px; scroll-snap-align:start; border-radius:14px; overflow:hidden; }

/* Footer links: 2 cols on phones */
.links { grid-template-columns: 1fr 1fr; gap:16px; }
@media (min-width: 900px){
  .links { grid-template-columns: repeat(4,minmax(0,1fr)); }
}

/* Cookie modal: fit phones nicely */
.cookie-modal{ right:12px; left:12px; bottom:12px; width:auto; }

/* Accessibility: larger tap targets on mobile */
a, button { -webkit-tap-highlight-color: transparent; }
.pill-link, .btn { padding: 12px 16px; }

/* Respect reduced motion */
@media (prefers-reduced-motion: reduce){
  * { animation: none !important; transition: none !important; }
}

  
.hero {
  position: relative;
  width: 100%;
  height: 100dvh;
  min-height: 640px;
  overflow: hidden;
}
.hero video {
  position: absolute;
  top: 0; left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
}


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

  /* Section Styling */
.studio-intro, .classes, .team, .memberships, .personal-training, .addons {
  padding: 60px 0;
  border-bottom: 1px solid var(--line);
}
.highlight { background: var(--bone); }

/* Headings */
.studio-intro h1, .classes h2, .team h2, .memberships h2, .personal-training h2, .addons h2 {
  color: var(--navy);
  text-align: center;
  margin-bottom: 20px;
}

/* Class Grid */
.class-grid {
  display: grid;
  gap: 24px;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  margin-top: 30px;
}
.class-card {
  background: #fff;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  transition: transform 0.2s, box-shadow 0.2s;
}
.class-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 6px 16px rgba(0,0,0,0.08);
}
.class-card h3 {
  color: var(--navy);
  margin-bottom: 10px;
}
.class-card p { color: var(--muted); font-size: 15px; }

/* Notes */
.note { color: var(--muted); font-style: italic; }
}

</style>
  <link rel="stylesheet" href="style.css?v=5" />

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
    background: radial-gradient(100% 80% at 50% 20%, rgba(235,31,72,.35), rgba(0,45,114,.9)), url('assets/hero.jpg') center/cover no-repeat;
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
<style>

/* === Themed SVG icons via CSS mask (NuAge) === */
.icon{width:48px;height:48px;display:inline-block;background-color:var(--navy);
  -webkit-mask:no-repeat center/contain;mask:no-repeat center/contain}
.icon--navy{background-color:var(--navy)}
.icon--coral{background-color:var(--coral)}
.icon-link:hover .icon{opacity:.9;transform:translateY(-1px);transition:.2s}
/* === end icon mask rules === */

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
        <div class="brand-name">NuAge<span>Fitness</span><span>Studios</span></div>
      </div>
      <button class="drawer-close" id="navClose" aria-label="Close menu">&times;</button>
    </div>
    <nav class="drawer-nav">
    <a href="location.php" class="pill-link primary">
  <span style="font-weight:700">Find a Location</span>
   </a>
   <a href="https://apps.apple.com/us/app/glofox/id916224471" target="_blank" rel="noopener" class="pill-link">Member Login</a>
      <!-- More links if needed -->
      <a href="classes.php">Classes</a>
      <a href="team.php">Meet the Team</a>
      <a href="pricing.php">Pricing</a>
    
    </nav>
  </aside>

  <header class="hero">
  <video id="heroVideo" autoplay muted playsinline loop poster="https://images.pexels.com/photos/4761669/pexels-photo-4761669.jpeg?auto=compress&cs=tinysrgb&w=1600&h=1000&dpr=1">
    <source src="assets/benchpress_hero.mp4" type="video/mp4">
    <source src="assets/benchpress_hero.webm" type="video/webm">
    <source src="assets/workout_hero.mp4" type="video/mp4">
    <source src="assets/hero.mp4" type="video/mp4">
  </video>
  <div class="hero-center">
    <div>
      <h1 style="font-family:'Playfair Display',serif;font-size:3rem;">
        This is the feeling of 
        <span style="color:var(--navy);">Nu</span><span style="color:var(--coral);">Age</span>
      </h1>
      <p style="max-width:720px;margin:12px auto 20px;color:#f0f0f0;opacity:.95;font-size:1.2rem;">
        Premium <span style="color:var(--coral);">training</span>, 
        <span style="color:var(--coral);">recovery</span>, 
        and <span style="color:var(--coral);">community</span> — all in one elegant club experience.
      </p>
      <a href="location.php" class="btn"
   style="background:#ffffffcc;color:var(--navy);border:2px solid #ffffff;border-radius:8px;
          padding:12px 24px;font-weight:600;backdrop-filter:blur(4px);" role="button">
  Find a Location
</a>

    </div>
  </div>
</header>



  <!-- HERO -->
  <section class="closing-ot">
    <div class="hero-inner">
      <h1>Train with Intention</h1>
      <p>Science-backed classes, motivating coaches, real results.</p>
      <div class="cta-row">
        <a class="btn btn-primary" href="classes.php">View Classes</a>
        <a class="btn btn-outline" href="pricing.php">See Pricing</a>
      </div>
    </div>
  </section>

  <section class="plans" style="max-width:900px;margin:2rem auto;padding:0 1rem">
  <div class="plan">
    <h3 style="color:var(--navy);font-family:'Playfair Display',serif;">
      <span style="color:var(--navy);">Nu</span><span style="color:var(--coral);">Age</span> Fitness Studio
    </h3>
    <p style="color:var(--navy);font-size:18px;line-height:1.6;">
      At <span style="color:var(--coral);">NuAge Fitness Studio</span>, we believe that fitness is more than just a workout — 
      it's a <span style="color:var(--coral);">lifestyle</span>. Founded in 2024 with a passion for health, strength, 
      and community, our state-of-the-art facility offers a range of equipment, from free weights and machines 
      to functional training zones and cardio stations. Whether you're a beginner, an athlete, or just looking 
      to stay active, our certified trainers and support staff are here to help you 
      <span style="color:var(--coral);">crush your goals</span>.
    </p>
  </div>
</section>


<!-- NuAge App Preview Section -->
<section class="app-preview" style="background:var(--bone);padding:80px 0;text-align:center;">
  <div class="container">
    <h2 style="font-family:'Playfair Display',serif;">
      <span style="color:var(--navy);">Nu</span><span style="color:var(--coral);">Age</span>
      <span style="color:var(--navy);"> Fitness Studios App</span>
    </h2>
    <p style="max-width:640px;margin:16px auto;color:var(--navy);font-size:18px;">
  Powered by <span style="color:var(--coral);">Glofox</span> — currently in development, not yet live. 
  Soon you’ll be able to <span style="color:var(--coral);">book classes</span>, 
  track progress, and stay connected right from your phone.
</p>

    <div style="margin:40px auto;max-width:420px;">
      <img src="assets/ChatGPT Image Sep 18, 2025, 11_46_05 PM.png"
        alt="NuAge Studio App Preview"
        style="width:100%;border-radius:28px;background:var(--bone);">
    </div>
    <!--
    <div style="display:flex;justify-content:center;gap:20px;flex-wrap:wrap;">
      <a href="#"><img src="assets/appstore_badge.png" alt="Download on App Store" style="height:60px;"></a>
      <a href="#"><img src="assets/googleplay_badge.png" alt="Get it on Google Play" style="height:60px;"></a>
    </div>
    -->
  </div>
</section>


  <!-- PREVIEWS -->
  <section class="previews">
    <div class="preview">
      <img src="assets/man-2264825_1280.jpg" alt="Classes preview" class="preview-img" />
      <div class="preview-text">
        <h2>Classes</h2>
        <p>Strength, HIIT, mobility and more — built for every level.</p>
        <a class="link" href="classes.php">View all classes →</a>
      </div>
    </div>
    <div class="preview">
      <img src="assets/abs-1850926_1280.jpg" alt="Team preview" class="preview-img" />
      <div class="preview-text">
        <h2>Meet the Team</h2>
        <p>Certified trainers who keep you accountable and inspired.</p>
        <a class="link" href="team.php">Meet our trainers →</a>
      </div>
    </div>
    <div class="preview">
      <img src="assets/4D288153-7690-43EE-A7C6-489BA52C9E9B_1_105_c.jpeg" alt="Pricing preview" class="preview-img" />
      <div class="preview-text">
        <h2>Membership Pricing</h2>
        <p>Transparent plans. Month-to-month. 30-day cancellation.</p>
        <a class="link" href="pricing.php">See plans →</a>
      </div>
    </div>
  </section>

<!-- Icon Features (themed to match site) -->
<section class="previews" style="max-width:1100px">
  <!-- Time App -->
  <a class="preview icon-link" href="<?= isset($BASE)? $BASE : '' ?>/classes.php">
    <div class="preview-text" style="display:flex;align-items:center;gap:12px">
      <span class="icon icon--coral"
        style="-webkit-mask-image:url('assets/6639bdc45130e047f53e388b_Time%20App%20icon.svg');
               mask-image:url('assets/6639bdc45130e047f53e388b_Time%20App%20icon.svg');"></span>
      <div>
        <h2 style="margin:0">Schedule</h2>
        <p style="margin:.25rem 0 0">Classes that fit your week.</p>
        <span class="link">View classes →</span>
      </div>
    </div>
  </a>

  <!-- Clock -->
  <a class="preview icon-link" href="<?= isset($BASE)? $BASE : '' ?>/pricing.php">
    <div class="preview-text" style="display:flex;align-items:center;gap:12px">
      <span class="icon icon--navy"
        style="-webkit-mask-image:url('assets/6639bdc45130e047f53e3950_Clock%20icon.svg');
               mask-image:url('assets/6639bdc45130e047f53e3950_Clock%20icon.svg');"></span>
      <div>
        <h2 style="margin:0">Flexible</h2>
        <p style="margin:.25rem 0 0">Month‑to‑month • 30‑day cancel.</p>
        <span class="link">See pricing →</span>
      </div>
    </div>
  </a>

  <!-- Hourglass -->
  <a class="preview icon-link" href="<?= isset($BASE)? $BASE : '' ?>/team.php">
    <div class="preview-text" style="display:flex;align-items:center;gap:12px">
      <span class="icon icon--coral"
        style="-webkit-mask-image:url('assets/6639bdc45130e047f53e381b_Hourglass%20icon.svg');
               mask-image:url('assets/6639bdc45130e047f53e381b_Hourglass%20icon.svg');"></span>
      <div>
        <h2 style="margin:0">Coaches</h2>
        <p style="margin:.25rem 0 0">Real guidance. Real results.</p>
        <span class="link">Meet the team →</span>
      </div>
    </div>
  </a>
</section>



  <!-- TESTIMONIALS -->
  <section class="testimonials">
    <h2>What Our Members Say</h2>
    <div class="quotes">
      <figure class="quote">
        <blockquote>“The energy here is unmatched. I’ve never felt stronger.”</blockquote>
        <figcaption>— T. Rivera</figcaption>
      </figure>
      <figure class="quote">
        <blockquote>“Coaches actually watch your form and push you safely.”</blockquote>
        <figcaption>— D. Lee</figcaption>
      </figure>
      <figure class="quote">
        <blockquote>“Finally a schedule I can stick to. Real progress in 8 weeks.”</blockquote>
        <figcaption>— M. Harris</figcaption>
      </figure>
    </div>
  </section>

  <!-- NEWSLETTER -->
<section class="newsletter">
  <h2>Get Class Drops & Deals</h2>
  <form class="newsletter-form" method="post">
    <input type="hidden" name="inquire_form" value="1">
    <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;opacity:0;height:0;width:0" aria-hidden="true">

    <input type="text" name="name" placeholder="Your Name" aria-label="Name" required />
    <input type="email" name="email" placeholder="Your Email" aria-label="Email" required />
    <textarea name="message" placeholder="Your Message" aria-label="Message" rows="4" required></textarea>

    <button class="btn btn-primary" type="submit">Inquire</button>
  </form>
</section>

  <!-- CLOSING -->
  <section class="closing-ot">
    <h2>Start Today</h2>
    <p>One decision can change your year. Let’s move.</p>
    <a class="btn btn-light" href="classes.php">Book a Class</a>
  </section>

  <footer class="footer">
  <div class="bottombar">
    <p>&copy; <?php echo date('Y'); ?> NuAge Fitness Studio. All rights reserved.</p>
  </div>
</footer>

<script>
    document.getElementById('year').textContent = new Date().getFullYear();
    // Optional: click to pause/play hero video
    (function(){
      const v = document.getElementById('heroVideo');
      if(v){ v.addEventListener('click', ()=>{ v.paused ? v.play() : v.pause(); }); }
    })();
  </script>
<script>
  // Ensure remote images don't break presentation if blocked by network/privacy tools
  (function(){
    function swapToFallback(img){
      if(!img.dataset.fallback) return;
      img.src = img.dataset.fallback;
      img.removeAttribute('srcset');
      img.classList.remove('remote');
    }
    document.querySelectorAll('img.remote').forEach(function(img){
      img.addEventListener('error', function(){ swapToFallback(img); }, {once:true});
      const t = setTimeout(function(){
        if(!img.complete || img.naturalWidth === 0){ swapToFallback(img); }
      }, 4000);
      img.addEventListener('load', function(){ clearTimeout(t); }, {once:true});
    });
  })();
  </script>
<script>
  (function(){
    const els = document.querySelectorAll('[data-reveal]');
    const io = new IntersectionObserver((entries)=>{
      entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('show'); io.unobserve(e.target); } });
    }, {threshold:0.12});
    els.forEach(el=>io.observe(el));
  })();
  </script>
<script>
  (function(){
    const toggle = document.getElementById('navToggle');
    const drawer = document.getElementById('navDrawer');
    const overlay = document.getElementById('navOverlay');
    const closeBtn = document.getElementById('navClose');

    function openDrawer(){
      drawer.hidden = false; overlay.hidden = false;
      requestAnimationFrame(()=>{
        drawer.classList.add('show'); overlay.classList.add('show');
        toggle.setAttribute('aria-expanded','true');
        drawer.setAttribute('aria-hidden','false');
      });
      const first = drawer.querySelector('a,button'); if(first) first.focus();
      document.documentElement.style.overflow = 'hidden';
    }
    function closeDrawer(){
      drawer.classList.remove('show'); overlay.classList.remove('show');
      toggle.setAttribute('aria-expanded','false');
      drawer.setAttribute('aria-hidden','true');
      setTimeout(()=>{ drawer.hidden = true; overlay.hidden = true; document.documentElement.style.overflow = ''; }, 280);
      toggle.focus();
    }
    toggle.addEventListener('click', openDrawer);
    closeBtn.addEventListener('click', closeDrawer);
    overlay.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape' && !drawer.hidden) closeDrawer(); });
  })();
  </script>
<script>
  (function(){
    const KEY = 'nuage.cookies.v1';
    const $ = (id)=>document.getElementById(id);

    const modal   = $('ccModal');
    const overlay = $('ccOverlay');
    const btnX    = $('ccClose');
    const accept  = $('ccAcceptAll');
    const reject  = $('ccRejectAll');
    const custom  = $('ccCustomizeBtn');
    const panel   = $('ccCustom');
    const save    = $('ccSave');
    const a       = $('ccAnalytics');
    const m       = $('ccMarketing');
    const manageLink = document.getElementById('manageCookies'); // optional external link

    let prevFocus = null;

    function open(){
      prevFocus = document.activeElement;
      modal.hidden = false; overlay.hidden = false;
      requestAnimationFrame(()=>{
        modal.classList.add('show'); overlay.classList.add('show');
      });
      document.documentElement.style.overflow = 'hidden';
      modal.querySelector('button, a, input')?.focus();
    }
    function close(){
      modal.classList.remove('show'); overlay.classList.remove('show');
      setTimeout(()=>{ modal.hidden = true; overlay.hidden = true; }, 250);
      document.documentElement.style.overflow = '';
      prevFocus?.focus();
    }

    function saveConsent(val){
      localStorage.setItem(KEY, JSON.stringify({ ...val, ts: Date.now() }));
      close();
    }

    accept.addEventListener('click', ()=>saveConsent({necessary:true, analytics:true, marketing:true}));
    reject.addEventListener('click', ()=>saveConsent({necessary:true, analytics:false, marketing:false}));
    save.addEventListener('click', ()=>saveConsent({necessary:true, analytics:a.checked, marketing:m.checked}));

    custom.addEventListener('click', ()=>{
      const show = panel.hasAttribute('hidden');
      if(show){ panel.removeAttribute('hidden'); } else { panel.setAttribute('hidden',''); }
      custom.setAttribute('aria-expanded', String(show));
    });

    btnX.addEventListener('click', close);
    overlay.addEventListener('click', close);
    document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape' && !modal.hidden) close(); });

    if(manageLink){ manageLink.addEventListener('click', (e)=>{ e.preventDefault(); open(); }); }

    const existing = localStorage.getItem(KEY);
    if(!existing){
      setTimeout(open, 600);
    }

    window.getNuAgeConsent = function(){
      try{ return JSON.parse(localStorage.getItem(KEY) || '{}'); }catch{return {}}
    };
  })();
  </script>




<!-- Location Modal -->
<div id="locationModal" style="
  display:none;
  position:fixed;
  top:0; left:0;
  width:100%; height:100%;
  background:rgba(0,0,0,0.7);
  z-index:1000;
  justify-content:center;
  align-items:center;
">
  <div style="
    background:#fff;
    border-radius:16px;
    max-width:900px;
    width:90%;
    padding:20px;
    box-shadow:0 8px 24px rgba(0,0,0,0.2);
    position:relative;
  ">
    <!-- Close button -->
    <button onclick="closeLocationModal()" style="
      position:absolute;
      top:10px; right:14px;
      background:none;
      border:none;
      font-size:28px;
      cursor:pointer;
      color:#333;
    ">&times;</button>

    <!-- Map -->
    <div style="border-radius:12px; overflow:hidden; margin-bottom:20px;">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3055.5946222931463!2d-75.07380722368798!3d39.92809147152389!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c6c91a6f82d1f1%3A0x1e8da94ecf7a4e28!2s7500%20S%20Crescent%20Blvd%20Unit%20A%2C%20Pennsauken%20Township%2C%20NJ%2008109!5e0!3m2!1sen!2sus!4v1727295859000!5m2!1sen!2sus"
        width="100%"
        height="400"
        style="border:0;"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>

    <!-- Get Directions Button -->
    <div style="text-align:center;">
      <a href="https://www.google.com/maps/dir/?api=1&destination=7500+S+Crescent+Blvd,+Unit+A,+Pennsauken,+NJ+08109"
         target="_blank"
         style="display:inline-block; background:#EB1F48; color:#fff; font-weight:600;
                padding:12px 24px; border-radius:8px; text-decoration:none;">
        Get Directions
      </a>
    </div>
  </div>
</div>

<script>
  function openLocationModal() {
    document.getElementById('locationModal').style.display = 'flex';
  }
  function closeLocationModal() {
    document.getElementById('locationModal').style.display = 'none';
  }
  document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('findLocationBtn');
    if (btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        openLocationModal();
      });
    }
    var modal = document.getElementById('locationModal');
    if (modal) {
      modal.addEventListener('click', function(e) {
        if (e.target === modal) {
          closeLocationModal();
        }
      });
    }
  });
</script>

</body>
</html>
