<!-- start: header -->
<?php setlocale(LC_TIME, 'id_ID.utf8'); ?>
<header class="header">
    <div class="logo-container">
        <a href="<?= base_url() ?>" class="logo">
            <?= $this->config->item('apps_logo_text') ?>
        </a>
        <div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <!-- start: search & user box -->
    <div class="header-right">
        <!-- Modern Clock & Date Widget -->
        <div class="header-clock-widget d-none d-sm-flex mr-auto">
            <div class="clock-icon">
                <i class="far fa-clock"></i>
            </div>
            <div class="clock-text-wrapper">
                <span id="clock" class="clock-time"></span>
                <span class="clock-date"><?= hariIndo(date('l')) . ', ' . indo_dates(date("Y-m-d")) ?></span>
            </div>
        </div>

        <span class="separator"></span>

        <!-- Modern User Profile -->
        <div id="userbox" class="userbox-modern">
            <div class="user-avatar-circle">
                <?php
                $CI = &get_instance();
                $user_data = $CI->db->select('file_foto')->from('pengguna')->where('pengguna_id', sessPenggunaId())->get()->row();
                $file_foto = !empty($user_data->file_foto) ? $user_data->file_foto : null;
                if ($file_foto):
                ?>
                    <img src="<?= base_url('uploads/file_karyawan/foto/' . $file_foto) ?>" alt="<?= sessNama() ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" />
                <?php else: ?>
                    <?= substr(sessNama(), 0, 1); ?>
                <?php endif; ?>
            </div>
            <div class="user-info-wrapper d-none d-md-flex">
                <span class="user-greeting">Assalamualaikum,</span>
                <span class="user-name"><?= $this->session->userdata('nama'); ?></span>
            </div>
        </div>

        <span class="separator"></span>

        <!-- Modern Notification Dropdown -->
        <div id="notifikasibox" class="notif-dropdown-wrapper">
            <?php
            $totalnotif = totalnotifikasimasuk();
            ?>
            <div class="dropdown dropdown-notif">
                <a href="#" class="notif-toggle" id="notifDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="notif-icon-box <?= $totalnotif > 0 ? 'has-notifications' : '' ?>">
                        <i class="far fa-envelope"></i>
                        <?php if ($totalnotif > 0): ?>
                            <span class="notif-badge-num"><?= $totalnotif ?></span>
                        <?php endif; ?>
                    </div>
                </a>

                <div class="dropdown-menu dropdown-menu-right notif-dropdown-menu" aria-labelledby="notifDropdown">
                    <div class="notif-header">
                        <span class="notif-header-title">Notifikasi Masuk</span>
                        <?php if ($totalnotif > 0): ?>
                            <span class="badge badge-pill badge-primary"><?= $totalnotif ?> Baru</span>
                        <?php endif; ?>
                    </div>

                    <div class="notif-list-scroll">
                        <?php if ($totalnotif > 0): ?>
                            <ul class="notif-items-list">
                                <?php
                                $this->load->helper('encrypt_helper');
                                foreach (notifikasimasuk() as $row):
                                ?>
                                    <li class="notif-single-item">
                                        <div class="notif-item-left">
                                            <div class="notif-icon-circle">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                        </div>
                                        <div class="notif-item-body">
                                            <div class="notif-sender">
                                                Dari: <strong><?= htmlspecialchars($row->dari) ?></strong>
                                            </div>
                                            <div class="notif-recipient">
                                                Kepada: <?= htmlspecialchars($row->kepada) ?>
                                            </div>
                                            <div class="notif-desc">
                                                <a href="<?= base_url(decryptvym($row->link)) ?>" class="notif-text-link">
                                                    <?= htmlspecialchars($row->keterangan) ?>
                                                </a>
                                            </div>
                                            <div class="notif-time">
                                                <i class="far fa-clock mr-1"></i> <?= $row->data_created ?>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="notif-empty-state">
                                <i class="far fa-envelope-open"></i>
                                <p>Tidak ada notifikasi baru</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($totalnotif > 0): ?>
                        <div class="notif-footer">
                            <div class="row no-gutters w-100">
                                <div class="col-6 text-left">
                                    <a href="<?= base_url('notifikasi/update/all') ?>" class="notif-footer-link font-weight-bold text-success">
                                        <i class="fas fa-check-double mr-1"></i> Baca Semua
                                    </a>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="<?= base_url('notifikasi') ?>" class="notif-footer-link font-weight-bold text-primary">
                                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- end: search & user box -->
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        startTime();

        function startTime() {
            const today = new Date();
            let h = today.getHours();
            let m = today.getMinutes();
            let s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('clock').innerHTML = h + ":" + m + ":" + s;
            setTimeout(startTime, 1000);
        }

        function checkTime(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }
    });
</script>