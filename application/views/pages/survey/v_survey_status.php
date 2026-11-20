<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Kepuasan Pelanggan | PT Visi Yosindo Medikal</title>
    <link rel="shortcut icon" href="<?= base_url('assets/img/favicon.png') ?>" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.css') ?>" />

    <style>
        :root {
            --primary: #1d4ed8;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --card-border: #e2e8f0;
            --bg-page: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-page);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .status-card {
            max-width: 540px;
            width: 100%;
            background: #ffffff;
            border-radius: 20px;
            padding: 40px 28px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            border: 1px solid var(--card-border);
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            margin-bottom: 20px;
        }

        .icon-success {
            background: #dcfce7;
            color: #16a34a;
        }

        .icon-info {
            background: #e0f2fe;
            color: #0284c7;
        }

        .icon-warning {
            background: #fef3c7;
            color: #d97706;
        }

        .status-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 12px;
        }

        .status-desc {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .company-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            color: #475569;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="status-card">
        <?php if ($status_view === 'success'): ?>
            <div class="icon-circle icon-success">
                <i class="fas fa-check"></i>
            </div>
            <h1 class="status-title">Terima Kasih Banyak!</h1>
            <p class="status-desc">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php elseif ($status_view === 'already_completed'): ?>
            <div class="icon-circle icon-info">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <h1 class="status-title">Survey Sudah Selesai</h1>
            <p class="status-desc">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php else: ?>
            <div class="icon-circle icon-warning">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1 class="status-title">Link Tidak Ditemukan</h1>
            <p class="status-desc">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <div class="company-pill">
            <i class="fas fa-building"></i> PT Visi Yosindo Medikal
        </div>
    </div>
</body>

</html>
