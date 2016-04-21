<?php

/** @var \common\dto\UserDto $userDto */

?>

<h1>
    Finansez.com
</h1>
<h3>
    Благодарим вас за регистрацию!
</h3>
<p>Ваши регистрационные данные:</p>

<table border="1">
    <tr>
        <td>Логин</td>
        <td><?=$userDto->getUsername()?></td>
    </tr>
    <tr>
        <td>Пароль</td>
        <td><?=$userDto->getPassword()?></td>
    </tr>
</table>

