<?php

// Mulai sesi
ob_start();
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  // Jika belum, redirect ke halaman login
  header("Location: ../");
  exit();
}
// Koneksi ke Database
include "../koneksi.php";

// Mengambil Data Sesuai Session
$username = $_SESSION['username'];
// $query="SELECT data_pasien.*, riwayat_berobat.*
// FROM data_pasien
// LEFT JOIN riwayat_berobat ON data_pasien.id_pasien = riwayat_berobat.id_pasien";
// // Memasukkan Kedalam array hasil dari query
// $result = mysqli_query($conn, $query);
// Hitung jumlah data total
$query_total = "SELECT COUNT(*) as total FROM data_pasien";
$result_total = mysqli_query($conn, $query_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_data = $row_total['total'];
// Tentukan jumlah data per halaman
$data_per_halaman = 10;
// Hitung jumlah halaman
$total_halaman = ceil($total_data / $data_per_halaman);

// Tentukan halaman saat ini (jika tidak ditentukan, gunakan halaman pertama)
$halaman_saat_ini = isset($_GET['page']) ? $_GET['page'] : 1;
// Pastikan halaman saat ini berada dalam rentang yang valid
if ($halaman_saat_ini < 1) {
  $halaman_saat_ini = 1;
} elseif ($halaman_saat_ini > $total_halaman) {
  $halaman_saat_ini = $total_halaman;
}

// Hitung offset
$offset = ($halaman_saat_ini - 1) * $data_per_halaman;

// Query untuk menampilkan data pasien pada halaman saat ini
$query_data_pasien = "SELECT * FROM data_pasien ORDER BY nama_depan ASC LIMIT $data_per_halaman OFFSET $offset";
$result_data_pasien = mysqli_query($conn, $query_data_pasien);

?>

<!DOCTYPE html>
<html lang="en" class="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tables - Admin One Tailwind CSS Admin Dashboard</title>

  <!-- Tailwind is included -->
  <link rel="stylesheet" href="css/main.css?v=1652870200386">

  <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png" />
  <link rel="mask-icon" href="safari-pinned-tab.svg" color="#00b4b6" />

  <meta name="description" content="Admin One - free Tailwind dashboard">

  <meta property="og:url" content="https://justboil.github.io/admin-one-tailwind/">
  <meta property="og:site_name" content="JustBoil.me">
  <meta property="og:title" content="Admin One HTML">
  <meta property="og:description" content="Admin One - free Tailwind dashboard">
  <meta property="og:image" content="https://justboil.me/images/one-tailwind/repository-preview-hi-res.png">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1920">
  <meta property="og:image:height" content="960">

  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:title" content="Admin One HTML">
  <meta property="twitter:description" content="Admin One - free Tailwind dashboard">
  <meta property="twitter:image:src" content="https://justboil.me/images/one-tailwind/repository-preview-hi-res.png">
  <meta property="twitter:image:width" content="1920">
  <meta property="twitter:image:height" content="960">

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-130795909-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-130795909-1');
  </script>

</head>

<body>

  <div id="app">

    <!-- Awal Navbar -->
    <nav id="navbar-main" class="navbar is-fixed-top">
      <div class="navbar-brand">
        <a class="navbar-item mobile-aside-button">
          <span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>
        </a>
        <div class="navbar-item">
          <div class="control"><input placeholder="Search everywhere..." class="input"></div>
        </div>
      </div>
      <div class="navbar-brand is-right">
        <a class="navbar-item --jb-navbar-menu-toggle" data-target="navbar-menu">
          <span class="icon"><i class="mdi mdi-dots-vertical mdi-24px"></i></span>
        </a>
      </div>
      <div class="navbar-menu" id="navbar-menu">
        <div class="navbar-end">
          <div class="navbar-item dropdown has-divider has-user-avatar">
            <a class="navbar-link">
              <div class="user-avatar">
                <img src="https://avatars.dicebear.com/v2/initials/john-doe.svg" alt="John Doe" class="rounded-full">
              </div>
              <div class="is-user-name"><span>John Doe</span></div>
              <span class="icon"><i class="mdi mdi-chevron-down"></i></span>
            </a>
            <div class="navbar-dropdown">
              <a href="profile.html" class="navbar-item">
                <span class="icon"><i class="mdi mdi-account"></i></span>
                <span>My Profile</span>
              </a>
              <a class="navbar-item">
                <span class="icon"><i class="mdi mdi-settings"></i></span>
                <span>Settings</span>
              </a>
              <a class="navbar-item">
                <span class="icon"><i class="mdi mdi-email"></i></span>
                <span>Messages</span>
              </a>
              <hr class="navbar-divider">
              <form action="logout.php" method="post">
                <input type="submit" value="Logout">
                <a class="navbar-item">
                  <span class="icon"><i class="mdi mdi-logout"></i></span>
                  <span>Log Out</span>
                </a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Akhir Navbar -->

    <!-- Awal Sidebar -->
    <aside class="aside is-placed-left is-expanded">
      <div class="aside-tools">
        <div>
          Admin <b class="font-black">One</b>
        </div>
      </div>
      <div class="menu is-menu-main">
        <p class="menu-label">General</p>
        <ul class="menu-list">
          <li class="--set-active-index-html">
            <a href="index.php">
              <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
              <span class="menu-item-label">Dashboard</span>
            </a>
          </li>
        </ul>
        <p class="menu-label">Examples</p>
        <ul class="menu-list">
          <li class="active">
            <a href="tables.php">
              <span class="icon"><i class="mdi mdi-table"></i></span>
              <span class="menu-item-label">Tables</span>
            </a>
          </li>
          <li class="--set-active-forms-html">
            <a href="forms.php">
              <span class="icon"><i class="mdi mdi-square-edit-outline"></i></span>
              <span class="menu-item-label">Forms</span>
            </a>
          </li>
          <li class="--set-active-profile-html">
            <a href="profile.php">
              <span class="icon"><i class="mdi mdi-account-circle"></i></span>
              <span class="menu-item-label">Profile</span>
            </a>
          </li>
          <li>
            <a href="login.php">
              <span class="icon"><i class="mdi mdi-lock"></i></span>
              <span class="menu-item-label">Login</span>
            </a>
          </li>
          <li>
            <a class="dropdown">
              <span class="icon"><i class="mdi mdi-view-list"></i></span>
              <span class="menu-item-label">Submenus</span>
              <span class="icon"><i class="mdi mdi-plus"></i></span>
            </a>
            <ul>
              <li>
                <a href="#void">
                  <span>Sub-item One</span>
                </a>
              </li>
              <li>
                <a href="#void">
                  <span>Sub-item Two</span>
                </a>
              </li>
            </ul>
          </li>
        </ul>

      </div>
    </aside>

    <!-- Akhir Sidebar -->
    <section class="is-title-bar">
      <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
        <ul>
          <li>Admin</li>
          <li>Tables</li>
        </ul>
        <a href="https://github.com/justboil/admin-one-tailwind" target="_blank" class="button blue">
          <span class="icon"><i class="mdi mdi-github-circle"></i></span>
          <span>GitHub</span>
        </a>
      </div>
    </section>

    <!-- <section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
      Responsive Tables
    </h1>
    <button class="button light">Button</button>
  </div>
</section> -->

    <section class="section main-section">
      <div class="notification blue">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
          <div>
            <span class="icon"><i class="mdi mdi-buffer"></i></span>
            <b>Responsive table</b>
          </div>
          <button type="button" class="button small textual --jb-notification-dismiss">Dismiss</button>
        </div>
      </div>

      <!-- Content Card Table  -->
      <div class="card has-table">
        <header class="card-header">
          <p class="card-header-title">
            <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
            Clients
          </p>
          <a href="#" class="card-header-icon">
            <span class="icon"><i class="mdi mdi-reload"></i></span>
          </a>
        </header>
        <div class="card-content">
          <table>
            <thead>
              <tr>
                <th class="checkbox-cell">
                  <label class="checkbox">
                    <input type="checkbox">
                    <span class="check"></span>
                  </label>
                </th>
                <th class="image-cell"></th>
                <th>ID Pasien</th>
                <th>Nama Depan</th>
                <th>Nama Belakang</th>
                <th>Jenis Kelamin</th>
                <th>Email</th>
                <th>Nomor HP</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <!-- Output List -->
              <?php
              while ($row_pasien = mysqli_fetch_assoc($result_data_pasien)) {
              ?>
                <tr>
                  <td class="checkbox-cell">
                    <label class="checkbox">
                      <input type="checkbox">
                      <span class="check"></span>
                    </label>
                  </td>
                  <td class="image-cell">
                    <div class="image">
                      <img src="https://avatars.dicebear.com/v2/initials/rebecca-bauch.svg" class="rounded-full">
                    </div>
                  </td>
                  <td data-label="id_pasien"><?php echo $row_pasien['id_pasien'] ?></td>
                  <td data-label="nama_depan"><?php echo $row_pasien['nama_depan'] ?></td>
                  <td data-label="nama_belakang"><?php echo $row_pasien['nama_belakang'] ?></td>
                  <td data-label="tanggal_berobat"><?php echo $row_pasien['gender'] ?></td>
                  <td data-label="email"><?php echo $row_pasien['email'] ?></td>
                  <td data-label="nomor_telp"><?php echo   $row_pasien['nomor_telp'] ?></td>
                  <td class="actions-cell">
                    <div class="buttons right nowrap">
                      <form method=post action='index.php'>
                        <input type='hidden' name='id_pasien' value='<?php echo $row_pasien['id_pasien'] ?>'>
                        <button type="submit" class="button small blue --jb-modal" data-target="sample-modal-2" type="button">
                          <span class="icon">
                            <i class="mdi mdi-eye"> </i>
                          </span>
                        </button>
                      </form>
                      <button class="button small red --jb-modal" onclick="hapusData(<?= $row_pasien['id_pasien'] ?>)">
                        <span class="icon"><i class="mdi mdi-trash-can">
                          </i>
                        </span>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php
              }
              mysqli_close($conn);
              ?>
            </tbody>
          </table>

          <!-- Pagination -->
          <div class="table-pagination">
            <div class="flex items-center justify-between">
              <div class="buttons">
                <?php for ($halaman = 1; $halaman <= $total_halaman; $halaman++) { ?>
                  <!-- <button type="button" class="button active"><?php echo "<a href='?page=$halaman'>$halaman</a> " ?>
              <a href=""></a>
              </button> -->
                <?php
                  echo "<a class='button active' href='?page=$halaman'>$halaman</a>";
                } ?>
              </div>
              <small>Page <?php echo $halaman_saat_ini ?> of <?php echo $halaman - 1 ?></small>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Notif Hijau -->
    <section class="section main-section">
    <div class="notification green">
      <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
        <div>
          <span class="icon"><i class="mdi mdi-buffer"></i></span>
          <b>Tightly wrapped.</b> Table header becomes card header
        </div>
        <button type="button" class="button small textual --jb-notification-dismiss">Dismiss</button>
      </div>
    </div>
    <!-- Batas Notif -->
    <!--
    <div class="card has-table">
      <div class="card-content">
        <table>
          <thead>
          <tr>
            <th class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </th>
            <th class="image-cell"></th>
            <th>Name</th>
            <th>Company</th>
            <th>City</th>
            <th>Progress</th>
            <th>Created</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </td>
            <td class="image-cell">
              <div class="image">
                <img src="https://avatars.dicebear.com/v2/initials/rebecca-bauch.svg" class="rounded-full">
              </div>
            </td>
            <td data-label="Name">Rebecca Bauch</td>
            <td data-label="Company">Daugherty-Daniel</td>
            <td data-label="City">South Cory</td>
            <td data-label="Progress" class="progress-cell">
              <progress max="100" value="79">79</progress>
            </td>
            <td data-label="Created">
              <small class="text-gray-500" title="Oct 25, 2021">Oct 25, 2021</small>
            </td>
            <td class="actions-cell">
              <div class="buttons right nowrap">
                <button class="button small blue --jb-modal"  data-target="sample-modal-2" type="button">
                  <span class="icon"><i class="mdi mdi-eye"></i></span>
                </button>
                <button class="button small red --jb-modal" data-target="sample-modal" type="button">
                  <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                </button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </td>
            <td class="image-cell">
              <div class="image">
                <img src="https://avatars.dicebear.com/v2/initials/felicita-yundt.svg" class="rounded-full">
              </div>
            </td>
            <td data-label="Name">Felicita Yundt</td>
            <td data-label="Company">Johns-Weissnat</td>
            <td data-label="City">East Ariel</td>
            <td data-label="Progress" class="progress-cell">
              <progress max="100" value="67">67</progress>
            </td>
            <td data-label="Created">
              <small class="text-gray-500" title="Jan 8, 2021">Jan 8, 2021</small>
            </td>
            <td class="actions-cell">
              <div class="buttons right nowrap">
                <button class="button small blue --jb-modal"  data-target="sample-modal-2" type="button">
                  <span class="icon"><i class="mdi mdi-eye"></i></span>
                </button>
                <button class="button small red --jb-modal" data-target="sample-modal" type="button">
                  <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                </button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </td>
            <td class="image-cell">
              <div class="image">
                <img src="https://avatars.dicebear.com/v2/initials/mr-larry-satterfield-v.svg" class="rounded-full">
              </div>
            </td>
            <td data-label="Name">Mr. Larry Satterfield V</td>
            <td data-label="Company">Hyatt Ltd</td>
            <td data-label="City">Windlerburgh</td>
            <td data-label="Progress" class="progress-cell">
              <progress max="100" value="16">16</progress>
            </td>
            <td data-label="Created">
              <small class="text-gray-500" title="Dec 18, 2021">Dec 18, 2021</small>
            </td>
            <td class="actions-cell">
              <div class="buttons right nowrap">
                <button class="button small blue --jb-modal"  data-target="sample-modal-2" type="button">
                  <span class="icon"><i class="mdi mdi-eye"></i></span>
                </button>
                <button class="button small red --jb-modal" data-target="sample-modal" type="button">
                  <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                </button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </td>
            <td class="image-cell">
              <div class="image">
                <img src="https://avatars.dicebear.com/v2/initials/mr-broderick-kub.svg" class="rounded-full">
              </div>
            </td>
            <td data-label="Name">Mr. Broderick Kub</td>
            <td data-label="Company">Kshlerin, Bauch and Ernser</td>
            <td data-label="City">New Kirstenport</td>
            <td data-label="Progress" class="progress-cell">
              <progress max="100" value="71">71</progress>
            </td>
            <td data-label="Created">
              <small class="text-gray-500" title="Sep 13, 2021">Sep 13, 2021</small>
            </td>
            <td class="actions-cell">
              <div class="buttons right nowrap">
                <button class="button small blue --jb-modal"  data-target="sample-modal-2" type="button">
                  <span class="icon"><i class="mdi mdi-eye"></i></span>
                </button>
                <button class="button small red --jb-modal" data-target="sample-modal" type="button">
                  <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                </button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </td>
            <td class="image-cell">
              <div class="image">
                <img src="https://avatars.dicebear.com/v2/initials/barry-weber.svg" class="rounded-full">
              </div>
            </td>
            <td data-label="Name">Barry Weber</td>
            <td data-label="Company">Schulist, Mosciski and Heidenreich</td>
            <td data-label="City">East Violettestad</td>
            <td data-label="Progress" class="progress-cell">
              <progress max="100" value="80">80</progress>
            </td>
            <td data-label="Created">
              <small class="text-gray-500" title="Jul 24, 2021">Jul 24, 2021</small>
            </td>
            <td class="actions-cell">
              <div class="buttons right nowrap">
                <button class="button small blue --jb-modal"  data-target="sample-modal-2" type="button">
                  <span class="icon"><i class="mdi mdi-eye"></i></span>
                </button>
                <button class="button small red --jb-modal" data-target="sample-modal" type="button">
                  <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                </button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </td>
            <td class="image-cell">
              <div class="image">
                <img src="https://avatars.dicebear.com/v2/initials/bert-kautzer-md.svg" class="rounded-full">
              </div>
            </td>
            <td data-label="Name">Bert Kautzer MD</td>
            <td data-label="Company">Gerhold and Sons</td>
            <td data-label="City">Mayeport</td>
            <td data-label="Progress" class="progress-cell">
              <progress max="100" value="62">62</progress>
            </td>
            <td data-label="Created">
              <small class="text-gray-500" title="Mar 30, 2021">Mar 30, 2021</small>
            </td>
            <td class="actions-cell">
              <div class="buttons right nowrap">
                <button class="button small blue --jb-modal"  data-target="sample-modal-2" type="button">
                  <span class="icon"><i class="mdi mdi-eye"></i></span>
                </button>
                <button class="button small red --jb-modal" data-target="sample-modal" type="button">
                  <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                </button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </td>
            <td class="image-cell">
              <div class="image">
                <img src="https://avatars.dicebear.com/v2/initials/lonzo-steuber.svg" class="rounded-full">
              </div>
            </td>
            <td data-label="Name">Lonzo Steuber</td>
            <td data-label="Company">Skiles Ltd</td>
            <td data-label="City">Marilouville</td>
            <td data-label="Progress" class="progress-cell">
              <progress max="100" value="17">17</progress>
            </td>
            <td data-label="Created">
              <small class="text-gray-500" title="Feb 12, 2021">Feb 12, 2021</small>
            </td>
            <td class="actions-cell">
              <div class="buttons right nowrap">
                <button class="button small blue --jb-modal"  data-target="sample-modal-2" type="button">
                  <span class="icon"><i class="mdi mdi-eye"></i></span>
                </button>
                <button class="button small red --jb-modal" data-target="sample-modal" type="button">
                  <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                </button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </td>
            <td class="image-cell">
              <div class="image">
                <img src="https://avatars.dicebear.com/v2/initials/jonathon-hahn.svg" class="rounded-full">
              </div>
            </td>
            <td data-label="Name">Jonathon Hahn</td>
            <td data-label="Company">Flatley Ltd</td>
            <td data-label="City">Billiemouth</td>
            <td data-label="Progress" class="progress-cell">
              <progress max="100" value="74">74</progress>
            </td>
            <td data-label="Created">
              <small class="text-gray-500" title="Dec 30, 2021">Dec 30, 2021</small>
            </td>
            <td class="actions-cell">
              <div class="buttons right nowrap">
                <button class="button small blue --jb-modal"  data-target="sample-modal-2" type="button">
                  <span class="icon"><i class="mdi mdi-eye"></i></span>
                </button>
                <button class="button small red --jb-modal" data-target="sample-modal" type="button">
                  <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                </button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </td>
            <td class="image-cell">
              <div class="image">
                <img src="https://avatars.dicebear.com/v2/initials/ryley-wuckert.svg" class="rounded-full">
              </div>
            </td>
            <td data-label="Name">Ryley Wuckert</td>
            <td data-label="Company">Heller-Little</td>
            <td data-label="City">Emeraldtown</td>
            <td data-label="Progress" class="progress-cell">
              <progress max="100" value="54">54</progress>
            </td>
            <td data-label="Created">
              <small class="text-gray-500" title="Jun 28, 2021">Jun 28, 2021</small>
            </td>
            <td class="actions-cell">
              <div class="buttons right nowrap">
                <button class="button small blue --jb-modal"  data-target="sample-modal-2" type="button">
                  <span class="icon"><i class="mdi mdi-eye"></i></span>
                </button>
                <button class="button small red --jb-modal" data-target="sample-modal" type="button">
                  <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                </button>
              </div>
            </td>
          </tr>
          <tr>
            <td class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </td>
            <td class="image-cell">
              <div class="image">
                <img src="https://avatars.dicebear.com/v2/initials/sienna-hayes.svg" class="rounded-full">
              </div>
            </td>
            <td data-label="Name">Sienna Hayes</td>
            <td data-label="Company">Conn, Jerde and Douglas</td>
            <td data-label="City">Jonathanfort</td>
            <td data-label="Progress" class="progress-cell">
              <progress max="100" value="55">55</progress>
            </td>
            <td data-label="Created">
              <small class="text-gray-500" title="Mar 7, 2021">Mar 7, 2021</small>
            </td>
            <td class="actions-cell">
              <div class="buttons right nowrap">
                <button class="button small blue --jb-modal"  data-target="sample-modal-2" type="button">
                  <span class="icon"><i class="mdi mdi-eye"></i></span>
                </button>
                <button class="button small red --jb-modal" data-target="sample-modal" type="button">
                  <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                </button>
              </div>
            </td>
          </tr>
          </tbody>
        </table>
        <div class="table-pagination">
          <div class="flex items-center justify-between">
            <div class="buttons">
              <button type="button" class="button active">1</button>
              <button type="button" class="button">2</button>
              <button type="button" class="button">3</button>
            </div>
            <small>Page 1 of 3</small>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <section class="section main-section">
    <div class="notification red">
      <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
        <div>
          <span class="icon"><i class="mdi mdi-buffer"></i></span>
          <b>Empty table.</b>
        </div>
        <button type="button" class="button small textual --jb-notification-dismiss">Dismiss</button>
      </div>
    </div>

    <div class="card empty">
      <div class="card-content">
        <div>
          <span class="icon large"><i class="mdi mdi-emoticon-sad mdi-48px"></i></span>
        </div>
        <p>Nothing's here…</p>
      </div>
    </div>
  </section>

<footer class="footer">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
    <div class="flex items-center justify-start space-x-3">
      <div>
        © 2022, JustBoil.me
      </div>
      <a href="https://github.com/justboil/admin-one-tailwind" style="height: 20px">
        <img src="https://img.shields.io/github/v/release/justboil/admin-one-tailwind?color=%23999">
      </a>
    </div>
    <a href="https://justboil.me">
    </a>
  </div>
</footer>

<div id="sample-modal" class="modal">
  <div class="modal-background --jb-modal-close"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Sample modal</p>
    </header>
    <section class="modal-card-body">
      <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
      <p>This is sample modal</p>
    </section>
    <footer class="modal-card-foot">
      <button class="button --jb-modal-close">Cancel</button>
      <button class="button red --jb-modal-close">Confirm</button>
    </footer>
  </div>
</div>

<div id="sample-modal-2" class="modal">
  <div class="modal-background --jb-modal-close"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Sample modal</p>
    </header>
    <section class="modal-card-body">
      <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
      <p>This is sample modal</p>
    </section> 
    <footer class="modal-card-foot">
      <button class="button --jb-modal-close">Cancel</button>
      <button class="button blue --jb-modal-close">Confirm</button>
    </footer>
  </div>
  </div>

  </div>

  <!-- Scripts below are for demo only -->
  <script type="text/javascript" src="js/main.min.js?v=1652870200386"></script>


  <script>
    ! function(f, b, e, v, n, t, s) {
      if (f.fbq) return;
      n = f.fbq = function() {
        n.callMethod ?
          n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };
      if (!f._fbq) f._fbq = n;
      n.push = n;
      n.loaded = !0;
      n.version = '2.0';
      n.queue = [];
      t = b.createElement(e);
      t.async = !0;
      t.src = v;
      s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
      'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '658339141622648');
    fbq('track', 'PageView');
  </script>
  <script>
    function hapusData(idPasien) {
      if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
        // Kirim permintaan AJAX untuk menghapus data
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "hapus_data.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && xhr.status == 200) {
            // Tampilkan notifikasi setelah data dihapus
            alert("Data berhasil dihapus");
            location.reload();

          }
        };
        xhr.send("id_pasien=" + idPasien);
      }
    }

    function lihatData(idRiwayat) {
      // Kirim permintaan AJAX untuk menghapus data
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "index.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          // Tampilkan notifikasi setelah data dihapus
          // location.reload();

        }
      };
      xhr.send("id_pasien=" + idRiwayat);
    }
  </script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=658339141622648&ev=PageView&noscript=1" /></noscript>

  <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

</body>

</html>