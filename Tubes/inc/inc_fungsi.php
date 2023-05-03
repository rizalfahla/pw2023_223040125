<?php
function list_news()
{
    global $koneksi;
    $sql1 = "select * from halaman where kategori = 'topic' limit 4";
    $q1 = mysqli_query($koneksi, $sql1);
    return mysqli_fetch_all($q1);
}

function list_tranding()
{
    global $koneksi;
    $sql1 = "select * from halaman where kategori = 'trending_topic' limit 4";
    $q1 = mysqli_query($koneksi, $sql1);
    return mysqli_fetch_all($q1);
}

function list_rekomendasi()
{
    global $koneksi;
    $sql1 = "select * from halaman where kategori = 'rekomendasi' limit 3";
    $q1 = mysqli_query($koneksi, $sql1);
    return mysqli_fetch_all($q1);
}

function newsFeed()
{
    global $koneksi;
    $sql1 = "select * from halaman where kategori = 'news_feed' limit 4";
    $q1 = mysqli_query($koneksi, $sql1);
    return mysqli_fetch_all($q1);
}

function url_dasar()
{
    $url_dasar = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['SCRIPT_NAME']);
    return $url_dasar;
}

function base_url()
{
    $baseUrl = "http://" . $_SERVER['SERVER_NAME'];
    return $baseUrl;
}

function getImage($konten)
{
    preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $konten, $img);
    $gambar = $img[1];

    return $gambar;
}

function ambil_gambar($id_tulisan)
{
    global $koneksi;
    $sql1 = "select * from halaman where id = '$id_tulisan'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $text = $r1['isi'];

    preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $text, $img);
    $gambar = $img[1];

    return $gambar;
}

function ambil_kutipan($id_tulisan)
{
    global $koneksi;
    $sql1 = "select * from halaman where id = '$id_tulisan'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $text = $r1['kutipan'];
    return $text;
}
function ambil_waktu($id_tulisan)
{
    global $koneksi;
    $sql1 = "select * from halaman where id = '$id_tulisan'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $text = $r1['tgl_isi'];
    return $text;
}

function ambil_judul($id_tulisan)
{
    global $koneksi;
    $sql1 = "select * from halaman where id = '$id_tulisan'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $text = $r1['judul'];
    return $text;
}

function ambil_isi($id_tulisan)
{
    global $koneksi;
    $sql1 = "select * from halaman where id = '$id_tulisan'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $text = strip_tags($r1['isi']);
    return $text;
}

function ambil_penulis($id_tulisan)
{
    global $koneksi;
    $sql1 = "select * from halaman where id = '$id_tulisan'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $text = ($r1['penulis']);
    return $text;
}

function bersihkan_judul($judul)
{
    $judul_baru = strtolower($judul);
    $judul_baru = preg_replace("/[^a-zA-Z0-9\s]/", "", $judul_baru);
    $judul_baru = str_replace(" ", "-", $judul_baru);
    return $judul_baru;
}
function buat_link_halaman($id)
{
    global $koneksi;
    $sql1 = "select * from halaman where id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $judul = bersihkan_judul($r1['judul']);
    // http://localhost/website-company-profile/halaman.php/8/judul
    return url_dasar() . "/isi.php/$id/$judul";
}

function dapatkan_id()
{
    $id = "";
    if (isset($_SERVER['PATH_INFO'])) {
        $id = dirname($_SERVER['PATH_INFO']);
        $id = preg_replace("/[^0-9]/", "", $id);
    }
    return $id;
}

function set_isi($isi)
{
    $isi = str_replace("../img/", url_dasar() . " /img/ ", $isi);
    return $isi;
}

function potongkata($kalimat, $panjang)
{
    if (strlen($kalimat) > $panjang) {
        $kalimat = wordwrap($kalimat, $panjang);
        $kalimat = substr($kalimat, 0, strpos($kalimat, "\n")) . "...";
    }
    return $kalimat;
}

function news_gambar($id)
{
    global $koneksi;
    $sql1 = "select * from halaman where id ='$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $gambar = $r1['gambar'];

    return $gambar;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function kirim_email($email_penerima, $nama_penerima, $judul_email, $isi_email)
{

    $email_pengirim = "fahla491@gmail.com";
    $nama_pengirim = "noreply";
    //Load Composer's autoloader
    require getcwd() . '/vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
        $mail->isSMTP(); //Send using SMTP
        $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username = $email_pengirimi; //SMTP username
        $mail->Password = 'secret'; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($email_pengirim, $nama_pengirim);
        $mail->addAddress($email_penerima, $nama_penerima); //Add a recipient

        //Content
        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = $judul_email;
        $mail->Body = $isi_email;

        $mail->send();
        return "sukses";
    } catch (Exception $e) {
        return "gagal: {$mail->ErrorInfo}";
    }
}




?>