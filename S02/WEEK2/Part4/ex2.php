<?php
$students = [
    ['name' => 'Alice Nguyễn', 'grade' => 95],
    ['name' => 'Bình Trần',   'grade' => 88],
    ['name' => 'Chí Lê',      'grade' => 76],
    ['name' => 'Duc Pham',    'grade' => 82],
    ['name' => 'Emmy Hà',     'grade' => 91],
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Student List</title>
<style>
    :root{--bg:#f4f7fb;--card:#ffffff;--accent:#4f46e5;--muted:#64748b}
    *{box-sizing:border-box}
    body{margin:0;font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial;color:#0f172a;background:linear-gradient(180deg,#eef2ff 0%,var(--bg) 100%);padding:40px}
    .wrap{max-width:920px;margin:0 auto}
    .card{background:var(--card);border-radius:12px;box-shadow:0 6px 30px rgba(15,23,42,.08);overflow:hidden}
    header{padding:24px 28px;border-bottom:1px solid #eef2ff;display:flex;align-items:center;justify-content:space-between}
    header h1{margin:0;font-size:18px;color:#0b1220}
    header p{margin:0;font-size:13px;color:var(--muted)}
    .table-wrap{padding:18px}
    table{width:100%;border-collapse:collapse;font-size:14px}
    thead th{background:linear-gradient(90deg,rgba(79,70,229,.06),rgba(79,70,229,.02));text-align:left;padding:12px 16px;color:#0b1220;font-weight:600}
    tbody td{padding:12px 16px;border-top:1px solid #f1f5f9;color:#0f172a}
    tbody tr:hover{background:linear-gradient(90deg,rgba(79,70,229,.03),transparent)}
    .rank{width:56px;color:var(--muted);font-weight:600}
    .name{min-width:260px}
    .grade{width:120px;text-align:right;font-weight:700;color:var(--accent)}
    .badge{display:inline-block;padding:6px 10px;border-radius:999px;font-size:12px;background:#eef2ff;color:var(--accent);margin-left:8px}
    @media (max-width:640px){
        header{flex-direction:column;align-items:flex-start;gap:8px}
        .grade{width:auto;text-align:left}
    }
</style>
</head>
<body>
<div class="wrap">
    <div class="card" role="region" aria-label="Student list">
        <header>
            <div>
                <h1>Student List</h1>
                <p>Danh sách sinh viên — điểm bài tập</p>
            </div>
            <div class="meta">
                <small style="color:var(--muted)">Total: <?php echo count($students); ?></small>
            </div>
        </header>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th class="rank">#</th>
                        <th class="name">Name</th>
                        <th class="grade">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $i => $s): ?>
                        <tr>
                            <td class="rank"><?php echo $i + 1; ?></td>
                            <td class="name"><?php echo htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="grade">
                                <?php echo number_format((float)$s['grade'], 0); ?>
                                <span class="badge">
                                    <?php
                                        $g = (float)$s['grade'];
                                        if ($g >= 90) echo 'Excellent';
                                        elseif ($g >= 80) echo 'Good';
                                        elseif ($g >= 70) echo 'Pass';
                                        else echo 'Remedial';
                                    ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>