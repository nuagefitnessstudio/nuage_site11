<?php
// signup.php

// Handle form submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic sanitizing
    $name   = trim($_POST['name'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $phone  = trim($_POST['phone'] ?? '');
    $frequency = trim($_POST['frequency'] ?? '');
    $goal   = trim($_POST['goal'] ?? '');

    // Simple validation
    if ($name === '' || $email === '' || $phone === '' || $frequency === '' || $goal === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Prepare email
        $to      = 'info@nuagefitness-studio.com';
        $subject = 'New NuAge Fitness Studio Sign Up';
        $body    = "A new person has signed up for NuAge Fitness Studio:\n\n"
                 . "Name: {$name}\n"
                 . "Email: {$email}\n"
                 . "Phone Number: {$phone}\n"
                 . "Gym Frequency (per week): {$frequency}\n\n"
                 . "Goal they'd like to achieve:\n{$goal}\n";

        // Set headers
        $headers = "From: NuAge Fitness Studio <info@nuagefitness-studio.com>\r\n";
        $headers .= "Reply-To: {$email}\r\n";

        // Send mail
        if (mail($to, $subject, $body, $headers)) {
            $success = 'Thank you for signing up! We will contact you soon.';
            // Clear form values after successful submit
            $name = $email = $phone = $frequency = $goal = '';
        } else {
            $error = 'There was a problem sending your information. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>NuAge Fitness Studio — Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --nuage-navy: #002D72;
            --nuage-coral: #EB1F48;
            --bg-light: #f5f6fb;
            --card-bg: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: linear-gradient(135deg, #f8fbff, #ecf1ff);
            color: #1f2933;
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 720px;
            background: var(--card-bg);
            border-radius: 24px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12);
            padding: 32px 28px;
        }

        @media (min-width: 768px) {
            .card {
                padding: 40px 48px;
            }
        }

        .logo-wrap {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 12px;
        }

        .logo-wrap a {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
        }

        .logo-wrap img {
            height: 64px;
            width: auto;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-main {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            letter-spacing: 0.04em;
        }

        .brand-main span.nu {
            color: var(--nuage-navy);
        }
        .brand-main span.age {
            color: var(--nuage-coral);
        }

        .brand-sub {
            font-size: 13px;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--nuage-navy);
            margin-top: 4px;
        }

        .heading {
            margin-top: 12px;
            margin-bottom: 8px;
            font-size: 24px;
            font-weight: 600;
            color: var(--nuage-navy);
        }

        .subheading {
            font-size: 14px;
            color: #4b5563;
            margin-bottom: 24px;
        }

        .subheading strong {
            color: var(--nuage-coral);
        }

        form {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        @media (min-width: 640px) {
            form {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .full-width {
                grid-column: 1 / -1;
            }
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            color: #111827;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        select,
        textarea {
            width: 100%;
            padding: 10px 11px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.05s ease;
            background-color: #f9fafb;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--nuage-coral);
            box-shadow: 0 0 0 1px rgba(235, 31, 72, 0.08);
            transform: translateY(-1px);
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .helper-text {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .actions {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        @media (min-width: 640px) {
            .actions {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            outline: none;
            border-radius: 999px;
            padding: 10px 26px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            cursor: pointer;
            background: linear-gradient(135deg, var(--nuage-coral), #ff4b75);
            color: #ffffff;
            box-shadow: 0 10px 25px rgba(235, 31, 72, 0.5);
            transition: transform 0.12s ease, box-shadow 0.12s ease, opacity 0.12s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 30px rgba(235, 31, 72, 0.6);
        }

        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 8px 20px rgba(235, 31, 72, 0.4);
        }

        .btn-primary span.arrow {
            font-size: 16px;
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #4b5563;
            text-decoration: none;
            transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease, transform 0.05s ease;
        }

        .btn-secondary:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
            transform: translateY(-1px);
        }

        .fine-print {
            font-size: 11px;
            color: #6b7280;
        }

        .flash {
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .flash.success {
            background: #ecfdf3;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .flash.error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="card">
        <div class="logo-wrap">
            <!-- Logo now links back to index.php -->
            <a href="index.php" title="Back to Home">
                <img src="assets/IMG_2413.png" alt="NuAge Fitness Studio Logo">
                <div class="brand-text">
                    <div class="brand-main">
                        <span class="nu">Nu</span><span class="age">Age</span>
                    </div>
                    <div class="brand-sub">Fitness Studio</div>
                </div>
            </a>
        </div>

        <h1 class="heading">Sign Up for NuAge Fitness Studio</h1>
        <p class="subheading">
            Lock in your interest and get first access to updates, offers, and opening announcements.
            Tell us a bit about you and your <strong>fitness goals</strong>.
        </p>

        <?php if ($success): ?>
            <div class="flash success"><?php echo htmlspecialchars($success); ?></div>
        <?php elseif ($error): ?>
            <div class="flash error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div>
                <label for="name">Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Your full name"
                    value="<?php echo htmlspecialchars($name ?? ''); ?>"
                    required
                >
            </div>

            <div>
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="you@example.com"
                    value="<?php echo htmlspecialchars($email ?? ''); ?>"
                    required
                >
            </div>

            <div>
                <label for="phone">Number</label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    placeholder="(555) 123-4567"
                    value="<?php echo htmlspecialchars($phone ?? ''); ?>"
                    required
                >
            </div>

            <div>
                <label for="frequency">How many times a week do you go to the gym?</label>
                <select id="frequency" name="frequency" required>
                    <option value="" disabled <?php echo empty($frequency) ? 'selected' : ''; ?>>Select an option</option>
                    <option value="0 times" <?php echo (isset($frequency) && $frequency === '0 times') ? 'selected' : ''; ?>>I don't go yet</option>
                    <option value="1-2 times" <?php echo (isset($frequency) && $frequency === '1-2 times') ? 'selected' : ''; ?>>1–2 times</option>
                    <option value="3-4 times" <?php echo (isset($frequency) && $frequency === '3-4 times') ? 'selected' : ''; ?>>3–4 times</option>
                    <option value="5+ times" <?php echo (isset($frequency) && $frequency === '5+ times') ? 'selected' : ''; ?>>5+ times</option>
                </select>
            </div>

            <div class="full-width">
                <label for="goal">What is your goal you would like to achieve?</label>
                <textarea
                    id="goal"
                    name="goal"
                    placeholder="Tell us about your goals (build muscle, lose weight, get stronger, feel better, etc.)"
                    required
                ><?php echo htmlspecialchars($goal ?? ''); ?></textarea>
                <p class="helper-text">The more detail you share, the better we can tailor programs and offers to you.</p>
            </div>

            <div class="full-width actions">
                <div>
                    <button type="submit" class="btn-primary">
                        <span>Submit Sign Up</span>
                        <span class="arrow">➜</span>
                    </button>
                </div>
                <div style="display:flex; flex-direction:column; gap:6px; align-items:flex-start;">
                    <a href="index.php" class="btn-secondary">← Back to Home</a>
                    <p class="fine-print">
                        By submitting, you agree that NuAge Fitness Studio may contact you about
                        updates, offers, and opening information. Your information stays with us.
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
