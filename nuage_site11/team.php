<?php
// === Injected: helper to enforce Microsoft 365 SMTP config (idempotent) ===
if (!function_exists('__configure_ms365_smtp')) {
    function __configure_ms365_smtp($mail) {
        if (!is_object($mail)) return;

        $host = getenv('SMTP_HOST') ?: 'smtp.office365.com';
        $port = (int)(getenv('SMTP_PORT') ?: 587);
        $user = getenv('SMTP_USERNAME') ?: '';
        $pass = getenv('SMTP_PASSWORD') ?: '';
        $enc  = strtolower(getenv('SMTP_ENCRYPTION') ?: 'tls');

        error_log(sprintf('[smtp] host=%s port=%d enc=%s user=%s pass=%s',
            $host, $port, $enc, $user ? 'set('.strlen($user).')' : 'EMPTY', $pass ? 'SET' : 'EMPTY'
        ));

        $mail->isSMTP();
// Injected: enforce Microsoft 365 SMTP config without changing your flow
__configure_ms365_smtp($mail);

        $mail->Host        = $host;
        $mail->Port        = $port;
        $mail->SMTPAuth    = true;
        $mail->AuthType    = 'LOGIN';
        $mail->SMTPAutoTLS = true;
        $mail->SMTPSecure  = PHPMailer::ENCRYPTION_STARTTLS;

        if ($user !== '') $mail->Username = $user;
        if ($pass !== '') $mail->Password = $pass;

        if (method_exists($mail, 'setFrom') && $user !== '') {
            $rf = (property_exists($mail, 'From') ? $mail->From : '');
            if (!$rf || strtolower($rf) != strtolower($user)) {
                $mail->setFrom($user, 'NuAge Careers');
            }
            if (property_exists($mail, 'Sender') && (!$mail->Sender || strtolower($mail->Sender) != strtolower($user))) {
                $mail->Sender = $user;
            }
        }
    }
}
// === End injected helper ===

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Robust Composer autoloader with clear diagnostics
$candidates = [
  __DIR__ . '/vendor/autoload.php',        // if vendor sits next to this file
  __DIR__ . '/../vendor/autoload.php',     // if vendor is one level up
  '/var/www/html/vendor/autoload.php',     // absolute path (your container docroot)
];

$found = null;
foreach ($candidates as $p) {
  if (is_file($p)) { $found = $p; break; }
}

if ($found) {
  require_once $found;
} else {
  // EXTRA diagnostics (helps you see what's actually there)
  error_log("[autoload] tried: " . implode(' | ', $candidates));
  $here = glob(__DIR__ . '/vendor/*', GLOB_ONLYDIR) ?: [];
  $up   = glob(dirname(__DIR__) . '/vendor/*', GLOB_ONLYDIR) ?: [];
  error_log("[autoload] listing __DIR__/vendor: " . json_encode(array_map('basename', $here)));
  error_log("[autoload] listing ../vendor: " . json_encode(array_map('basename', $up)));
  http_response_code(500);
  exit('Server misconfiguration: dependencies missing.');
}
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
<?php
// ================================
// Employment Application Mailer (PHPMailer over Microsoft 365)
// ================================


