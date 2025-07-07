<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: admin_login.html");
  exit();
}

$file = 'about-structured.json';

// Load content from JSON or use default
$defaultContent = [
  'about_title' => 'About Miraé',
  'about_intro' => 'Miraé is a premium skincare brand devoted to enhancing your natural beauty through gentle, effective, and scientifically-backed products.',
  'mission' => 'To help people embrace their natural glow with skincare that’s clean, powerful, and accessible. We believe beauty starts with self-love and confidence.',
  'story' => 'Founded by a team of beauty experts and skincare scientists, Miraé was born from the desire to simplify skincare without compromising results. Our journey began in a small lab with a bold vision: to make skincare effective, inclusive, and deeply nourishing.',
  'promise' => [
    'Clean, cruelty-free ingredients',
    'Dermatologist-tested formulas',
    'Designed for all skin types',
    'Eco-conscious packaging'
  ],
  'why' => 'Because your skin deserves the best. From our gentle cleansers to our nourishing serums, every product is crafted with care, transparency, and love.'
];

$content = file_exists($file) ? json_decode(file_get_contents($file), true) : $defaultContent;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>About Miraé</title>
  <link rel="stylesheet" href="adminDash.css">
  <style>
    .main-content {
      padding: 20px;
      flex-grow: 1;
    }
    h1, h2 {
      margin-top: 25px;
      color: #333;
    }
    p {
      margin: 12px 0;
      line-height: 1.6;
    }
    ul {
      margin-top: 10px;
      margin-bottom: 20px;
      padding-left: 20px;
    }
    li {
      margin-bottom: 6px;
    }
    .toggle-btn {
      position: absolute;
      top: 15px;
      left: 15px;
      background: #333;
      color: white;
      padding: 10px 15px;
      font-size: 18px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<div class="dashboard-container">
  <?php include 'adminHeader.php'; ?>
  <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

  <div class="main-content">
    <h1><?= htmlspecialchars($content['about_title']) ?></h1>
    <p><?= nl2br(htmlspecialchars($content['about_intro'])) ?></p>

    <h2>Our Mission</h2>
    <p><?= nl2br(htmlspecialchars($content['mission'])) ?></p>

    <h2>Our Story</h2>
    <p><?= nl2br(htmlspecialchars($content['story'])) ?></p>

    <h2>Our Promise</h2>
    <ul>
      <?php foreach ($content['promise'] as $item): ?>
        <li>✔️ <?= htmlspecialchars($item) ?></li>
      <?php endforeach; ?>
    </ul>

    <h2>Why Choose Miraé?</h2>
    <p><?= nl2br(htmlspecialchars($content['why'])) ?></p>
  </div>
</div>

<script>
function toggleSidebar() {
  const sidebar = document.querySelector('.sidebar');
  if (sidebar) sidebar.classList.toggle('collapsed');
}
</script>
</body>
</html>
