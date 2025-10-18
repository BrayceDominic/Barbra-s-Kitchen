<?php
$conn = new mysqli('localhost', 'root', '', 'eapp_db');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM services WHERE id='$id'";
    if ($conn->query($query)) {
        echo "<script>alert('Food deleted successfully'); window.location='admin_home.php';</script>";
    } else {
        echo "<script>alert('Failed to delete food'); window.location='admin_home.php';</script>";
    }
}
?>
