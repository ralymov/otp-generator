<?php include ROOT . '/views/layouts/header.php'; ?>

    <!-- content -->
    <div class="row cabinet">
        <div class="col-md-4 col-md-offset-4">
            <?php if (!$user['verified']): ?>
                <p>Подтвердите email</p>
            <?php else: ?>
                <form action="/cabinet/edit" autocomplete="on" method="post">
                    <h1>Личный кабинет</h1>
                    <div class="field-with-error">
                        <input id="name" name="name" class="field-name" required="required" type="text" maxlength="16"
                               value="<?php echo $user['name']; ?>" placeholder="Имя"/>
                        <div class="field-error">
                            <p>Максимум 16 символов</p>
                        </div>
                    </div>
                    <div class="field-with-error">
                        <input id="surname" name="surname" class="field-surname" required="required" maxlength="16"
                               value="<?php echo $user['surname']; ?>" type="text" placeholder="Фамилия"/>
                        <div class="field-error">
                            <p>Максимум 16 символов</p>
                        </div>
                    </div>
                    <div class="field-with-error">
                        <input id="birthdate" name="birthdate" class="field-birthdate" required="required" type="date"
                               value="<?php echo $user['birthdate']; ?>" placeholder="Дата рождения"/>
                    </div>
                    <div class="field-with-error">
                        <input id="nickname" name="nickname" class="field-nickname" required="required" type="text"
                               value="<?php echo $user['nickname']; ?>" maxlength="16" placeholder="Никнейм"/>
                        <div class="field-error">
                            <p>Максимум 16 символов</p>
                        </div>
                    </div>
                    <!-- поле с паролем
                <div class="field-with-error">
                    <input id="password" name="password" class="field-pass" required="required" type="password"
                           value = "<?php echo '******'; ?>" placeholder="Пароль" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"/>
                    <div class="field-error">
                        <p>Пароль должен содержать минимум 6 различных символов(хотя бы 1 заглавная, 1 строчная и 1
                            цифра)</p>
                    </div>
                </div>
                <div class="field-with-error">
                    <input id="password-conf" name="password-conf" class="field-pass" required="required"
                           type="password" placeholder="Подтверждение пароля"
                           pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"/>
                    <div class="field-error">
                        <p>Пароли не совпадают</p>
                    </div>
                </div>
                -->
                    <button type="submit" name="submit" class="button">Изменить данные</button>
                </form>
                <form action="/cabinet/otpadd" method="post">
                    <button type='submit' name="otp" class="button">Прикрепить OTP генератор</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

<?php include ROOT . '/views/layouts/footer.php'; ?>