require __DIR__ . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['__employment_form'])) {
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
    if ($hp_field !== '') { $ok = false; } // honeypot
    if ($app_name === '')  { $ok = false; $errors[] = "Name is required."; }
    if ($app_email === '') { $ok = false; $errors[] = "Valid email is required."; }
    if ($app_phone === '') { $ok = false; $errors[] = "Phone is required."; }
    if ($app_role === '')  { $ok = false; $errors[] = "Position selection is required."; }

    if ($ok) {
        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = getenv('SMTP_HOST') ?: 'smtp.office365.com';
            $mail->SMTPAuth = true;
            $mail->SMTPAutoTLS = true;
            $mail->SMTPKeepAlive = false;

            $mail->Username   = ( getenv('SMTP_USERNAME') ?: 'info@nuagefitness-studio.com' );
            $mail->Password   = ( getenv('SMTP_PASSWORD') ?: 'Nuagefitness24#' ); // <-- set your M365 password or app password
            $mail->SMTPSecure = ( getenv('SMTP_ENCRYPTION') ?: \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS );
            $mail->Port       = (int)(getenv('SMTP_PORT') ?: 587);

            $mail->Sender = ( getenv('SMTP_USERNAME') ?: 'info@nuagefitness-studio.com' );
            $mail->setFrom( ( getenv('SMTP_FROM') ?: (getenv('SMTP_USERNAME') ?: 'info@nuagefitness-studio.com') ), 'NuAge Careers');
            $mail->addAddress( getenv('SMTP_USERNAME') ?: 'info@nuagefitness-studio.com' );
            if ($app_email) { $mail->addReplyTo($app_email, $app_name); }

            $mail->isHTML(false);
            $mail->Subject = 'New Employment Application — NuAge Fitness Studio';
            $mail->Body =
                "A new employment inquiry was submitted:\n\n"
                . "Name: {$app_name}\n"
                . "Email: {$app_email}\n"
                . "Phone: {$app_phone}\n"
                . "Position: {$app_role}\n"
                . "Submitted: " . date('Y-m-d H:i:s') . "\n";
                error_log('SMTP cfg host='.getenv('SMTP_HOST').' port='.getenv('SMTP_PORT').' enc='.getenv('SMTP_ENCRYPTION'));
                $u = getenv('SMTP_USERNAME'); $p = getenv('SMTP_PASSWORD');
                error_log('SMTP user='.($u ? 'set('.strlen($u).')' : 'EMPTY').' pass='.($p ? 'SET' : 'EMPTY'));
            $ok__injected = $mail->send();
error_log('PHPMailer send result: ' . ($ok__injected ? 'OK' : $mail->ErrorInfo));
            echo "<script>window.addEventListener('DOMContentLoaded',function(){alert('Thank you — your application has been submitted!');});</script>";
        } catch (Exception $e) {
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
// Employment Application Mailer (PHPMailer SMTP)
// ================================



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
            $mail->Host       = getenv('SMTP_HOST') ?: 'smtp.office365.com';
            $mail->SMTPAuth = true;
            $mail->SMTPAutoTLS = true;
            $mail->SMTPKeepAlive = false;

            $mail->Username   = ( getenv('SMTP_USERNAME') ?: 'info@nuagefitness-studio.com' );
            $mail->Password   = ( getenv('SMTP_PASSWORD') ?: 'Nuagefitness24#' ); // <-- put your password or app password here
            $mail->SMTPSecure = ( getenv('SMTP_ENCRYPTION') ?: \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS );
            $mail->Port       = (int)(getenv('SMTP_PORT') ?: 587);

            $mail->Sender = ( getenv('SMTP_USERNAME') ?: 'info@nuagefitness-studio.com' );
            $mail->setFrom( ( getenv('SMTP_FROM') ?: (getenv('SMTP_USERNAME') ?: 'info@nuagefitness-studio.com') ), 'NuAge Careers');
            $mail->addAddress( getenv('SMTP_USERNAME') ?: 'info@nuagefitness-studio.com' );
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
        } catch (Exception $e) {
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
        $to = "info@nuagefitness-studio.com";
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

<style id="employmentModalStyles">
  /* Backdrop */
  #employmentModal.nuage-modal-backdrop{
    position:fixed; inset:0; display:none; /* shown by JS */
    align-items:center; justify-content:center;
    padding:16px; background:rgba(0,0,0,.5); z-index:9999;
  }

  /* Modal shell */
  #employmentModal .nuage-modal{
    background:#fff; width:min(680px, 94vw);
    max-height:90vh; border-radius:16px;
    box-shadow:0 20px 60px rgba(0,0,0,.25);
    display:flex; flex-direction:column; overflow:hidden;
  }

  #employmentModal header{
    padding:14px 18px; background:#002D72; color:#fff; font-weight:700;
  }

  /* Make JUST the inner form scroll, not the header or buttons */
  #employmentModal .content{
    flex:1; overflow-y:auto; padding:16px 18px 20px;
  }

  /* 2-column on desktop, 1-column on mobile */
  #employmentModal .grid{ display:grid; gap:12px; grid-template-columns:1fr 1fr; }
  @media (max-width:680px){ #employmentModal .grid{ grid-template-columns:1fr; } }

  /* Keep the action bar visible while body scrolls */
  #employmentModal .nuage-actions{
    position:sticky; bottom:0; background:#fff;
    border-top:1px solid #eee; padding:12px 16px;
    display:flex; gap:10px; justify-content:flex-end;
  }

  /* Optional: cap the tall checkbox sets so they scroll inside */
  #employmentModal .nuage-fieldset{
    border:1px solid #e5e7eb; border-radius:8px;
    padding:8px 10px; max-height:180px; overflow:auto;
  }
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

