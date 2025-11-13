<?php
// koneksi ke database
$conn = mysqli_connect ("localhost", "ppkpipos1", "ppkpipos1","tahsin");

// Ambil data dari tabel login / query data login
function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    // '--> Sebagai wadah untuk mengambil data
    while ($row = mysqli_fetch_assoc($result)) {
    // $row sebagai masing-masing data
        $rows[] = $row;
    }
    return $rows;
}

function tambah($data) {
    global $conn;

    // ambil data dari tiap elemen dalam form
    $email = htmlspecialchars($data["email"]);
    $password = htmlspecialchars($data["password"]);
    
    // Cek apakah data berhasil ditambahkan atau tidak
    try {
        // memasukkan data di database
        $query = "INSERT INTO login VALUES
                    (null, '$email', '$password')
                ";
        mysqli_query($conn,$query);
        echo "
            <script>
                alert('Data berhasil ditambahkan');
                document.location.href = 'user.php';
            </script>
        ";
    } catch ( mysqli_sql_exception $e) {
        // Gagal memasukkan data karena kesalahan syntax
        echo "
            <script>
                alert('Data gagal ditambahkan');
            </script>
        ";
    }

    // return mysqli_affected_rows($conn);
}

function upload() {
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // Cek apakah tidak ada gambar yg di upload
    if( $error === 4 ) { // error 4, artinya tidak ada yg diupload
        echo "<script>
                alert('Pilih gambar terlebih dahulu');
              </script>";
        return false;
    }

    // Cek apakah yg diupload adalah gambar atau bukan
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    // explode --> sebuah fungsi yg memecah string menjadi array
    // delimiter --> yg memisahkan antar kata

    $ekstensiGambar = strtolower(end($ekstensiGambar));
    // gambar = tedi.fajar.jpg
    // gambar = ['tedi','fajar','jpg']
    // end() berfungsi untuk mengambil array yg paling terakhir (file ekstensi nya)
    // strtolower() berfungsi untuk membuat string menjadi huruf kecil semua

    // Cek apakah file berekstensi jpg / jpeg / png atau bukan
    if ( !in_array($ekstensiGambar, $ekstensiGambarValid)) {
    // in_array() berfungsi untuk mengecek apakah sebuah string ada di dalam array atau tidak
        echo "<script>
                alert('File harus berupa jpg / jpeg / png');
              </script>";
        return false;
    }

    // Cek jika ukuran file lebih besar
    if ( $ukuranFile > 1000000 ) { // --> dalam byte
        echo "<script>
                alert('Ukuran gambar terlalu besar');
              </script>";
        return false;
    }

    // Lolos pengecekan, gambar siap diupload
    // generate nama gambar baru
    $namaFileBaru = uniqid();
    // uniqid() --> menghasilkan angka random dalam bentuk string.
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

    return $namaFileBaru;
}

function hapus($id) {
    global $conn;

    mysqli_query($conn, "DELETE FROM film WHERE id = $id");

    return mysqli_affected_rows($conn);
}

function ubah($data){
    global $conn;

    $id = $data["id"];
    // id tidak perlu htmlspecialchars karena bukan input dari user
    $judul = htmlspecialchars($data["judul"]);
    $tahun = htmlspecialchars($data["tahun"]);
    $aktor = htmlspecialchars($data["aktor"]);
    $sutradara = htmlspecialchars($data["sutradara"]);
    $gambarLama = htmlspecialchars($data["gambarLama"]);

    // Cek apakah user pilih gambar baru atau tidak
    if ( $_FILES['gambar']['error'] === 4 ) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
    }

    $query = "UPDATE film SET
                judul = '$judul',
                tahun = '$tahun',
                aktor = '$aktor',
                sutradara = '$sutradara',
                gambar = '$gambar'
            WHERE id = $id
                ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function cari($keyword){
    $query = "SELECT * FROM film
                WHERE
              judul LIKE '%$keyword%' OR
              tahun LIKE '%$keyword%' OR
              aktor LIKE '%$keyword%' OR
              sutradara LIKE '%$keyword%'
            --   LIKE merupakan wild card yg dapat mencari data, agar tampil walau tidak 100% sama. Harus ditambahkan tanda % diakhir variabel
            ";

    return query($query);
}

function register($data) {
    global $conn;

    $username = strtolower(stripcslashes($data["username"]));
    // stripclashes() --> untuk menghilangkan backslash
    $password = mysqli_real_escape_string($conn, $data["password"]);
    // real_escape() --> memungkinkan user memasukkan password ada tanda kutip nya
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);

    // Cek username sudah ada atau belum
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' ");

    if(mysqli_fetch_assoc($result)) {
        echo "<script>
                alert('Username sudah ada, silahkan buat yg lain!');
              </script>";
        return false;
    }

    // Cek konfirmasi password
    if($password !== $password2) {
        echo "<script>
                alert('Konfirmasi password tidak sesuai!');
              </script>";
        return false;
    }

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Tambahkan user baru ke database
    mysqli_query($conn, "INSERT INTO users VALUES (NULL, '$username','$password')");

    return mysqli_affected_rows($conn);
}
?>
