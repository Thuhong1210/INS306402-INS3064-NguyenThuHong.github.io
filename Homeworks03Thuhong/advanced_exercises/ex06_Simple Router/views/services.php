<?php
require __DIR__ . '/_layout.php';
layoutHead('Services', $currentPage);
layoutNav($currentPage);
?>

<section class="page-hero animate">
  <div class="hero-icon green">⚙️</div>
  <div>
    <h1>Services</h1>
    <p>Explore what this Front Controller approach enables — from clean routing to secure view loading.</p>
  </div>
</section>

<div class="info-box info animate delay-1" style="margin-bottom: 28px;">
  <div class="icon">🔗</div>
  <div>Route: <code>index.php?page=services</code> → loaded <code>views/services.php</code></div>
</div>

<p class="section-title animate delay-1">Core Router Features</p>
<div class="grid grid-2 animate delay-1" style="margin-bottom: 28px;">
  <div class="card">
    <div class="card-icon">🛡️</div>
    <h3>Input Sanitisation</h3>
    <p>The <code>?page=</code> value is lowercased and stripped of non-alphabetic characters using <code>preg_replace</code> before any file reference.</p>
  </div>
  <div class="card">
    <div class="card-icon">📋</div>
    <h3>Allow-list Routing</h3>
    <p>Only pages in <code>$allowedPages</code> array are served. Everything else triggers a 404 — no guessing or wildcard matching.</p>
  </div>
  <div class="card">
    <div class="card-icon">📂</div>
    <h3>View Isolation</h3>
    <p>Views live in their own <code>views/</code> directory. The controller passes data via <code>extract()</code> — clean separation of concerns.</p>
  </div>
  <div class="card">
    <div class="card-icon">🔁</div>
    <h3>PRG-Ready</h3>
    <p>The Front Controller layout supports Post-Redirect-Get easily — just redirect back to <code>index.php?page=X</code> after form processing.</p>
  </div>
  <div class="card">
    <div class="card-icon">🚀</div>
    <h3>Zero Dependencies</h3>
    <p>Pure PHP. No Composer, no framework, no external libraries. Runs on any XAMPP/WAMP/LAMP stack with PHP 8+.</p>
  </div>
  <div class="card">
    <div class="card-icon">📐</div>
    <h3>Extensible Design</h3>
    <p>Add a new page by: (1) adding slug to <code>$allowedPages</code>, and (2) creating <code>views/slug.php</code>. That's it.</p>
  </div>
</div>

<p class="section-title animate delay-2">How to Add a New Route</p>
<div class="code-block animate delay-2">
<span class="cmt">// Step 1 – in index.php, add to the allow-list:</span><br>
<span class="var">$allowedPages</span> = [<span class="str">'home'</span>, <span class="str">'about'</span>, ..., <span class="str">'pricing'</span>]; <span class="cmt">// ← add here</span><br>
<br>
<span class="cmt">// Step 2 – create views/pricing.php:</span><br>
<span class="kw">&lt;?php</span><br>
<span class="fn">require</span> <span class="var">__DIR__</span> . <span class="str">'/_layout.php'</span>;<br>
<span class="fn">layoutHead</span>(<span class="str">'Pricing'</span>, <span class="var">$currentPage</span>);<br>
<span class="fn">layoutNav</span>(<span class="var">$currentPage</span>);<br>
<span class="cmt">// ... your HTML content ...</span><br>
<span class="fn">layoutFoot</span>();<br>
<span class="kw">?&gt;</span>
</div>

<?php layoutFoot(); ?>
