<?php
// NuAge Fitness — Gallery Page (theme-matched to index.php)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <title>NuAge Fitness — Gallery</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    /* ===== Theme Tokens (mirrors index.php) ===== */
    :root{
      --ink:#111418; --muted:#6a6d74; --line:#e9e6e1; --bone:#faf7f2;
      --pill:#efebe6cc; --navy:#002D72; --coral:#EB1F48;
      --navy-deep:#0d2a55;
    }
    *{box-sizing:border-box} html,body{height:100%}
    body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,Helvetica,sans-serif;line-height:1.6;background:#fff;color:var(--ink)}
    h1,h2,h3{font-family:'Playfair Display',serif;letter-spacing:.02em;line-height:1.15;margin:0 0 .25em}
    h1{font-size:clamp(32px,5vw,52px);font-weight:600}
    h2{font-size:clamp(22px,3.5vw,30px);font-weight:600}
    h3{font-size:clamp(16px,2.2vw,20px);font-weight:600}
    a{color:inherit;text-decoration:none}
    .container{max-width:1200px;margin:0 auto;padding:0 24px}

    /* ===== Elevated Glass Topbar (like index.php) ===== */
    .topbar{
      position:sticky;top:0;z-index:60;height:68px;
      display:grid;grid-template-columns:1fr auto 1fr;align-items:center;
      background:rgba(255,255,255,.86);backdrop-filter:saturate(120%) blur(12px);
      border-bottom:1px solid #f2f2f2;padding:0 12px;
    }
    .brand{display:flex;align-items:center;gap:12px}
    .brand .logo{width:38px;height:38px;border-radius:14px;background:linear-gradient(135deg,var(--navy),var(--navy-deep));display:grid;place-items:center;color:#fff;font-weight:800;letter-spacing:.3px;box-shadow:0 6px 20px rgba(0,45,114,.25)}
    .brand .name{font-weight:800;letter-spacing:.3px;color:var(--navy)}
    .nav{display:flex;gap:10px;justify-content:center}
    .pill-link{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:999px;border:1px solid #e8e8e8;background:#f7f7f7;color:#222;font-weight:700}
    .pill-link:hover{transform:translateY(-1px);box-shadow:0 6px 16px rgba(0,0,0,.07)}
    .pill-link:focus-visible{outline:3px solid rgba(235,31,72,.35);outline-offset:2px}
    .pill-link.primary{background:var(--navy);color:#fff;border-color:var(--navy)}
    .right{display:flex;justify-content:flex-end;gap:8px}

    .hamburger{display:none;border:1px solid #e8e8e8;padding:8px 10px;border-radius:10px;background:#fff}
    @media (max-width:900px){
      .nav{display:none}
      .hamburger{display:inline-flex}
      .topbar{grid-template-columns:auto 1fr auto}
    }

    /* ===== Hero (navy/coral gradient overlay like index hero) ===== */
    .page-hero{
      position:relative;min-height:260px;display:grid;place-items:center;text-align:center;padding:48px 16px;color:#fff;
      background:#0b2b59;
    }
    .page-hero::before{
      content:'';position:absolute;inset:0;
      background:
        linear-gradient(180deg, rgba(0,0,0,.15) 0%, rgba(0,0,0,.45) 60%, rgba(0,0,0,.55)),
        linear-gradient(135deg, rgba(0,45,114,.85), rgba(235,31,72,.25)),
        url('assets/hero/gym-hero.jpg') center/cover no-repeat;
      filter:saturate(105%);
    }
    .page-hero > *{position:relative;z-index:1}
    .breadcrumbs{font-size:13px;opacity:.9;margin-bottom:10px}
    .breadcrumbs a{opacity:.9} .breadcrumbs a:hover{opacity:1;text-decoration:underline}

    /* ===== Section heading with coral underline ===== */
    .section-title{display:inline-block;margin:24px auto 6px;position:relative}
    .section-title::after{content:'';position:absolute;left:50%;transform:translateX(-50%);bottom:-8px;width:72px;height:3px;background:var(--coral);border-radius:6px}

    /* ===== Filter Toolbar (chips same pill style) ===== */
    .toolbar{display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:center;margin:26px 0}
    .chip{border:1px solid #e6e6e6;background:var(--pill);padding:9px 14px;border-radius:999px;font-weight:700;cursor:pointer;user-select:none;transition:.15s}
    .chip:hover{transform:translateY(-1px)}
    .chip.active{background:var(--coral);color:#fff;border-color:var(--coral)}
    .search{display:flex;gap:8px;align-items:center;border:1px solid #eae9e7;border-radius:14px;padding:10px 12px;flex:1;min-width:240px;background:#fff}
    .search input{border:none;outline:none;width:100%;font-size:14px;background:transparent}

    /* ===== Gallery Section on Bone background like index split sections ===== */
    .gallery-wrap{background:var(--bone);border-top:1px solid var(--line);border-bottom:1px solid var(--line);padding:34px 0 40px}

    /* Masonry (CSS columns) with soft cards */
    .grid{columns:1 280px;column-gap:16px;margin:16px 0 34px}
    @media (min-width:640px){.grid{columns:2 280px}}
    @media (min-width:940px){.grid{columns:3 280px}}
    @media (min-width:1200px){.grid{columns:4 280px}}
    .card{break-inside:avoid;margin:0 0 16px;border-radius:22px;overflow:hidden;border:1px solid #eee;background:#fff;
      box-shadow:0 6px 24px rgba(0,0,0,.06);
      transition:.2s transform ease, .2s box-shadow ease;
    }
    .card:hover{transform:translateY(-3px);box-shadow:0 16px 44px rgba(0,0,0,.10)}
    .card img,.card video{width:100%;height:auto;display:block}
    .meta{display:flex;align-items:center;justify-content:space-between;padding:12px 14px;font-size:13px;color:#4b4e55;background:linear-gradient(180deg,#fff, #f9f9f9)}
    .tag{display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;background:#f2f4f7;border:1px solid #e5e7eb;font-weight:700;font-size:12px}
    .tag.coral{background:rgba(235,31,72,.08);color:#B61235;border-color:rgba(235,31,72,.35)}

    /* ===== Lightbox with glass controls ===== */
    .lightbox{position:fixed;inset:0;background:rgba(6,10,18,.9);display:none;align-items:center;justify-content:center;z-index:100}
    .lightbox.open{display:flex}
    .lb-content{position:relative;max-width:min(92vw,1200px);max-height:86vh;display:grid;place-items:center}
    .lb-img,.lb-video{max-width:100%;max-height:86vh;border-radius:14px;box-shadow:0 24px 80px rgba(0,0,0,.55)}
    .control{position:absolute;background:rgba(255,255,255,.12);color:#fff;border:none;border-radius:12px;padding:10px 14px;cursor:pointer;font-weight:800;backdrop-filter:blur(3px)}
    .close{top:-52px;right:0}
    .prev{left:-56px;top:50%;transform:translateY(-50%)}
    .next{right:-56px;top:50%;transform:translateY(-50%)}
    @media (max-width:700px){
      .prev{left:6px} .next{right:6px} .close{top:8px;right:8px}
    }

    /* ===== Drawer ===== */
    #drawer{display:none;position:fixed;inset:0;background:rgba(0,0,0,.35);z-index:70}
    .drawer{position:absolute;right:0;top:0;height:100%;width:min(88vw,360px);background:#fff;display:flex;flex-direction:column;gap:10px;padding:18px;border-left:1px solid #eaeaea}
    .drawer .brand .logo{height:32px;width:32px;border-radius:10px}
    .drawer a{margin:4px 0}

    /* ===== Footer ===== */
    footer{border-top:1px solid #f0f0f0;background:#fafafa;margin-top:0}
    .footer-inner{max-width:1200px;margin:0 auto;padding:26px}
    .muted{color:var(--muted)}
  </style>
</head>
<body>

  <!-- ===== Topbar ===== -->
  <header class="topbar">
    <div class="brand">
      <div class="logo">Nu</div>
      <div class="name">NuAge Fitness</div>
    </div>
    <nav class="nav" aria-label="Primary">
      <a class="pill-link" href="index.php">Home</a>
      <a class="pill-link" href="classes.php">Classes</a>
      <a class="pill-link" href="team.php">Team</a>
      <a class="pill-link primary" href="gallery.php" aria-current="page">Gallery</a>
    </nav>
    <div class="right">
      <button class="hamburger" aria-label="Open menu" title="Menu" onclick="document.getElementById('drawer').style.display='block'">☰</button>
      <a class="pill-link" href="#" onclick="alert('App links coming soon')" role="button">Get the App</a>
    </div>
  </header>

  <!-- Mobile Drawer -->
  <div id="drawer" onclick="if(event.target===this)this.style.display='none'">
    <div class="drawer">
      <div style="display:flex;align-items:center;justify-content:space-between">
        <div class="brand">
          <div class="logo">Nu</div>
          <div class="name">NuAge</div>
        </div>
        <button class="pill-link" onclick="document.getElementById('drawer').style.display='none'">Close</button>
      </div>
      <a class="pill-link" href="index.php">Home</a>
      <a class="pill-link" href="classes.php">Classes</a>
      <a class="pill-link" href="team.php">Team</a>
      <a class="pill-link primary" href="gallery.php" aria-current="page">Gallery</a>
    </div>
  </div>

  <!-- ===== Page Hero ===== -->
  <section class="page-hero">
    <div>
      <div class="breadcrumbs"><a href="index.php">Home</a> / <strong>Gallery</strong></div>
      <h1 class="section-title">Gallery</h1>
      <p style="opacity:.95;margin:12px 0 0">Facilities • Classes • Community • Transformations</p>
    </div>
  </section>

  <!-- ===== Toolbar on white, grid on bone (like split sections) ===== -->
  <section class="container" aria-label="Filters">
    <div class="toolbar">
      <div id="filterBar" role="tablist" aria-label="Filter gallery">
        <button class="chip active" data-filter="all" role="tab" aria-selected="true">All</button>
        <button class="chip" data-filter="classes" role="tab">Classes</button>
        <button class="chip" data-filter="facilities" role="tab">Facilities</button>
        <button class="chip" data-filter="members" role="tab">Members</button>
        <button class="chip" data-filter="transformations" role="tab">Transformations</button>
        <button class="chip" data-filter="video" role="tab">Video</button>
      </div>
      <div class="search" role="search">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M21 21l-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        <input id="searchInput" type="search" placeholder="Search photos & videos…" aria-label="Search gallery">
      </div>
    </div>
  </section>

  <section class="gallery-wrap">
    <main class="container">
      <div id="galleryGrid" class="grid" aria-live="polite">
        <!-- Cards injected by JS -->
      </div>
    </main>
  </section>

  <!-- ===== Lightbox Viewer ===== -->
  <div class="lightbox" id="lightbox" aria-modal="true" role="dialog" aria-label="Media viewer">
    <div class="lb-content">
      <button class="control close" aria-label="Close" onclick="closeLightbox()">✕</button>
      <button class="control prev" aria-label="Previous" onclick="navLightbox(-1)">‹</button>
      <div id="lbMediaContainer"></div>
      <button class="control next" aria-label="Next" onclick="navLightbox(1)">›</button>
    </div>
  </div>

  <footer>
    <div class="footer-inner">
      <p class="muted">© <?php echo date('Y'); ?> NuAge Fitness Studios · Navy & Coral, always.</p>
    </div>
  </footer>

  <script>
    // ===== Sample Data (replace with your own assets) =====
    const items = [
      {src:'assets/gallery/facility-weights.jpg', type:'image', tag:'facilities', title:'Strength Zone'},
      {src:'assets/gallery/class-hiit.jpg', type:'image', tag:'classes', title:'HIIT Burn'},
      {src:'assets/gallery/members-strong.jpg', type:'image', tag:'members', title:'Member Spotlight'},
      {src:'assets/gallery/transform-1.jpg', type:'image', tag:'transformations', title:'8-Week Progress'},
      {src:'assets/gallery/videos/nuage-tour.mp4', type:'video', tag:'video', title:'NuAge Walkthrough'},
      {src:'assets/gallery/class-yoga.jpg', type:'image', tag:'classes', title:'Vinyasa Flow'},
      {src:'assets/gallery/facility-cardio.jpg', type:'image', tag:'facilities', title:'Cardio Deck'},
      {src:'assets/gallery/members-class.jpg', type:'image', tag:'members', title:'Group Energy'},
      {src:'assets/gallery/transform-2.jpg', type:'image', tag:'transformations', title:'Glute Gains'}
    ];

    // ===== Build Cards =====
    const grid = document.getElementById('galleryGrid');
    function makeCard(item, index){
      const fig = document.createElement('figure'); fig.className = 'card'; fig.tabIndex = 0; fig.dataset.tag = item.tag;
      fig.dataset.index = index;

      if(item.type === 'video'){
        const v = document.createElement('video'); v.src = item.src; v.controls = false; v.muted = true; v.playsInline = true; v.loop = true;
        v.setAttribute('aria-label', item.title);
        v.addEventListener('mouseenter', ()=> v.play());
        v.addEventListener('mouseleave', ()=> v.pause());
        v.addEventListener('click', ()=> openLightbox(index));
        fig.appendChild(v);
      } else {
        const img = document.createElement('img'); img.src = item.src; img.alt = item.title; img.loading = 'lazy';
        img.addEventListener('click', ()=> openLightbox(index));
        fig.appendChild(img);
      }

      const meta = document.createElement('figcaption'); meta.className = 'meta';
      meta.innerHTML = `<span>${item.title}</span><span class="tag ${item.tag==='transformations'?'coral':''}">#${item.tag}</span>`;
      fig.appendChild(meta);
      return fig;
    }
    items.forEach((it, i)=> grid.appendChild(makeCard(it, i)));

    // ===== Filters =====
    const filterBar = document.getElementById('filterBar');
    filterBar.addEventListener('click', (e)=>{
      const btn = e.target.closest('.chip'); if(!btn) return;
      document.querySelectorAll('.chip').forEach(c=>c.classList.remove('active'));
      btn.classList.add('active');
      const f = btn.dataset.filter;
      [...grid.children].forEach(card=>{
        card.style.display = (f==='all' || card.dataset.tag===f) ? '' : 'none';
      });
    });

    // ===== Search =====
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', ()=>{
      const q = searchInput.value.toLowerCase();
      [...grid.children].forEach(card=>{
        const title = card.querySelector('.meta span').textContent.toLowerCase();
        const tag = card.dataset.tag.toLowerCase();
        card.style.display = (title.includes(q) || tag.includes(q)) ? '' : 'none';
      });
    });

    // ===== Lightbox =====
    let currentIndex = -1;
    const lightbox = document.getElementById('lightbox');
    const lbContainer = document.getElementById('lbMediaContainer');

    function openLightbox(i){
      currentIndex = i;
      const it = items[i];
      lbContainer.innerHTML='';
      if(it.type==='video'){
        const v = document.createElement('video'); v.src = it.src; v.controls = true; v.autoplay = true; v.className='lb-video';
        lbContainer.appendChild(v);
      }else{
        const img = document.createElement('img'); img.src = it.src; img.alt = it.title; img.className='lb-img';
        lbContainer.appendChild(img);
      }
      lightbox.classList.add('open');
      document.body.style.overflow='hidden';
    }
    function closeLightbox(){ lightbox.classList.remove('open'); document.body.style.overflow=''; }
    function navLightbox(dir){
      if(currentIndex<0) return;
      currentIndex = (currentIndex + dir + items.length) % items.length;
      openLightbox(currentIndex);
    }
    lightbox.addEventListener('click', (e)=>{ if(e.target===lightbox) closeLightbox(); });
    document.addEventListener('keydown', (e)=>{
      if(!lightbox.classList.contains('open')) return;
      if(e.key==='Escape') closeLightbox();
      if(e.key==='ArrowLeft') navLightbox(-1);
      if(e.key==='ArrowRight') navLightbox(1);
    });
  </script>
</body>
</html>
