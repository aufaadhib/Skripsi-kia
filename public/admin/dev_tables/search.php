<?php
ob_start();
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "skripsi-kia";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Menentukan jumlah data per halaman
if (isset($_SESSION['page'])) {
  $halaman_saat_ini = $_SESSION['page'];
}
$data_per_halaman = 10;
// $halaman_saat_ini = isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;
// Hitung offset
$offset = ($halaman_saat_ini - 1) * $data_per_halaman;

$query = isset($_POST['query']) ? $_POST['query'] : '';

// Query untuk menghitung total hasil pencarian
$totalQuery = "SELECT COUNT(*) as total FROM data_pasien";
if ($query != '') {
    $totalQuery .= " WHERE nama_depan LIKE '%$query%' OR nama_belakang LIKE '%$query%'";
}
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$total_data = $totalRow['total'];
$total_halaman = ceil($total_data / $data_per_halaman);

$sql = "SELECT * FROM data_pasien";
if ($query != '') {
  $sql .= " WHERE nama_depan LIKE '%$query%' OR nama_belakang LIKE '%$query%' OR email LIKE '%$query%' OR nomor_telp LIKE '%$query%'";  
}

$sql .= " ORDER BY nama_depan ASC LIMIT $data_per_halaman OFFSET $offset";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  if ($query != '') {
    echo '<div class="notification green">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
                    <div>
                        <span class="icon"><i class="mdi mdi-buffer"></i></span>
                        <b>Hasil ditemukan.</b>
                    </div>
                    <button type="button" class="button small textual --jb-notification-dismiss">Dismiss</button>
                </div>
              </div>';
  }
  echo '<div class="card has-table">
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
                <tbody>';
  while ($row = $result->fetch_assoc()) {
    echo '<tr>
                <td class="checkbox-cell">
                  <label class="checkbox">
                    <input type="checkbox">
                    <span class="check"></span>
                  </label>
                </td>
                <td class="image-cell">
                  <div class="image">
                    <img src="https://avatars.dicebear.com/v2/initials/' . $row['nama_depan'] . '-' . $row['nama_belakang'] . '.svg" class="rounded-full">
                  </div>
                </td>
                <td>' . $row['id_pasien'] . '</td>
                <td>' . $row['nama_depan'] . '</td>
                <td>' . $row['nama_belakang'] . '</td>
                <td>' . $row['gender'] . '</td>
                <td>' . $row['email'] . '</td>
                <td>' . $row['nomor_telp'] . '</td>
                <td class="actions-cell">
                  <div class="buttons right nowrap">
                    <form method="post" action="index.php">
                      <input type="hidden" name="id_pasien" value="' . $row['id_pasien'] . '">
                      <button type="submit" class="button small blue --jb-modal">
                        <span class="icon">
                          <i class="mdi mdi-eye"></i>
                        </span>
                      </button>
                    </form>
                    <button class="button small red --jb-modal" onclick="hapusData(' . $row['id_pasien'] . ')">
                      <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                    </button>
                  </div>
                </td>
              </tr>';
  }
  echo '</tbody>
          </table>';
  // Pagination
  // $sql_pagination = "SELECT COUNT(*) AS total FROM data_pasien";
  // $result = $conn->query($sql_pagination);
  // $row = $result->fetch_assoc();
  // $total_data = $row['total'];
  // $total_halaman = ceil($total_data / $data_per_halaman);
  
  echo '<div class="table-pagination">
          <div class="flex items-center justify-between">
            <div class="buttons">';
  for ($halaman = 1; $halaman <= $total_halaman; $halaman++) {
    echo "<a class='button " . ($halaman == $halaman_saat_ini ? "active" : "") . "' href='?page=$halaman'>$halaman</a> ";
  }
  echo '</div>
              <small>Page ' . $halaman_saat_ini . ' of ' . $total_halaman . '</small>
            </div>
          </div>';
} else {
  echo '
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
        <p>Nothings here…</p>
      </div>
    </div>
  </section>
            ';
}

$conn->close();
