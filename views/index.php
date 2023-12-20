<h1>Đây là trang chủ</h1>

<?php

if (isset($_SESSION['hovaten'])) {
    $hovaten = $_SESSION['hovaten'];
    echo "<div class='name-user'>$hovaten</div>";
    echo "<a href='?action=signOut'>Đăng xuất</a>";
} else {
    echo '<ul>
            <li><a href="?action=register">Đăng kí</a></li>
            <li><a href="?action=login">Đăng nhập</a></li>
        </ul>';
}
?>
