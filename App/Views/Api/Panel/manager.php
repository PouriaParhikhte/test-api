<?php

use Core\Token;

include HEADER;
?>
<h1>manager</h1>
<form action="api/panel/login" method="post" id="panelLoginForm">
    <input type="text" name="username" placeholder="نام کاربری">
    <input type="password" name="password" placeholder="رمز عبور">
    <input type="submit" id="loginButton" value="ورود">
</form>
<div id="panelErrorMessage"><?= Token::decodePayload(Token::getPayload($_COOKIE['token']))->data->message ?? ''; ?></div>
<script src="assets/js/panel.js"></script>
<?php
include FOOTER;
