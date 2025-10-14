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
    $to = "tgravesjr7@gmail.com";
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
      $from = "tgravesjr7@gmail.com"; // Best deliverability: from your domain
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
?><?php
// ================================
// Employment Application Mailer (PHPMailer SMTP)
// ================================

require __DIR__ . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['__employment_form'])) {
    // Simple sanitizers
    if (!function_exists('nuage_employ_clean')) {
        function nuage_employ_clean($s){ return trim(strip_tags($s ?? '')); }
    }
    if (!function_exists('nuage_employ_safe_email')) {
        function nuage_employ_safe_email($s){ $s = trim($s ?? ''); return filter_var($s, FILTER_VALIDATE_EMAIL) ? $s : ''; }
    }

    $app_name  = nuage_employ_clean($_POST['app_name'] ?? '');
    $app_email = nuage_employ_safe_email($_POST['app_email'] ?? '');
    $app_phone = nuage_employ_clean($_POST['app_phone'] ?? '');
    $app_role  = nuage_employ_clean($_POST['app_role'] ?? '');
    $hp_field  = trim($_POST['website'] ?? '');

    $ok = true; $errors = [];
    if ($hp_field !== '') { $ok = false; }
    if ($app_name === '') { $ok = false; $errors[] = "Name is required."; }
    if ($app_email === '') { $ok = false; $errors[] = "Valid email is required."; }
    if ($app_phone === '') { $ok = false; $errors[] = "Phone is required."; }
    if ($app_role === '') { $ok = false; $errors[] = "Position selection is required."; }

    if ($ok) {
        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tgravesjr7@gmail.com';
            $mail->Password   = 'D@ll@sC0wb0ys823!!!'; // <-- put your password or app password here
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('tgravesjr7@gmail.com', 'NuAge Careers');
            $mail->addAddress('tgravesjr7@gmail.com');
            if ($app_email) {
                $mail->addReplyTo($app_email, $app_name);
            }

            $mail->isHTML(false);
            $mail->Subject = 'New Employment Application — NuAge Fitness Studio';
            $mail->Body =
                "A new employment inquiry was submitted:\n\n" .
                "Name: {$app_name}\n" .
                "Email: {$app_email}\n" .
                "Phone: {$app_phone}\n" .
                "Position: {$app_role}\n" .
                "Submitted: " . date('Y-m-d H:i:s');

            $mail->send();
            echo "<script>window.addEventListener('DOMContentLoaded',function(){alert('Thank you — your application has been submitted!');});</script>";
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            $err = addslashes($mail->ErrorInfo ?? $e->getMessage());
            echo "<script>window.addEventListener('DOMContentLoaded',function(){alert('Mailer Error: {$err}');});</script>";
        }
    } else {
        $msg = htmlspecialchars(implode("\\n", $errors), ENT_QUOTES);
        echo "<script>window.addEventListener('DOMContentLoaded',function(){alert('Please fix the following:\\n{$msg}');});</script>";
    }
}
?>
<?php
// ================================
// Employment Application Mailer (conflict-free)
// ================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['__employment_form'])) {
    // Unique helper names to avoid collisions
    if (!function_exists('nuage_employ_clean')) {
        function nuage_employ_clean($s){ return trim(filter_var($s ?? '', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES)); }
    }
    if (!function_exists('nuage_employ_safe_email')) {
        function nuage_employ_safe_email($s){ $s = trim($s ?? ''); return filter_var($s, FILTER_VALIDATE_EMAIL) ? $s : ''; }
    }

    $app_name  = nuage_employ_clean($_POST['app_name'] ?? '');
    $app_email = nuage_employ_safe_email($_POST['app_email'] ?? '');
    $app_phone = nuage_employ_clean($_POST['app_phone'] ?? '');
    $app_role  = nuage_employ_clean($_POST['app_role'] ?? '');
    $hp_field  = trim($_POST['website'] ?? '');

    $ok = true; $errors = [];
    if ($hp_field !== '') { $ok = false; }
    if ($app_name === '') { $ok = false; $errors[] = "Name is required."; }
    if ($app_email === '') { $ok = false; $errors[] = "Valid email is required."; }
    if ($app_phone === '') { $ok = false; $errors[] = "Phone is required."; }
    if ($app_role === '') { $ok = false; $errors[] = "Position selection is required."; }

    if ($ok) {
        $to = "tgravesjr7@gmail.com";
        $subject = "New Employment Application — NuAge Fitness Studio";
        $body = "A new employment inquiry was submitted:\n\n"
              . "Name: {$app_name}\n"
              . "Email: {$app_email}\n"
              . "Phone: {$app_phone}\n"
              . "Position: {$app_role}\n"
              . "Submitted: " . date('Y-m-d H:i:s') . "\n";
        $headers = "From: NuAge Careers <no-reply@nuagefitness-studio.com>\r\n";
        if ($app_email) { $headers .= "Reply-To: {$app_email}\r\n"; }
        $headers .= "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\n";

        @mail($to, $subject, $body, $headers);
        echo "<script>window.addEventListener('DOMContentLoaded',function(){alert('Thanks! Your application was submitted. We will be in touch.');});</script>";
    } else {
        $msg = htmlspecialchars(implode("\\n", $errors), ENT_QUOTES);
        echo "<script>window.addEventListener('DOMContentLoaded',function(){alert('Please fix the following:\\n{$msg}');});</script>";
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
    body {
   letter-spacing: .01em;
   padding-top: 100px; /* push content down so it clears navbar */
   }
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
    background: var(--navy);
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

.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.6);
  display: none;              /* start hidden */
  align-items: center;
  justify-content: center;
  z-index: 2000;
}

