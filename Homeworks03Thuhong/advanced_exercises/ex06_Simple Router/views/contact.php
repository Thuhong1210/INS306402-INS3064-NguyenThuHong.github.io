<?php
require __DIR__ . '/_layout.php';
layoutHead('Contact', $currentPage);
layoutNav($currentPage);
?>

<section class="page-hero animate">
  <div class="hero-icon blue">✉️</div>
  <div>
    <h1>Contact</h1>
    <p>Get in touch. This page demonstrates how a form could live inside a routed view — the controller would handle POST on the same entry point.</p>
  </div>
</section>

<div class="info-box info animate delay-1" style="margin-bottom:28px;">
  <div class="icon">🔗</div>
  <div>Route: <code>index.php?page=contact</code> → loaded <code>views/contact.php</code></div>
</div>

<div class="grid grid-2 animate delay-1" style="margin-bottom: 28px;">

  <!-- Contact form (display-only demo) -->
  <div class="card" style="padding: 24px;">
    <h3 style="margin-bottom:18px; font-size:16px;">📬 Send a Message</h3>

    <div style="margin-bottom:14px;">
      <label for="contact-name" style="display:block; font-size:13px; font-weight:600; margin-bottom:6px; color:var(--txt-muted);">Full Name</label>
      <input id="contact-name" type="text" placeholder="e.g. Thu Hong"
             style="width:100%; padding:9px 12px; border-radius:8px; border:1px solid var(--border);
                    background:var(--surface2); color:var(--txt); font-size:14px; outline:none;
                    transition:border-color .15s;"
             onfocus="this.style.borderColor='var(--primary)'"
             onblur="this.style.borderColor='var(--border)'">
    </div>

    <div style="margin-bottom:14px;">
      <label for="contact-email" style="display:block; font-size:13px; font-weight:600; margin-bottom:6px; color:var(--txt-muted);">Email</label>
      <input id="contact-email" type="email" placeholder="you@example.com"
             style="width:100%; padding:9px 12px; border-radius:8px; border:1px solid var(--border);
                    background:var(--surface2); color:var(--txt); font-size:14px; outline:none;
                    transition:border-color .15s;"
             onfocus="this.style.borderColor='var(--primary)'"
             onblur="this.style.borderColor='var(--border)'">
    </div>

    <div style="margin-bottom:20px;">
      <label for="contact-msg" style="display:block; font-size:13px; font-weight:600; margin-bottom:6px; color:var(--txt-muted);">Message</label>
      <textarea id="contact-msg" rows="4" placeholder="Your message …"
                style="width:100%; padding:9px 12px; border-radius:8px; border:1px solid var(--border);
                       background:var(--surface2); color:var(--txt); font-size:14px; resize:vertical;
                       outline:none; font-family:inherit; transition:border-color .15s;"
                onfocus="this.style.borderColor='var(--primary)'"
                onblur="this.style.borderColor='var(--border)'"></textarea>
    </div>

    <div class="info-box warn" style="margin-bottom:16px; font-size:12.5px;">
      <div class="icon" style="font-size:15px;">⚠️</div>
      <div>This form is a <strong>UI demo</strong>. In a real app, form action would point to <code>index.php?page=contact</code> and the Front Controller would detect <code>$_SERVER['REQUEST_METHOD'] === 'POST'</code> to process it.</div>
    </div>

    <button type="button"
            style="width:100%; padding:10px; border-radius:10px; border:none; cursor:pointer;
                   background:linear-gradient(135deg, var(--primary-dim), var(--primary)); color:#fff;
                   font-size:14px; font-weight:700; transition:.15s;"
            onmouseover="this.style.opacity='.85'"
            onmouseout="this.style.opacity='1'">
      Send Message (Demo)
    </button>
  </div>

  <!-- Info cards -->
  <div style="display:flex; flex-direction:column; gap:14px;">
    <div class="card">
      <div class="card-icon">📡</div>
      <h3>POST Handling in Front Controller</h3>
      <p>Check <code>$_SERVER['REQUEST_METHOD']</code> inside <code>index.php</code>. If <code>POST</code>, process the form; then redirect (PRG pattern) back to the page.</p>
    </div>
    <div class="card">
      <div class="card-icon">🔐</div>
      <h3>CSRF Protection</h3>
      <p>Generate a token in the session, embed it in a hidden field, and verify it on POST. See <em>ex02 CSRF Protection</em> for the full implementation.</p>
    </div>
    <div class="card">
      <div class="card-icon">✅</div>
      <h3>Server-side Validation</h3>
      <p>After routing, the controller validates fields with <code>filter_var()</code> and <code>trim()</code>. Errors are flashed to the session and shown in the view.</p>
    </div>
  </div>
</div>

<?php layoutFoot(); ?>
