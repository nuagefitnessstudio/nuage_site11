<?php
// =====================================
// Employment form handler (in-page)
// =====================================
$flash_msg = null; $flash_ok = false;

function clean_text($s){ return trim(filter_var($s ?? '', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES)); }
function safe_email($s){ $s = trim($s ?? ''); return filter_var($s, FILTER_VALIDATE_EMAIL) ? $s : ''; }

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employment_form'])) {
  // Honeypot (bots fill this)
  if (!empty($_POST['company'])) {
    $flash_ok = true;
    $flash_msg = "Thanks! We received your application.";
  } else {
    $to = "info@nuagefitness-studio.com";
    $name    = clean_text($_POST['full_name'] ?? '');
    $email   = safe_email($_POST['email'] ?? '');
    $address = clean_text($_POST['address'] ?? '');

    // Basic validation
    $errors = [];
    if ($name === '')   $errors[] = "Name is required.";
    if ($email === '')  $errors[] = "Valid email is required.";
    if ($address === '')$errors[] = "Address is required.";
    if (!isset($_FILES['resume']) || ($_FILES['resume']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
      $errors[] = "Please upload your resume.";
    }

    // File checks
    $file_ok = false; $file_name = ''; $file_data_b64 = ''; $mime = '';
    if (empty($errors) && isset($_FILES['resume'])) {
      $max_bytes = 5 * 1024 * 1024; // 5MB
      $tmp = $_FILES['resume']['tmp_name'];
      $file_name = basename($_FILES['resume']['name']);
      $size = (int)($_FILES['resume']['size'] ?? 0);

      // Allow only pdf/doc/docx by extension
      $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $allowed_ext = ['pdf','doc','docx'];
      if (!in_array($ext, $allowed_ext, true)) {
        $errors[] = "Resume must be a PDF, DOC, or DOCX file.";
      } elseif ($size <= 0 || $size > $max_bytes) {
        $errors[] = "Resume file is too large (max 5MB).";
      } elseif (!is_uploaded_file($tmp)) {
        $errors[] = "Upload failed. Please try again.";
      } else {
        // Guess a MIME
        $mime_map = [
          'pdf'  => 'application/pdf',
          'doc'  => 'application/msword',
          'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        $mime = $mime_map[$ext] ?? 'application/octet-stream';
        $file_data_b64 = chunk_split(base64_encode(file_get_contents($tmp)));
        $file_ok = true;
      }
    }

    if (empty($errors) && $file_ok) {
      // Compose email with attachment
      $subject = "New Employment Application — NuAge Website";
      $boundary = "==Multipart_Boundary_x" . md5(uniqid(mt_rand(), true)) . "x";
      $from = "info@nuagefitness-studio.com"; // Best deliverability: from your domain
      $headers  = "From: NuAge Website <{$from}>\r\n";
      if ($email) $headers .= "Reply-To: {$email}\r\n";
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

      $text  = "New employment application submitted from the website.\r\n\r\n";
      $text .= "Name: {$name}\r\n";
      $text .= "Email: {$email}\r\n";
      $text .= "Address:\r\n{$address}\r\n\r\n";
      $text .= "-- End of details --\r\n";

      $body  = "This is a multi-part message in MIME format.\r\n\r\n";
      $body .= "--{$boundary}\r\n";
      $body .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
      $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
      $body .= "{$text}\r\n\r\n";

      // Attachment
      $body .= "--{$boundary}\r\n";
      $body .= "Content-Type: {$mime}; name=\"{$file_name}\"\r\n";
      $body .= "Content-Disposition: attachment; filename=\"{$file_name}\"\r\n";
      $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
      $body .= "{$file_data_b64}\r\n\r\n";
      $body .= "--{$boundary}--";

      // Use -f to set envelope sender (helps DMARC/SPF)
      $ok = @mail($to, $subject, $body, $headers, "-f {$from}");

      if ($ok) {
        $flash_ok = true;
        $flash_msg = "Thanks! Your application was sent successfully.";
      } else {
        $flash_msg = "Sorry, we couldn’t send your application. Please try again or email us at info@nuagefitness-studio.com.";
      }
    } else {
      $flash_msg = implode(" ", $errors);
    }
  }
}
?>
<!DOCTYPE html>
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
  min-height:72vh;display:grid;place-items:center;text-align:center;color:#fff;
  background: linear-gradient(180deg, var(--coral) 0%, var(--navy) 100%);
}
.hero-ot .hero-inner h1{font-size:clamp(2rem,5vw,3.5rem);margin:.25rem 0}
.hero-ot .hero-inner p{opacity:.92;margin-bottom:1rem}
.hero-ot .cta-row{display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap}

