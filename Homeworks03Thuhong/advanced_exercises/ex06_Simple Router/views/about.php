<?php
require __DIR__ . '/_layout.php';
layoutHead('About', $currentPage);
layoutNav($currentPage);
?>

<section class="page-hero animate">
  <div class="hero-icon purple">👤</div>
  <div>
    <h1>About SimpleRouter</h1>
    <p>Learn the key concepts behind the Front Controller pattern and how PHP routing works without any framework.</p>
  </div>
</section>

<div class="info-box success animate delay-1" style="margin-bottom:28px;">
  <div class="icon">✅</div>
  <div>You reached this page via: <code>index.php?page=about</code> — the Front Controller loaded <code>views/about.php</code>.</div>
</div>

<p class="section-title animate delay-1">What is a Front Controller?</p>
<div class="grid grid-2 animate delay-1" style="margin-bottom:28px;">
  <div class="card">
    <div class="card-icon">🚦</div>
    <h3>Single Entry Point</h3>
    <p>All HTTP requests go through one file — <code>index.php</code>. This centralises authentication, logging, and error handling.</p>
  </div>
  <div class="card">
    <div class="card-icon">🗺️</div>
    <h3>Routing Logic</h3>
    <p>The controller reads query parameters, validates them, and delegates to the right view or action — like a traffic director.</p>
  </div>
  <div class="card">
    <div class="card-icon">🔒</div>
    <h3>Security Benefits</h3>
    <p>Input is sanitised before any file is loaded. Using an allow-list prevents directory traversal and file-injection attacks.</p>
  </div>
  <div class="card">
    <div class="card-icon">🏗️</div>
    <h3>Foundation of MVC</h3>
    <p>Frameworks like Laravel, Symfony, and CodeIgniter all use this pattern internally — every request hits <code>public/index.php</code>.</p>
  </div>
</div>

<p class="section-title animate delay-2">MVC Layers in This Demo</p>
<div class="grid grid-3 animate delay-2" style="margin-bottom: 28px;">
  <div class="card" style="border-color: rgba(88,166,255,.35);">
    <div class="card-icon">🎮</div>
    <h3 style="color: var(--primary);">Controller</h3>
    <p><code>index.php</code><br>Reads input, validates, dispatches to view.</p>
  </div>
  <div class="card" style="border-color: rgba(188,140,255,.35);">
    <div class="card-icon">🖼️</div>
    <h3 style="color: var(--accent);">View</h3>
    <p><code>views/*.php</code><br>Renders HTML for each page. No business logic.</p>
  </div>
  <div class="card" style="border-color: rgba(63,185,80,.35);">
    <div class="card-icon">🗄️</div>
    <h3 style="color: var(--green);">Model</h3>
    <p><em>Not needed</em> for this static demo, but would handle database, data classes &amp; business rules.</p>
  </div>
</div>

<p class="section-title animate delay-3">File Structure</p>
<div class="code-block animate delay-3">
<span class="var">ex06_Simple Router/</span><br>
├── <span class="fn">index.php</span>          <span class="cmt">← Front Controller (router)</span><br>
└── views/<br>
&nbsp;&nbsp;&nbsp;&nbsp;├── <span class="str">_layout.php</span>    <span class="cmt">← Shared layout (head, nav, footer)</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;├── <span class="str">home.php</span>       <span class="cmt">← Home view</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;├── <span class="str">about.php</span>      <span class="cmt">← About view  ← YOU ARE HERE</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;├── <span class="str">services.php</span>   <span class="cmt">← Services view</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;├── <span class="str">blog.php</span>       <span class="cmt">← Blog view</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;├── <span class="str">contact.php</span>    <span class="cmt">← Contact view</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;└── <span class="str">404.php</span>        <span class="cmt">← 404 Not Found view</span>
</div>

<?php layoutFoot(); ?>
