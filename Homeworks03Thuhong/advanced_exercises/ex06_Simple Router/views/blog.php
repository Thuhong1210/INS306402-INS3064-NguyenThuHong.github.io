<?php
require __DIR__ . '/_layout.php';
layoutHead('Blog', $currentPage);
layoutNav($currentPage);

// Static blog post data (simulates a Model returning data)
$posts = [
    [
        'badge'   => 'Tutorial',
        'color'   => 'badge-blue',
        'date'    => '2026-03-01',
        'title'   => 'PHP Front Controller Pattern Explained',
        'excerpt' => 'A Front Controller centralises all HTTP request handling through one entry point. This post walks through how index.php acts as the dispatcher for an entire application.',
        'readTime'=> '4 min read',
    ],
    [
        'badge'   => 'Architecture',
        'color'   => 'badge-purple',
        'date'    => '2026-02-20',
        'title'   => 'MVC Without a Framework',
        'excerpt' => 'You don\'t need Laravel to use MVC. Learn how to separate Models, Views, and Controllers using plain PHP — keeping your code clean and testable.',
        'readTime'=> '6 min read',
    ],
    [
        'badge'   => 'Security',
        'color'   => 'badge-orange',
        'date'    => '2026-02-10',
        'title'   => 'Preventing Path Traversal in PHP Routers',
        'excerpt' => 'When routing by file name, always sanitise input and use an allow-list. Here\'s why preg_replace + in_array() is the safest combination.',
        'readTime'=> '3 min read',
    ],
    [
        'badge'   => 'Tips',
        'color'   => 'badge-green',
        'date'    => '2026-01-28',
        'title'   => 'Query Parameters vs Pretty URLs',
        'excerpt' => '?page=about is simple but readable. With .htaccess you can rewrite /about to ?page=about — same Front Controller, cleaner URLs.',
        'readTime'=> '5 min read',
    ],
];
?>

<section class="page-hero animate">
  <div class="hero-icon orange">📝</div>
  <div>
    <h1>Blog</h1>
    <p>Articles about PHP routing, MVC patterns, and web development best practices. Data is static (simulates a Model).</p>
  </div>
</section>

<div class="info-box info animate delay-1" style="margin-bottom:28px;">
  <div class="icon">💡</div>
  <div>
    In a real MVC app, a <strong>Model</strong> would fetch these posts from a database.
    Here the <code>$posts</code> array inside <code>views/blog.php</code> simulates that — notice there's no SQL in the controller.
  </div>
</div>

<p class="section-title animate delay-1">Latest Posts</p>
<div class="grid animate delay-1" style="margin-bottom: 28px;">
  <?php foreach ($posts as $i => $post): ?>
  <div class="card" style="animation-delay: <?= $i * 0.07 ?>s;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
      <span class="badge <?= htmlspecialchars($post['color'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($post['badge'], ENT_QUOTES, 'UTF-8') ?></span>
      <span style="color:var(--txt-muted); font-size:12px;"><?= htmlspecialchars($post['date'], ENT_QUOTES, 'UTF-8') ?> · <?= htmlspecialchars($post['readTime'], ENT_QUOTES, 'UTF-8') ?></span>
    </div>
    <h3 style="font-size:15px; margin-bottom:8px;"><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h3>
    <p><?= htmlspecialchars($post['excerpt'], ENT_QUOTES, 'UTF-8') ?></p>
  </div>
  <?php endforeach; ?>
</div>

<?php layoutFoot(); ?>
