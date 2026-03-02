<?php
require __DIR__ . '/_layout.php';
layoutHead('Home', $currentPage);
layoutNav($currentPage);
?>

<section class="page-hero animate">
  <div class="hero-icon blue">🏠</div>
  <div>
    <h1>Welcome to SimpleRouter</h1>
    <p>A lightweight Front Controller built in pure PHP — routing all requests through <code>index.php</code> using the <code>?page=</code> query parameter.</p>
  </div>
</section>

<!-- MVC Concept banner -->
<div class="info-box info animate delay-1" style="margin-bottom:28px;">
  <div class="icon">💡</div>
  <div>
    <strong>MVC Concept:</strong> This page is <strong>index.php</strong> (the <em>Front Controller / Controller</em>). It reads <code>$_GET['page']</code>, validates it, then loads the matching <strong>View</strong> from <code>views/</code>.
    No actual Model layer is needed for this static demo.
  </div>
</div>

<!-- Route Map -->
<p class="section-title animate delay-1">Route Map</p>
<div class="card animate delay-1" style="padding:0; overflow:hidden; margin-bottom:28px;">
  <table class="route-table">
    <thead>
      <tr>
        <th>URL</th>
        <th>?page=</th>
        <th>View file</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><a href="index.php">index.php</a></td>
        <td><code>(none)</code></td>
        <td><code>views/home.php</code></td>
        <td><span class="badge badge-green">200 OK</span></td>
      </tr>
      <tr>
        <td><a href="index.php?page=home">index.php?page=home</a></td>
        <td><code>home</code></td>
        <td><code>views/home.php</code></td>
        <td><span class="badge badge-green">200 OK</span></td>
      </tr>
      <tr>
        <td><a href="index.php?page=about">index.php?page=about</a></td>
        <td><code>about</code></td>
        <td><code>views/about.php</code></td>
        <td><span class="badge badge-green">200 OK</span></td>
      </tr>
      <tr>
        <td><a href="index.php?page=services">index.php?page=services</a></td>
        <td><code>services</code></td>
        <td><code>views/services.php</code></td>
        <td><span class="badge badge-green">200 OK</span></td>
      </tr>
      <tr>
        <td><a href="index.php?page=blog">index.php?page=blog</a></td>
        <td><code>blog</code></td>
        <td><code>views/blog.php</code></td>
        <td><span class="badge badge-green">200 OK</span></td>
      </tr>
      <tr>
        <td><a href="index.php?page=contact">index.php?page=contact</a></td>
        <td><code>contact</code></td>
        <td><code>views/contact.php</code></td>
        <td><span class="badge badge-green">200 OK</span></td>
      </tr>
      <tr>
        <td><a href="index.php?page=unknown">index.php?page=unknown</a></td>
        <td><code>unknown</code></td>
        <td><code>views/404.php</code></td>
        <td><span class="badge badge-red">404 Not Found</span></td>
      </tr>
    </tbody>
  </table>
</div>

<!-- How it works -->
<p class="section-title animate delay-2">How the Router Works</p>
<div class="grid grid-2 animate delay-2" style="margin-bottom:28px;">
  <div class="card">
    <div class="card-icon">1️⃣</div>
    <h3>Receive Request</h3>
    <p>Every URL is pointed at <code>index.php</code>. The script reads <code>$_GET['page']</code> (default <em>home</em>).</p>
  </div>
  <div class="card">
    <div class="card-icon">2️⃣</div>
    <h3>Sanitise &amp; Validate</h3>
    <p>Input is lowercased and stripped of non-alpha characters to prevent path traversal or injection attacks.</p>
  </div>
  <div class="card">
    <div class="card-icon">3️⃣</div>
    <h3>Allow-list Check</h3>
    <p>The page slug is checked against <code>$allowedPages</code>. Unknown pages are rejected and sent to the 404 view.</p>
  </div>
  <div class="card">
    <div class="card-icon">4️⃣</div>
    <h3>Load View</h3>
    <p><code>loadView()</code> maps the slug to <code>views/{page}.php</code> and <code>require</code>s it — rendering the page.</p>
  </div>
</div>

<!-- Core code excerpt -->
<p class="section-title animate delay-3">Core Router Code</p>
<div class="code-block animate delay-3">
<span class="cmt">// 1. Read &amp; sanitise</span><br>
<span class="var">$raw</span>  = isset(<span class="var">$_GET</span>[<span class="str">'page'</span>]) ? strtolower(trim(<span class="var">$_GET</span>[<span class="str">'page'</span>])) : <span class="str">'home'</span>;<br>
<span class="var">$page</span> = preg_replace(<span class="str">'/[^a-z]/'</span>, <span class="str">''</span>, <span class="var">$raw</span>) ?: <span class="str">'home'</span>;<br>
<br>
<span class="cmt">// 2. Allow-list</span><br>
<span class="var">$allowedPages</span> = [<span class="str">'home'</span>, <span class="str">'about'</span>, <span class="str">'contact'</span>, <span class="str">'blog'</span>, <span class="str">'services'</span>];<br>
<br>
<span class="cmt">// 3. Dispatch</span><br>
<span class="kw">if</span> (in_array(<span class="var">$page</span>, <span class="var">$allowedPages</span>, <span class="kw">true</span>)) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">loadView</span>(<span class="var">$page</span>);<br>
} <span class="kw">else</span> {<br>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">http_response_code</span>(<span class="str">404</span>);<br>
&nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">loadView</span>(<span class="str">'404'</span>);<br>
}
</div>

<?php layoutFoot(); ?>