.modal-box {
  background: #fff;
  padding: 24px;
  border-radius: 12px;
  max-width: 420px;
  width: 90%;
  text-align: center;
  position: relative;
}
.modal-actions button {
  margin: 10px;
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  background: var(--navy);
  color: #fff;
  cursor: pointer;
}
.modal-close {
  position: absolute; top: 10px; right: 10px;
  border: none; background: transparent;
  font-size: 24px; cursor: pointer;
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

  
.previews{padding:3rem 1rem;display:grid;gap:1.25rem;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));max-width:1100px;margin:0 auto}
.preview{background:#fff;border:1px solid var(--line);border-radius:1rem;overflow:hidden;display:grid;grid-template-columns:1fr}
.preview-text{padding:1rem 1.25rem}
.preview-text h2{color:var(--navy);margin:0 0 .25rem}
.preview-text .link{color:var(--coral);font-weight:600}


</style>
  <link rel="stylesheet" href="style.css?v=5" />

<style>
  
  </style>
</head>
<body>

<!-- App Download Modal -->
<div id="appModal" class="modal-overlay" style="display:none;">
  <div class="modal-box">
    <h2>Download the Glofox App</h2>
    <p>
      Please download the Glofox app, search <strong>NuAge Fitness Studio</strong> and register.<br>
      Once logged in, you’ll be able to:<br>
      • Access your account<br>
      • Purchase membership<br>
      • Book classes<br>
      • And more.
    </p>
    <div class="modal-actions">
      <button onclick="window.open('https://apps.apple.com/app/id916224471','_blank')">Apple</button>
      <button onclick="window.open('https://play.google.com/store/apps/details?id=ie.zappy.fennec.oneapp_glofox&hl=en_US','_blank')">Google</button>
    </div>
    <button class="modal-close" onclick="document.getElementById('appModal').style.display='none'">×</button>
  </div>
</div>

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
      <div class="brand-name">
        <span style="color:var(--navy);">Nu</span><span style="color:var(--coral);">Age</span>
        <span style="color:var(--navy);">Fitness</span>
        <span style="color:var(--navy);">Studios</span>
      </div>
    </div>
    <button class="drawer-close" id="navClose" aria-label="Close menu">&times;</button>
  </div>
  <nav class="drawer-nav">
    <a href="location.php" class="pill-link primary"><span style="font-weight:700">Find a Location</span></a>
    <a href="javascript:void(0)" onclick="openModal()" class="pill-link">Member Login</a>
    <a href="classes.php">Classes</a>
    <a href="team.php">Meet the Team</a>
    <a href="pricing.php">Pricing</a>
  </nav>
</aside>
  <section class="hero-ot" style="min-height:40vh">
    <div class="hero-inner">
      <h1>Meet the Team</h1>
      <p>Real coaches. Real accountability.</p>
      <div class="cta-row">
        <a class="btn btn-primary" href="classes.php">View Classes</a>
        <a class="btn btn-light" href="index.php">Back Home</a>
        <!-- Matching coral CTA for employment -->
        <a href="#apply" class="btn btn-primary" id="openJobBtn" role="button">Apply for Employment</a>
      </div>
    </div>
  </section>

<!-- Coaches -->
<section class="previews" style="background:var(--bone);padding:80px 20px;text-align:center;">
  <div class="container" style="max-width:1200px;margin:auto;">
    <h2 style="font-family:'Playfair Display',serif;color:var(--navy);margin-bottom:40px;">
      Meet Our Coaches
    </h2>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
      
      <div class="plan">
        <h3>Izeem</h3>
        <p>HIIT & Strength Coach. NASM CPT.</p>
        <p><em>“Your only limit is you.”</em></p>
        <a class="btn btn-primary" href="javascript:void(0)" onclick="openModal()">View Schedule & Book</a>
      </div>

      <div class="plan">
        <h3>K. Patel</h3>
        <p>Strength & Conditioning. CSCS.</p>
        <p><em>“Consistency compounds.”</em></p>
        <a class="btn btn-primary" href="javascript:void(0)" onclick="openModal()">View Schedule & Book</a>
      </div>

      <div class="plan">
        <h3>Natonya</h3>
        <p>Strength & Conditioning Coach</p>
        <p><em>“Train like there is no tomorrow.”</em></p>
        <a class="btn btn-primary" href="javascript:void(0)" onclick="openModal()">View Schedule & Book</a>
      </div>

      <div class="plan">
        <h3>James</h3>
        <p>Specialty: Boxing, HIT, Core & Strength</p>
        <p><em>“Your biggest enemy is you.”</em></p>
        <a class="btn btn-primary" href="javascript:void(0)" onclick="openModal()">View Schedule & Book</a>
      </div>

      <div class="plan">
        <h3>Danny</h3>
        <p>Manager</p>
        <p><em>“Push your limits.”</em></p>
        <a class="btn btn-primary" href="javascript:void(0)" onclick="openModal()">View Schedule & Book</a>
      </div>

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



  <footer class="footer">
  <div class="bottombar">
    <p>&copy; <?php echo date('Y'); ?> NuAge Fitness Studio. All rights reserved.</p>
  </div>
</footer>


<script>
  document.addEventListener("DOMContentLoaded", function () {
    const appLinks = document.querySelectorAll('a[href*="apps.apple.com/us/app/glofox"]');

    appLinks.forEach(link => {
      link.addEventListener("click", function (e) {
        e.preventDefault();

        const choice = prompt(
  "Please download the Glofox app, search NuAge Fitness Studio and register.\n" +
  "Once logged in, you’ll be able to:\n" +
  "• Access your account\n" +
  "• Purchase membership\n" +
  "• Book classes\n" +
  "• And more.\n\n" +
  "Type A for Apple\n" +
  "Type G for Google"
);

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

  // ESC key to close
  document.addEventListener("keydown", (e)=>{ if (e.key === "Escape") closeNav(); });
});
</script>



<style>
.modal { display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,.6); }
.modal-content { background:#fff; margin:10% auto; padding:20px; border-radius:12px; max-width:420px; text-align:center; position:relative; }
.modal-content h2 { color:var(--navy); margin-bottom:12px; }
.modal-content p { margin-bottom:16px; color:var(--muted); }
.modal-content ul { list-style:none; padding:0; margin:0 0 20px; text-align:left; }
.modal-content ul li { margin-bottom:8px; }
.btn-row { display:flex; gap:12px; justify-content:center; }
.btn-row button { background:var(--coral); color:#fff; padding:10px 18px; border:none; border-radius:8px; cursor:pointer; }
.close { position:absolute; right:16px; top:12px; font-size:24px; cursor:pointer; }
</style>

<script>
function openModal(){ document.getElementById("downloadModal").style.display="block"; }
function closeModal(){ document.getElementById("downloadModal").style.display="none"; }
window.onclick = function(e){
  let modal = document.getElementById("downloadModal");
  if(e.target == modal){ modal.style.display="none"; }
}
</script>


<!-- Styled Modal copied from index.php but customized for A/G input -->
<div id="appModal" class="modal">
  <div class="modal-content">
    <span class="modal-close" onclick="closeModal()">&times;</span>
    <h2>Please download the Glofox app</h2>
    <p>Search NuAge Fitness Studio and register.<br>
    Once logged in, you’ll be able to:<br>
    • Access your account<br>
    • Purchase membership<br>
    • Book classes<br>
    • And more.</p>
    <p>Type A for Apple or G for Google:</p>
    <input type="text" id="appChoice" maxlength="1" style="padding:8px;font-size:16px;">
    <button onclick="submitChoice()" style="padding:8px 16px;margin-left:8px;">Submit</button>
  </div>
</div>

<script>
function openModal(){
  document.getElementById('appModal').style.display = 'flex';
}
function closeModal(){
  document.getElementById('appModal').style.display = 'none';
}
function submitChoice(){
  const choice = document.getElementById('appChoice').value.trim().toUpperCase();
  if(choice === 'A'){
    window.open('https://apps.apple.com', '_blank');
  } else if(choice === 'G'){
    window.open('https://play.google.com', '_blank');
  } else {
    alert('Please enter A or G.');
  }
}
</script>


<!-- Employment Application Modal (conflict-free) -->
<style>
  .nuage-modal-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.5);display:none;align-items:center;justify-content:center;z-index:9999}
  .nuage-modal{background:#fff;max-width:520px;width:92%;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.25);overflow:hidden}
  .nuage-modal header{padding:18px 22px;background:#002D72;color:#fff;font-weight:700}
  .nuage-modal .content{padding:20px}
  .nuage-modal .grid{display:grid;grid-template-columns:1fr;gap:12px}
  .nuage-modal label{font-size:.9rem;color:#111418;font-weight:600;margin-bottom:6px;display:block}
  .nuage-modal input,.nuage-modal select{width:100%;padding:12px 14px;border:1.5px solid #e5e4e1;border-radius:10px;font-size:1rem;outline:none}
  .nuage-modal input:focus,.nuage-modal select:focus{border-color:#002D72}
  .nuage-actions{display:flex;gap:10px;justify-content:flex-end;padding:16px 20px;border-top:1px solid #eee;background:#fafafa}
  .nuage-btn{padding:10px 14px;border-radius:10px;border:2px solid transparent;font-weight:700;cursor:pointer}
  .nuage-btn.primary{background:#EB1F48;color:#fff}
  .nuage-btn.ghost{background:#fff;border-color:#e5e4e1}
  .nuage-hidden{display:none !important}
</style>

<div class="nuage-modal-backdrop" id="employmentModal">
  <div class="nuage-modal" role="dialog" aria-modal="true" aria-labelledby="employmentTitle">
    <header><span id="employmentTitle">Apply for Employment</span></header>
    <form method="post" class="content" id="employmentForm">
      <input type="hidden" name="__employment_form" value="1" />
      <!-- Honeypot -->
      <input type="text" name="website" autocomplete="off" class="nuage-hidden" tabindex="-1" aria-hidden="true"/>
      <div class="grid">
        <div>
          <label for="app_name">Full Name</label>
          <input id="app_name" name="app_name" type="text" placeholder="Jane Doe" required />
        </div>
        <div>
          <label for="app_phone">Phone Number</label>
          <input id="app_phone" name="app_phone" type="tel" placeholder="(555) 123-4567" required />
        </div>
        <div>
          <label for="app_email">Email Address</label>
          <input id="app_email" name="app_email" type="email" placeholder="you@example.com" required />
        </div>
        <div>
          <label for="app_role">Position</label>
          <select id="app_role" name="app_role" required>
            <option value="">Select a position…</option>
            <option>Trainer</option>
            <option>Sales</option>
            <option>Manager</option>
            <option>Instructor</option>
          </select>
        </div>
      </div>
      <div class="nuage-actions">
        <button type="button" class="nuage-btn ghost" id="closeEmployment">Cancel</button>
        <button type="submit" class="nuage-btn primary">Submit</button>
      </div>
    </form>
  </div>
</div>

<script>
(function(){
  const modal = document.getElementById('employmentModal');
  const closeBtn = document.getElementById('closeEmployment');

  function openModal(){ if(modal){ modal.style.display='flex'; document.body.style.overflow='hidden'; } }
  function closeModal(){ if(modal){ modal.style.display='none'; document.body.style.overflow=''; } }

  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (modal) modal.addEventListener('click', (e)=>{ if(e.target === modal) closeModal(); });

  // Attach to existing buttons/links that say "Apply for Employment"
  document.querySelectorAll('a,button').forEach(el=>{
    const txt = (el.innerText || el.textContent || '').trim().toLowerCase();
    if (txt.includes('apply for employment')) {
      el.addEventListener('click', function(ev){
        ev.preventDefault();
        openModal();
      }, { passive:false });
    }
  });
})();
</script>


<!-- Employment Application Modal (PHPMailer) -->
<style>
  .nuage-modal-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.5);display:none;align-items:center;justify-content:center;z-index:9999}
  .nuage-modal{background:#fff;max-width:520px;width:92%;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.25);overflow:hidden}
  .nuage-modal header{padding:18px 22px;background:#002D72;color:#fff;font-weight:700}
  .nuage-modal .content{padding:20px}
  .nuage-modal .grid{display:grid;grid-template-columns:1fr;gap:12px}
  .nuage-modal label{font-size:.9rem;color:#111418;font-weight:600;margin-bottom:6px;display:block}
  .nuage-modal input,.nuage-modal select{width:100%;padding:12px 14px;border:1.5px solid #e5e4e1;border-radius:10px;font-size:1rem;outline:none}
  .nuage-modal input:focus,.nuage-modal select:focus{border-color:#002D72}
  .nuage-actions{display:flex;gap:10px;justify-content:flex-end;padding:16px 20px;border-top:1px solid #eee;background:#fafafa}
  .nuage-btn{padding:10px 14px;border-radius:10px;border:2px solid transparent;font-weight:700;cursor:pointer}
  .nuage-btn.primary{background:#EB1F48;color:#fff}
  .nuage-btn.ghost{background:#fff;border-color:#e5e4e1}
  .nuage-hidden{display:none !important}
</style>

<div class="nuage-modal-backdrop" id="employmentModal">
  <div class="nuage-modal" role="dialog" aria-modal="true" aria-labelledby="employmentTitle">
    <header><span id="employmentTitle">Apply for Employment</span></header>
    <form method="post" class="content" id="employmentForm">
      <input type="hidden" name="__employment_form" value="1" />
      <!-- Honeypot -->
      <input type="text" name="website" autocomplete="off" class="nuage-hidden" tabindex="-1" aria-hidden="true"/>
      <div class="grid">
        <div>
          <label for="app_name">Full Name</label>
          <input id="app_name" name="app_name" type="text" placeholder="Jane Doe" required />
        </div>
        <div>
          <label for="app_phone">Phone Number</label>
          <input id="app_phone" name="app_phone" type="tel" placeholder="(555) 123-4567" required />
        </div>
        <div>
          <label for="app_email">Email Address</label>
          <input id="app_email" name="app_email" type="email" placeholder="you@example.com" required />
        </div>
        <div>
          <label for="app_role">Position</label>
          <select id="app_role" name="app_role" required>
            <option value="">Select a position…</option>
            <option>Trainer</option>
            <option>Sales</option>
            <option>Manager</option>
            <option>Instructor</option>
          </select>
        </div>
      </div>
      <div class="nuage-actions">
        <button type="button" class="nuage-btn ghost" id="closeEmployment">Cancel</button>
        <button type="submit" class="nuage-btn primary">Submit</button>
      </div>
    </form>
  </div>
</div>

<script>
(function(){
  const modal = document.getElementById('employmentModal');
  const closeBtn = document.getElementById('closeEmployment');

  function openModal(){ if(modal){ modal.style.display='flex'; document.body.style.overflow='hidden'; } }
  function closeModal(){ if(modal){ modal.style.display='none'; document.body.style.overflow=''; } }

  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (modal) modal.addEventListener('click', (e)=>{ if(e.target === modal) closeModal(); });

  // Attach to existing buttons/links that say "Apply for Employment"
  document.querySelectorAll('a,button').forEach(el=>{
    const txt = (el.innerText || el.textContent || '').trim().toLowerCase();
    if (txt.includes('apply for employment')) {
      el.addEventListener('click', function(ev){
        ev.preventDefault();
        openModal();
      }, { passive:false });
    }
  });
})();
</script>

</body>
</html>

