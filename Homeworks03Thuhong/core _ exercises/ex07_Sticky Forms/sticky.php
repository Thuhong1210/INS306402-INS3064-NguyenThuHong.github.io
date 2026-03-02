<?php
// sticky.php - Light Professional Sticky Form

$errors = [];
$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';
$post = $submitted ? $_POST : [];

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function old(array $data, string $name, string $default = ''): string {
    if (!array_key_exists($name, $data)) return $default;
    $val = $data[$name];
    if (is_array($val)) return $default;
    return e((string)$val);
}

function checked(array $data, string $name, string $value): string {
    if (!array_key_exists($name, $data)) return '';
    $val = $data[$name];
    if (is_array($val)) return in_array($value, $val, true) ? 'checked' : '';
    return ((string)$val === $value) ? 'checked' : '';
}

function selected(array $data, string $name, string $value): string {
    if (!array_key_exists($name, $data)) return '';
    return ((string)$data[$name] === $value) ? 'selected' : '';
}

$successData = null;

if ($submitted) {
    $name = trim((string)($post['name'] ?? ''));
    $email = trim((string)($post['email'] ?? ''));
    $age = trim((string)($post['age'] ?? ''));
    $gender = (string)($post['gender'] ?? '');
    $interests = $post['interests'] ?? [];
    $country = (string)($post['country'] ?? '');
    $message = trim((string)($post['message'] ?? ''));

    if ($name === '') $errors[] = 'Name is required.';
    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email must be a valid email address.';
    }

    if ($age !== '' && filter_var($age, FILTER_VALIDATE_INT) === false) {
        $errors[] = 'Age must be an integer.';
    }

    if (!is_array($interests) || count($interests) === 0) {
        $errors[] = 'Please select at least one interest.';
    }

    if (empty($errors)) {
        $successData = [
            'Name' => $name,
            'Email' => $email,
            'Age' => $age,
            'Gender' => $gender,
            'Interests' => implode(', ', array_map('strval', $interests)),
            'Country' => $country,
            'Message' => $message,
        ];
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Professional Sticky Form</title>

<style>
:root{
    --bg:#f4f6f9;
    --card:#ffffff;
    --border:#e5e7eb;
    --text:#1f2937;
    --muted:#6b7280;
    --primary:#2563eb;
    --primary-hover:#1e40af;
    --danger-bg:#fef2f2;
    --danger-border:#fecaca;
    --danger-text:#b91c1c;
    --success-bg:#ecfdf5;
    --success-border:#bbf7d0;
    --success-text:#047857;
    --radius:12px;
    --shadow:0 10px 30px rgba(0,0,0,.05);
}

*{box-sizing:border-box}

body{
    margin:0;
    font-family: system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial;
    background:var(--bg);
    color:var(--text);
    display:flex;
    justify-content:center;
    padding:40px 20px;
}

.container{
    width:100%;
    max-width:820px;
}

.header{
    text-align:center;
    margin-bottom:25px;
}

.header h1{
    margin:0 0 8px;
    font-size:28px;
}

.header p{
    margin:0;
    color:var(--muted);
}

.card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:var(--radius);
    box-shadow:var(--shadow);
    padding:25px;
}

.alert{
    padding:14px 16px;
    border-radius:10px;
    margin-bottom:18px;
    font-size:14px;
}

.alert ul{
    margin:8px 0 0 18px;
}

.alert-danger{
    background:var(--danger-bg);
    border:1px solid var(--danger-border);
    color:var(--danger-text);
}

.alert-success{
    background:var(--success-bg);
    border:1px solid var(--success-border);
    color:var(--success-text);
}

.grid{
    display:grid;
    gap:18px;
}

@media(min-width:720px){
    .grid{
        grid-template-columns:1fr 1fr;
    }
    .full{
        grid-column:1/-1;
    }
}

label{
    display:block;
    font-size:13px;
    font-weight:600;
    margin-bottom:6px;
}

.required{
    color:var(--primary);
}

input,select,textarea{
    width:100%;
    padding:10px 12px;
    border-radius:8px;
    border:1px solid var(--border);
    font-size:14px;
    outline:none;
    transition:.2s ease;
}

input:focus,select:focus,textarea:focus{
    border-color:var(--primary);
    box-shadow:0 0 0 3px rgba(37,99,235,.15);
}

textarea{
    min-height:110px;
    resize:vertical;
}

.options{
    display:flex;
    flex-wrap:wrap;
    gap:15px;
    font-size:14px;
}

button{
    background:var(--primary);
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
    transition:.2s ease;
}

button:hover{
    background:var(--primary-hover);
}

.footer{
    margin-top:18px;
    font-size:12px;
    color:var(--muted);
    text-align:center;
}
</style>
</head>

<body>
<div class="container">

<div class="header">
    <h1>Professional Sticky Form</h1>
    <p>Simple, clean and modern light interface</p>
</div>

<div class="card">

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <strong>Please fix the following errors:</strong>
    <ul>
        <?php foreach ($errors as $eMsg): ?>
            <li><?= e($eMsg) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<?php if ($successData !== null): ?>
<div class="alert alert-success">
    <strong>Form submitted successfully!</strong>
</div>
<?php endif; ?>

<form method="post">

<div class="grid">

<div>
<label>Name <span class="required">*</span></label>
<input type="text" name="name" value="<?= old($post,'name') ?>">
</div>

<div>
<label>Email <span class="required">*</span></label>
<input type="email" name="email" value="<?= old($post,'email') ?>">
</div>

<div>
<label>Age</label>
<input type="number" name="age" value="<?= old($post,'age') ?>">
</div>

<div>
<label>Country</label>
<select name="country">
<option value="">Select country</option>
<option value="US" <?= selected($post,'country','US') ?>>United States</option>
<option value="CA" <?= selected($post,'country','CA') ?>>Canada</option>
<option value="UK" <?= selected($post,'country','UK') ?>>United Kingdom</option>
</select>
</div>

<div class="full">
<label>Gender</label>
<div class="options">
<label><input type="radio" name="gender" value="male" <?= checked($post,'gender','male') ?>> Male</label>
<label><input type="radio" name="gender" value="female" <?= checked($post,'gender','female') ?>> Female</label>
<label><input type="radio" name="gender" value="other" <?= checked($post,'gender','other') ?>> Other</label>
</div>
</div>

<div class="full">
<label>Interests <span class="required">*</span></label>
<div class="options">
<label><input type="checkbox" name="interests[]" value="coding" <?= checked($post,'interests','coding') ?>> Coding</label>
<label><input type="checkbox" name="interests[]" value="music" <?= checked($post,'interests','music') ?>> Music</label>
<label><input type="checkbox" name="interests[]" value="sports" <?= checked($post,'interests','sports') ?>> Sports</label>
</div>
</div>

<div class="full">
<label>Message</label>
<textarea name="message"><?= old($post,'message') ?></textarea>
</div>

</div>

<br>
<button type="submit">Submit</button>

</form>

<div class="footer">
© 2026 Professional Sticky Form
</div>

</div>
</div>
</body>
</html>