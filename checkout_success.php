<?php
$session_id = isset($_GET['session_id']) ? htmlspecialchars($_GET['session_id']) : '';
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Payment Successful</title>
<style>body{font-family:system-ui,Segoe UI,Roboto,Arial;margin:0;background:#f7f7fb;padding:24px}.wrap{max-width:700px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 6px 20px rgba(0,0,0,.08);padding:24px;text-align:center}.ok{color:#28a745;font-weight:800}</style>
</head><body>
<div class="wrap">
  <h1 class="ok">✅ Payment successful</h1>
  <p>Thank you! Your payment was processed.</p>
  <?php if ($session_id): ?><p><small>Session: <?php echo $session_id; ?></small></p><?php endif; ?>
  <p><a href="/pricing.php">Back to Pricing</a></p>
</div>
</body></html>