.previews{padding:3rem 1rem;display:grid;gap:1.25rem;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));max-width:1100px;margin:0 auto}
.preview{background:#fff;border:1px solid var(--line);border-radius:1rem;overflow:hidden;display:grid;grid-template-columns:1fr}
.preview-text{padding:1rem 1.25rem}
.preview-text h2{color:var(--navy);margin:0 0 .25rem}
.preview-text .link{color:var(--coral);font-weight:600}

/* extra patch to ensure headings render */
.preview{position:relative;padding:20px;box-shadow:0 6px 18px rgba(0,0,0,.08)}
.preview-text{position:relative;z-index:2;color:inherit}
.preview h2{display:block !important;visibility:visible !important;opacity:1 !important;margin:0 0 8px;font-size:24px;line-height:1.2;color:inherit}

/* ===== Employment Modal ===== */
.modal-overlay{
  position:fixed;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(2px);
  opacity:0;pointer-events:none;transition:opacity .25s ease;z-index:80;
}
.modal-overlay.show{opacity:1;pointer-events:auto}
.modal{
  position:fixed;left:50%;top:50%;transform:translate(-50%,-52%) scale(.98);
  opacity:0;pointer-events:none;transition:transform .25s ease, opacity .25s ease;
  width:min(640px,92vw);max-height:85vh;overflow:auto;background:#fff;border-radius:16px;
  box-shadow:0 20px 40px rgba(0,0,0,.25);z-index:81;padding:22px;
}
.modal.show{opacity:1;pointer-events:auto;transform:translate(-50%,-50%) scale(1)}
.modal h3{margin:0 0 10px;color:var(--navy)}
.modal p.small{color:var(--muted);margin:0 0 14px}
.modal .close{
  position:absolute;right:10px;top:8px;border:none;background:transparent;
  font-size:28px;line-height:1;color:var(--coral);cursor:pointer
}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.form-grid label{font-weight:600;color:var(--ink);font-size:.95rem}
.input, textarea, input[type="file"]{
  width:100%;padding:.75rem .8rem;border:1px solid var(--line);border-radius:.6rem;font:inherit
}
textarea{min-height:120px;resize:vertical}
.full{grid-column:1 / -1}
.alert{
  border-radius:.6rem;padding:.75rem 1rem;margin-bottom:.75rem;font-weight:600
}
.alert.success{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
.alert.error{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}
  </style>

  <!-- (There is a duplicate <style> block in your original — kept minimal above to avoid conflicts) -->
</head>
<body>
  <section class="hero-ot" style="min-height:40vh">
    <div class="hero-inner">
      <h1>Meet the Team</h1>
      <p>Real coaches. Real accountability.</p>
      <div class="cta-row">
        <a class="btn btn-primary" href="classes.php">View Classes</a>
        <a class="btn btn-light" href="index.php">Back Home</a>
        <button class="btn btn-outline" id="openJobBtn" type="button">Apply for Employment</button>
      </div>
    </div>
  </section>

  <section class="previews">
    <div class="preview">
      <div class="preview-text">
        <h2>Izeem</h2>
        <p>HIIT & Strength Coach. NASM CPT.</p>
        <p><em>“Your only limit is you.”</em></p>
      </div>
    </div>

    <div class="preview">
      <div class="preview-text">
        <h2>K. Patel</h2>
        <p>Strength & Conditioning. CSCS.</p>
        <p><em>“Consistency compounds.”</em></p>
      </div>
    </div>

    <div class="preview">
      <div class="preview-text">
        <h2>Natonya</h2>
        <p>Strength & Conditioning Coach</p>
        <p><em>“Train like there is no tomorrow.”</em></p>
      </div>
    </div>

    <div class="preview">
      <div class="preview-text">
        <h2>James</h2>
        <p>Specialty: Boxing, HIT, Core & Strength</p>
        <p><em>“Your biggest enemy is you.”</em></p>
      </div>
    </div>

    <div class="preview">
      <div class="preview-text">
        <h2>Danny</h2>
        <p>Manager</p>
        <p><em>“Push your limits.”</em></p>
      </div>
    </div>
  </section>

  <!-- ===== Employment Modal Markup ===== -->
  <div id="jobOverlay" class="modal-overlay" onclick="closeJob()"></div>
  <div id="jobModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="jobTitle" aria-hidden="true">
    <button class="close" aria-label="Close" onclick="closeJob()">&times;</button>
    <h3 id="jobTitle">Employment Application</h3>
    <p class="small">Submit your info and resume. We’ll reply from <b>info@nuagefitness-studio.com</b>.</p>

    <?php if ($flash_msg !== null): ?>
      <div class="alert <?php echo $flash_ok ? 'success' : 'error'; ?>">
        <?php echo htmlspecialchars($flash_msg); ?>
      </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" onsubmit="return lockJobSubmit(this);">
      <input type="hidden" name="employment_form" value="1">
      <!-- Honeypot (hidden to humans) -->
      <input type="text" name="company" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;opacity:0;height:0;width:0" aria-hidden="true">

      <div class="form-grid">
        <div class="full">
          <label for="full_name">Full Name</label>
          <input class="input" type="text" id="full_name" name="full_name" required>
        </div>

        <div class="full">
          <label for="email">Email</label>
          <input class="input" type="email" id="email" name="email" required>
        </div>

        <div class="full">
          <label for="address">Address</label>
          <textarea id="address" name="address" class="input" required></textarea>
        </div>

        <div class="full">
          <label for="resume">Resume (PDF/DOC/DOCX, max 5MB)</label>
          <input class="input" type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
        </div>
      </div>

      <div style="display:flex;gap:.5rem;justify-content:flex-end;margin-top:12px">
        <button type="button" class="btn btn-light" onclick="closeJob()">Cancel</button>
        <button type="submit" class="btn btn-primary" id="jobSubmitBtn">Submit Application</button>
      </div>
    </form>
  </div>

  <script>
    const modal   = document.getElementById('jobModal');
    const overlay = document.getElementById('jobOverlay');
    const openBtn = document.getElementById('openJobBtn');

    function openJob(){
      modal.classList.add('show');
      overlay.classList.add('show');
      modal.setAttribute('aria-hidden','false');
    }
    function closeJob(){
      modal.classList.remove('show');
      overlay.classList.remove('show');
      modal.setAttribute('aria-hidden','true');
    }
    function lockJobSubmit(form){
      const btn = document.getElementById('jobSubmitBtn');
      btn.disabled = true;
      btn.textContent = 'Sending...';
      return true;
    }
    if(openBtn) openBtn.addEventListener('click', openJob);

    // If PHP set a flash message, auto-open the modal so the user sees it.
    <?php if ($flash_msg !== null): ?>
      openJob();
    <?php endif; ?>
  </script>
</body>
</html>
