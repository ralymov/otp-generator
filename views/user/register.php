<?php include ROOT . '/views/layouts/header.php'; ?>

    <!-- content -->
    <div class="row registration">
        <div class="col-md-6 col-md-offset-3">
            <?php if ($result): ?>
                <p>Вы зарегистрированы! На вашу почту было отправлено подтверждение email.</p>
            <?php else: ?>
                <form action="#" autocomplete="on" method="post">
                    <h1>Регистрация</h1>
                    <div class="field-with-error">
                        <input id="email" name="email" class="field-email" required="required" type="email"
                               value="<?php echo $email; ?>" placeholder="E-mail"/>
                        <div class="field-error"><p>Введите корректный адрес</p></div>
                    </div>
                    <div class="field-with-error">
                        <input id="nickname" name="nickname" class="field-nickname" required="required" type="text"
                               value="<?php echo $name; ?>" maxlength="16" placeholder="Никнейм"/>
                        <div class="field-error"><p>Максимум 16 символов</p></div>
                    </div>
                    <div class="field-with-error">
                        <input id="password" name="password" class="field-pass" required="required" type="password"
                               placeholder="Пароль" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"/>
                        <div class="field-error"><p>Пароль должен содержать минимум 6 различных символов(хотя бы 1
                                заглавная, 1 строчная и 1 цифра)</p></div>
                    </div>
                    <div class="field-with-error">
                        <input id="password-conf" name="password-conf" class="field-pass" required="required"
                               type="password" placeholder="Подтверждение пароля"
                               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"/>
                        <div class="field-error"><p>Пароли не совпадают</p></div>
                    </div>
                    <?php if (isset($errors) && is_array($errors)): ?>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li> - <?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <button type="submit" name="submit" class="button">Зарегистрироваться</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

<?php include ROOT . '/views/layouts/footer.php'; ?>