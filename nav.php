<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'beranda';
?>

<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo-text">
            <span>Employee Evaluation</span>
            <small>Administrator</small>
        </div>
    </div>

    <ul class="menu-list">

        <!-- DASHBOARD -->
        <li class="menu-item <?= ($page == 'beranda') ? 'active' : '' ?>">
            <a href="?page=beranda" class="menu-link">
                <i class="fa fa-home menu-icon"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- MASTER DATA -->
        <div class="menu-section">MASTER DATA</div>

        <!-- DIVISI -->
        <li class="menu-item <?= ($page == 'divisi') ? 'active' : '' ?>">
            <a href="?page=divisi" class="menu-link">
                <i class="fa fa-building menu-icon"></i>
                <span>Divisi</span>
            </a>
        </li>

        <!-- AHP -->
        <li class="menu-item <?= ($page == 'ahp') ? 'active' : '' ?>">
            <a href="?page=ahp" class="menu-link">
                <i class="fa fa-list menu-icon"></i>
                <span>Perhitungan AHP</span>
            </a>
        </li>

        <!-- KRITERIA -->
        <li class="menu-item <?= ($page == 'kriteria') ? 'active' : '' ?>">
            <a href="?page=kriteria" class="menu-link">
                <i class="fa fa-list-alt menu-icon"></i>
                <span>Kriteria</span>
            </a>
        </li>

        <!-- DATA KARYAWAN -->
        <div class="menu-section">DATA KARYAWAN</div>

        <!-- KARYAWAN -->
        <li class="menu-item <?= ($page == 'karyawan') ? 'active' : '' ?>">
            <a href="?page=karyawan" class="menu-link">
                <i class="fa fa-users menu-icon"></i>
                <span>Karyawan</span>
            </a>
        </li>

        <!-- PENILAIAN -->
        <div class="menu-section">PENILAIAN</div>

        <li class="menu-item <?= ($page == 'penilaiankaryawan') ? 'active' : '' ?>">
            <a href="?page=penilaiankaryawan" class="menu-link">
                <i class="fa fa-edit menu-icon"></i>
                <span>Penilaian Karyawan</span>
            </a>
        </li>

        <!-- HASIL -->
        <li class="menu-item <?= ($page == 'hasil') ? 'active' : '' ?>">
        <a href="?page=hasil" class="menu-link">
            <i class="fa fa-chart-bar menu-icon"></i>
            <span>Hasil Akhir</span>
        </a>
    </li>

    <li class="menu-item">
        <a href="logout.php" class="menu-link menu-link-danger">
            <i class="fa fa-sign-out-alt menu-icon"></i>
            <span>Keluar</span>
        </a>
     </li>

    </ul>
</div>
