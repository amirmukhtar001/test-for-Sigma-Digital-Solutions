<?php

function redirect($url) {
    header("Location: $url");
    exit;
}

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>