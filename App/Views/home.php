<?php

use Core\Token;

include HEADER;
if (isset($content))
    foreach ($content as $index => $post) {
?>
    <h2><?= $post->title; ?></h2>
    <p><?= $post->content; ?></p>
<?php
    }
echo $pagination ?? null;
?>
<form action="api/user/signup" method="post">
    <label for="username">نام کاربری</label>
    <input type="text" name="username" id="username">
    <label for="password">رمز عبور</label>
    <input type="password" name="password" id="password">
    <input type="submit" value="ثبت نام">
</form>
<span id="signupErrorMessage"><?= Token::fetchValueFromPayload('signupErrorMessage'); ?></span>
<br><br>
<?php
if (!Token::fetchValueFromPayload('userId') && Token::fetchValueFromPayload('roleId') !== 2) {
?>
    <form action="api/user/login" method="post">
        <input type="text" name="username" placeholder="نام کاربری">
        <input type="password" name="password" placeholder="رمز عبور">
        <input type="submit" id="loginButton" value="ورود">
    </form>
    <span id="loginErrorMessage"><?= Token::fetchValueFromPayload('loginErrorMessage'); ?></span>
<?php
}
include FOOTER;
