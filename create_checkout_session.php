<?php
// create_checkout_session.php — server endpoint to create a Stripe Checkout Session (no composer required)
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$amount_usd = isset($input['amount']) ? intval($input['amount']) : 0;
$description = isset($input['description']) ? substr(strip_tags($input['description']), 0, 120) : 'Payment';

if ($amount_usd < 1) {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid amount']);
  exit;
}

// Use env var if available; otherwise hardcode your key here (NOT in client).
$STRIPE_SECRET = getenv('STRIPE_SECRET') ?: 'pk_live_51SABsE5czUWB82DekGKNxAuMiE4S9JsP12r3ginPDrtVtir4MO2VDul0JCl2SNMvlaDlH6YF1Lv3yrZzn5gSRcIJ00IXE3LB7W';
if (strpos($STRIPE_SECRET, 'pk_live_51SABsE5czUWB82DekGKNxAuMiE4S9JsP12r3ginPDrtVtir4MO2VDul0JCl2SNMvlaDlH6YF1Lv3yrZzn5gSRcIJ00IXE3LB7W') !== false) {
  http_response_code(500);
  echo json_encode(['error' => 'Missing Stripe secret key. Set STRIPE_SECRET env or edit this file.']);
  exit;
}

$domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
$success_url = $domain . '/checkout_success.php?session_id={CHECKOUT_SESSION_ID}';
$cancel_url  = $domain . '/checkout_cancel.php';

$amount_cents = $amount_usd * 100;

$postFields = [
  'mode' => 'payment',
  'success_url' => $success_url,
  'cancel_url' => $cancel_url,
  'line_items[0][price_data][currency]' => 'usd',
  'line_items[0][price_data][product_data][name]' => $description,
  'line_items[0][price_data][unit_amount]' => $amount_cents,
  'line_items[0][quantity]' => 1,
];

$ch = curl_init('https://api.stripe.com/v1/checkout/sessions');
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => http_build_query($postFields),
  CURLOPT_HTTPHEADER => [
    'Authorization: Bearer ' . $STRIPE_SECRET,
    'Content-Type: application/x-www-form-urlencoded'
  ]
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($response === false) {
  http_response_code(500);
  echo json_encode(['error' => 'cURL error: ' . curl_error($ch)]);
  curl_close($ch);
  exit;
}
curl_close($ch);
http_response_code($httpCode);
echo $response; // includes id, url, etc.
