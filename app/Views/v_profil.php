<?= $this->extend('layout') ?>
<?= $this->section('title') ?>Profil<?= $this->endSection() ?>
<?= $this->section('cardTitle') ?>Profil<?= $this->endSection() ?>
<?= $this->section('pageTitle') ?>
    <div class="pagetitle">
      <h1>Profil</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Profil</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$username = session()->get('username') ?? 'Guest';
$role = session()->get('role') ?? 'user';
$email = session()->get('email') ?? 'belum.ada@domain.com';
$loginTime = session()->get('login_time') ?? date('Y-m-d H:i:s');
$status = session()->get('status') ?? 'Tidak aktif';
?>
    <section class="section profile">
      <div class="row-lg-10">
        <div class="col-xl-4">

          <div class="card">
           
          </div>

        </div>

        <div class="col-lg-10">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
            
             <div class="card-body">
        <h5 class="card-title">Profile Information</h5>
        <div class="row mb-3">
          <div class="col-sm-4 text-secondary">Username</div>
          <div class="col-sm-8">
            <?= esc($username) ?> <span class="badge bg-danger"><?= esc($role) ?></span>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-sm-4 text-secondary">Email</div>
          <div class="col-sm-8"><?= esc($email) ?></div>
        </div>
        <div class="row mb-3">
          <div class="col-sm-4 text-secondary">Login Time</div>
          <div class="col-sm-8"><?= esc($loginTime) ?></div>
        </div>
        <div class="row">
          <div class="col-sm-4 text-secondary">Status</div>
          <div class="col-sm-8"><span class="badge bg-success"><?= esc($status) ?></span></div>
        </div>
                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>
<?= $this->endSection() ?>