<!-- Employment Modal -->
<div class="nuage-actions">
        <button type="button" class="nuage-btn ghost" id="closeEmployment">Cancel</button>
        <button type="submit" class="nuage-btn primary">Submit</button>
      </div>
    </form>
  </div>
</div>

<script>
  // Map your app_* inputs to the hidden standard names so either backend works.
  document.getElementById('employmentForm').addEventListener('submit', function () {
    document.getElementById('std_full_name').value = document.getElementById('app_name').value;
    document.getElementById('std_phone').value     = document.getElementById('app_phone').value;
    document.getElementById('std_email').value     = document.getElementById('app_email').value;
    document.getElementById('std_position').value  = document.getElementById('app_role').value;
  });
</script>


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


<!-- Employment Application Modal (hooks any "Apply for Employment" trigger) -->
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

  // Auto-hook any link or button that says "Apply for Employment"
  document.querySelectorAll('a,button').forEach(el=>{
    const txt = (el.innerText || el.textContent || '').trim().toLowerCase();
    if (txt.includes('apply for employment')) {
      el.addEventListener('click', function(ev){ ev.preventDefault(); openModal(); }, { passive:false });
    }
  });
})();
</script>


<!-- === Employment Modal (popup) === -->
<div id="nuage-emp-backdrop" aria-hidden="true" style="position:fixed;inset:0;background:rgba(0,0,0,.5);display:none;align-items:center;justify-content:center;z-index:9999">
  <div id="nuage-emp-modal" role="dialog" aria-modal="true" aria-labelledby="nuage-emp-title" style="background:#fff;max-width:540px;width:92%;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.25);overflow:hidden">
    <header style="padding:16px 20px;background:#002D72;color:#fff;font-weight:700"><span id="nuage-emp-title">Apply for Employment</span></header>
    <form method="post" id="nuage-emp-form" class="content" style="padding:20px">
      <input type="hidden" name="__employment_form" value="1" />
      <input type="text" name="website" autocomplete="off" style="display:none" tabindex="-1" aria-hidden="true"/>
      <div class="grid" style="display:grid;grid-template-columns:1fr;gap:12px">
        <div><label for="emp_name" style="font-weight:600">Full Name</label><input id="emp_name" name="app_name" type="text" required style="width:100%;padding:12px 14px;border:1.5px solid #e5e4e1;border-radius:10px"></div>
        <div><label for="emp_phone" style="font-weight:600">Phone Number</label><input id="emp_phone" name="app_phone" type="tel" required style="width:100%;padding:12px 14px;border:1.5px solid #e5e4e1;border-radius:10px"></div>
        <div><label for="emp_email" style="font-weight:600">Email Address</label><input id="emp_email" name="app_email" type="email" required style="width:100%;padding:12px 14px;border:1.5px solid #e5e4e1;border-radius:10px"></div>
        <div>
          <label for="emp_role" style="font-weight:600">Position</label>
          <select id="emp_role" name="app_role" required style="width:100%;padding:12px 14px;border:1.5px solid #e5e4e1;border-radius:10px">
            <option value="">Select a position…</option>
            <option>Trainer</option>
            <option>Sales</option>
            <option>Manager</option>
            <option>Instructor</option>
          </select>
        </div>
      </div>
      <div class="actions" style="display:flex;gap:10px;justify-content:flex-end;padding:16px 0 0;margin-top:12px;border-top:1px solid #eee">
        <button type="button" id="nuage-emp-close" style="padding:10px 14px;border-radius:10px;border:2px solid #e9e6e1;background:#fff;cursor:pointer">Cancel</button>
        <button type="submit" style="padding:10px 14px;border-radius:10px;border:2px solid transparent;background:#EB1F48;color:#fff;font-weight:700;cursor:pointer">Submit</button>
      </div>
    </form>
  </div>
