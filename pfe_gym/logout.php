<?php
session_start(); // ضروري باش نوصلو للـ session
session_unset(); // كتمسح كاع الـ variables ديال الـ session
session_destroy(); // كتدمر الـ session كاملة
header("Location: index.php"); // كترجع المستخدم للواجهة
exit();
?>