<?php

$id = 'localhost';
$user = "root";
$pass = "";
$db = "perpustakaan";

$koneksi = mysqli_connect($id, $user, $pass, $db);

if (!$koneksi) {
    die("Tidak dapat terkoneksi ke database");
}

// inisialisasi variabel
$judul = "";
$penulis = "";
$penerbit = "";
$tahun = "";
$error = "";
$success = "";

// memanggil variabel
if (isset($_GET['op'])){
  $op = $_GET['op'];
} else {
  $op = "";
}

// fungsi edit
if ($op == 'edit'){
  $id = $_GET['id'];
  $sql1 = "SELECT * FROM buku WHERE id = '$id'";
  $q1 = mysqli_query($koneksi, $sql1);
  $r1 = mysqli_fetch_array($q1);
  $judul = $r1['Judul_Buku'];
  $penulis = $r1['Penulis'];
  $penerbit = $r1['Penerbit'];
  $tahun = $r1['Tahun_Terbit'];

  if ($judul = ''){
    $error = "Data buku tidak ditemukan";
  }

}

// fungsi delete
if ($op == 'delete') {
  $id = $_GET['id'];
  $sql1 = "DELETE FROM buku WHERE id = '$id'";
  $q1 = mysqli_query($koneksi, $sql1);
  if ($q1) {
    $success = "Data buku berhasil dihapus";
  } else {
    $error = "Gagal menghapus data buku ini";
  }
}



if (isset($_POST['simpan'])){
  $judul = $_POST ['Judul_Buku'];
  $penulis = $_POST ['Penulis'];
  $penerbit = $_POST ['Penerbit'];
  $tahun = $_POST ['Tahun_Terbit'];

  if($judul && $penulis && $penerbit && $tahun) {
    if ($op == 'edit'){ //edit data buku
      $sql1 = "UPDATE buku SET Judul_Buku='$judul', Penulis='$penulis', Penerbit='$penerbit', Tahun_Terbit='$tahun' WHERE id='$id'";
      $q1 = mysqli_query($koneksi, $sql1);
      if ($q1) {
        $success = "Data buku berhasil diperbahrui";
      } else {
        $error = "Data buku gagal diperbahrui";
      }
    } else { // insert data buku
      $sql1 = "INSERT INTO buku (Judul_buku, Penulis, Penerbit, Tahun_Terbit) VALUES ('$judul','$penulis','$penerbit','$tahun')";
      $q1 = mysqli_query($koneksi,$sql1);
      if ($q1){
        $success = "Sukses menambah data buku";
      } else {
        $error = "Gagal memasukkan data buku, silahkan periksa lagi apakah data yang dimasukkan sudah benar";
      }
    }
  } else {
    $error = "Data tidak boleh ada yang kosong";
  }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rykondengis | Projek CRUD perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <div class="mx-auto">
        <!-- FORM INPUT -->
        <div class="card">
            <div class="card-header text-white bg-success">
                Silahkan masukkan data buku anda 📖
            </div>
            <div class="card-body">
                <?php
                if ($error){
                ?>
                <div class="alert alert-danger" role="alert">
                    <?php
              echo $error;
              ?>
                </div>
                <?php
                header("refresh:3;url = index.php"); // refresh halaman setiap 3 detik setelah proses CRUD
                  }
                ?>
                <?php
                if ($success){
                ?>
                <div class="alert alert-success" role="alert">
                    <?php
              echo $success;
              ?>
                </div>
                <?php
                header("refresh:3;url = index.php");
                  }
                ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="Judul_Buku" class="form-label">Judul Buku</label>
                        <input type="text" class="form-control" id="Judul_Buku" name="Judul_Buku"
                            value="<?php echo $judul ?>">
                    </div>
                    <div class="mb-3">
                        <label for="Penulis" class="form-label">Penulis</label>
                        <input type="text" class="form-control" id="Penulis" name="Penulis"
                            value="<?php echo $penulis ?>">
                    </div>
                    <div class="mb-3">
                        <label for="Penerbit" class="form-label">Penerbit</label>
                        <input type="text" class="form-control" id="Penerbit" name="Penerbit"
                            value="<?php echo $penerbit ?>">
                    </div>
                    <div class="mb-3">
                        <label for="Tahun_Terbit" class="form-label">Tahun terbit</label>
                        <input type="number" class="form-control" id="Tahun_Terbit" name="Tahun_Terbit"
                            value="<?php echo $tahun ?>">
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="simpan data" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- FORM OUTPUT -->
    <div class="mx-auto">
        <div class="card">
            <div class="card-header text-white bg-success">
                Data Buku 📚
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Judul Buku</th>
                            <th scope="col">Penulis</th>
                            <th scope="col">Penerbit</th>
                            <th scope="col">Tahun Terbit</th>
                            <th scope="col">Eksekusi</th>
                        </tr>
                    <tbody>
                        <?php
                      // query urut data dari yang terbaru
                      $sql2 = "SELECT * FROM buku ORDER BY id DESC";
                      $q2 = mysqli_query($koneksi, $sql2);
                      $sort = 1;
                      while ($r2 = mysqli_fetch_array($q2)){
                        $id = $r2['id'];
                        $judul = $r2['Judul_Buku'];
                        $penulis = $r2['Penulis'];
                        $penerbit = $r2['Penerbit'];
                        $tahun = $r2['Tahun_Terbit'];
                        ?>
                        <tr>
                            <!-- read data -->
                            <th scope="row"><?php echo $sort++ ?></th>
                            <td scope="row"><?php echo $judul ?></td>
                            <td scope="row"><?php echo $penulis ?></td>
                            <td scope="row"><?php echo $penerbit ?></td>
                            <td scope="row"><?php echo $tahun ?></td>
                            <td scope="row">
                                <!-- button edit -->
                                <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button"
                                        class="btn btn-secondary">Edit</button></a>
                                <!-- button delete -->
                                <a href="index.php?op=delete&id=<?php echo $id ?>"
                                    onclick="return confirm('Yakin ingin menghapus data buku?')"><button type="button"
                                        class="btn btn-danger">Delete</button></a>
                            </td>
                        </tr>
                        <?php
                        }
                      ?>
                    </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</body>

</html>