</div>
<script>
(function(){
  const b = document.getElementById('nuage-emp-backdrop');
  const c = document.getElementById('nuage-emp-close');
  function openM(){b.style.display='flex';document.body.style.overflow='hidden';b.setAttribute('aria-hidden','false');}
  function closeM(){b.style.display='none';document.body.style.overflow='';b.setAttribute('aria-hidden','true');}
  // Your existing Apply button just needs: data-open-employment
  document.querySelectorAll('[data-open-employment]').forEach(el=>{el.addEventListener('click',e=>{e.preventDefault();openM();});});
  if (c) c.addEventListener('click',closeM);
  if (b) b.addEventListener('click',e=>{if(e.target===b) closeM();});
})();
</script>


<!-- Employment Modal -->
<div class="nuage-modal-backdrop" id="employmentModal">
  <div class="nuage-modal" role="dialog" aria-modal="true" aria-labelledby="employmentTitle">
    <header><span id="employmentTitle">Apply for Employment</span></header>

    <form method="post" class="content" id="employmentForm" enctype="multipart/form-data">
      <input type="hidden" name="__employment_form" value="1" />
      <!-- honeypot -->
      <input type="text" name="website" autocomplete="off" class="nuage-hidden" tabindex="-1" aria-hidden="true"/>

      <!-- Injected fields: keep names aligned with server handler -->
      <!-- Basic contact -->
      <div class="mb-3">
        <label class="form-label">Full Name*</label>
        <input class="form-control" name="full_name" required>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Phone Number</label>
          <input class="form-control" name="phone" type="tel" placeholder="(555) 123-4567">
        </div>
        <div class="col-md-6">
          <label class="form-label">Email Address*</label>
          <input class="form-control" name="email" type="email" required>
        </div>
      </div>

      <div class="mb-3 mt-3">
        <label class="form-label">Position*</label>
        <select class="form-select" name="position" required>
          <option value="" selected disabled>Select a position…</option>
          <option>Instructor</option>
          <option>Front Desk</option>
          <option>Coach</option>
        </select>
      </div>

      <!-- Resume / Cover Letter -->
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Resume/CV* (upload)</label>
          <input class="form-control" type="file" name="resume" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
          <small class="text-muted d-block mt-1">or paste below</small>
          <textarea class="form-control mt-2" name="resume_text" rows="5" placeholder="Paste resume text here"></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Cover Letter (upload)</label>
          <input class="form-control" type="file" name="cover_letter" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
          <small class="text-muted d-block mt-1">or paste below</small>
          <textarea class="form-control mt-2" name="cover_letter_text" rows="5" placeholder="Optional"></textarea>
        </div>
      </div>

      <!-- Job history -->
      <div class="row g-3 mt-3">
        <div class="col-md-6">
          <label class="form-label">Recent Job Title</label>
          <input class="form-control" name="recent_job_title">
        </div>
        <div class="col-md-6">
          <label class="form-label">Recent Employer</label>
          <input class="form-control" name="recent_employer">
        </div>
      </div>

      <!-- Age 18+ -->
      <div class="mt-3">
        <label class="form-label">Are you at least 18 years old?*</label>
        <select class="form-select" name="age_over_18" required>
          <option value="" selected disabled>Select…</option>
          <option>Yes</option>
          <option>No</option>
        </select>
      </div>

      <!-- Certifications -->
      <fieldset class="mt-3">
        <legend class="fs-6">Personal Training Certifications*</legend>
        <div class="row">
          <div class="col-6 col-md-4">
            <div class="form-check"><input class="form-check-input" type="checkbox" name="certifications[]" value="ACE PT" id="c1"><label class="form-check-label" for="c1">ACE PT</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="certifications[]" value="ACE GFI" id="c2"><label class="form-check-label" for="c2">ACE GFI</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="certifications[]" value="NASM CPT" id="c3"><label class="form-check-label" for="c3">NASM CPT</label></div>
          </div>
          <div class="col-6 col-md-4">
            <div class="form-check"><input class="form-check-input" type="checkbox" name="certifications[]" value="AFFA GFI" id="c4"><label class="form-check-label" for="c4">AFFA GFI</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="certifications[]" value="NSCA CPT" id="c5"><label class="form-check-label" for="c5">NSCA CPT</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="certifications[]" value="NSCA CSCS" id="c6"><label class="form-check-label" for="c6">NSCA CSCS</label></div>
          </div>
          <div class="col-12 col-md-4">
            <div class="form-check"><input class="form-check-input" type="checkbox" name="certifications[]" value="ACSM CPT" id="c7"><label class="form-check-label" for="c7">ACSM CPT</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="certifications[]" value="ACSM GEI" id="c8"><label class="form-check-label" for="c8">ACSM GEI</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="certifications[]" value="ACSM CEP" id="c9"><label class="form-check-label" for="c9">ACSM CEP</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="certifications[]" value="None / willing to obtain" id="c10"><label class="form-check-label" for="c10">None of the above, but willing to obtain</label></div>
          </div>
        </div>
      </fieldset>

      <!-- Experience -->
      <div class="mt-3">
        <label class="form-label">Do you have experience in personal or group training?*</label>
        <textarea class="form-control" name="experience" rows="4" required></textarea>
      </div>

      <!-- CPR/AED -->
      <div class="mt-3">
        <label class="form-label">Do you have a CPR/AED/First Aid Certification?*</label>
        <select class="form-select" name="cpr_cert" required>
          <option value="" selected disabled>Select…</option>
          <option>Yes</option>
          <option>No</option>
          <option>No, but willing to obtain</option>
        </select>
      </div>

      <!-- Attended before -->
      <div class="mt-3">
        <label class="form-label">Have you attended an NuAge class before?*</label>
        <select class="form-select" name="attended_otf" required>
          <option value="" selected disabled>Select…</option>
          <option>Yes</option>
          <option>No</option>
        </select>
      </div>

      <!-- Availability -->
      <fieldset class="mt-3">
        <legend class="fs-6">Please indicate your availability (select all that apply)*</legend>
        <div class="row">
          <div class="col-sm-6 col-md-4">
            <div class="form-check"><input class="form-check-input" type="checkbox" name="availability[]" value="Open Availability" id="a1"><label class="form-check-label" for="a1">Open Availability</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="availability[]" value="Weekday Mornings" id="a2"><label class="form-check-label" for="a2">Weekday Mornings</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="availability[]" value="Weekday Afternoons" id="a3"><label class="form-check-label" for="a3">Weekday Afternoons</label></div>
          </div>
          <div class="col-sm-6 col-md-4">
            <div class="form-check"><input class="form-check-input" type="checkbox" name="availability[]" value="Weekday Evenings" id="a4"><label class="form-check-label" for="a4">Weekday Evenings</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="availability[]" value="Weekend Mornings" id="a5"><label class="form-check-label" for="a5">Weekend Mornings</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="availability[]" value="Weekend Afternoons" id="a6"><label class="form-check-label" for="a6">Weekend Afternoons</label></div>
          </div>
        </div>
      </fieldset>

      <!-- Referred -->
      <div class="form-check mt-3">
        <input class="form-check-input" type="checkbox" id="ref1" name="referred_by_employee" value="Yes">
        <label class="form-check-label" for="ref1">I was referred to this position by a current employee</label>
      </div>

      <div class="nuage-actions">
        <button type="button" class="nuage-btn ghost" id="closeEmployment">Cancel</button>
        <button type="submit" class="nuage-btn primary">Submit</button>
      </div>
    </form>
  </div>
</div>


<script id="employmentModalJS">
  document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('employmentModal');
    const closeBtn = document.getElementById('closeEmployment');

    const open = (e)=>{ if(e) e.preventDefault(); modal.style.display='flex'; document.body.style.overflow='hidden'; };
    const close = (e)=>{ if(e) e.preventDefault(); modal.style.display='none'; document.body.style.overflow=''; };

    if (closeBtn) closeBtn.addEventListener('click', close);
    if (modal) modal.addEventListener('click', (e)=>{ if (e.target === modal) close(e); });
    window.addEventListener('keydown', (e)=>{ if (e.key === 'Escape') close(e); });

    // Wire up your “Apply for Employment” button(s)
    // Either give your CTA id="employmentButton" OR this will auto-detect by text
    const btn = document.getElementById('employmentButton');
    if (btn) btn.addEventListener('click', open);
    else {
      document.querySelectorAll('a,button').forEach(el=>{
        const t = (el.innerText||el.textContent||'').trim().toLowerCase();
        if (t.includes('apply for employment')) el.addEventListener('click', open);
      });
    }
  });
</script>

</body>
</html>
