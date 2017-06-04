<?php include ROOT . '/views/layouts/header.php'; ?>

    <!-- content -->
    <div class="row login">
        <div class="col-md-6 col-md-offset-3 blog-posts">
            <form action="#" autocomplete="on" method="post">
                <h1>Войти</h1>
                <div class="field-with-error">
                    <input id="email" name="email" class="field-email" required="required" type="email"
                           value="<?php echo $email; ?>" placeholder="E-mail"/>
                    <div class="field-error"><p>Введите корректный адрес</p></div>
                </div>
                <div class="field-with-error">
                    <input id="password" name="password" class="field-pass" required="required" type="password"
                           placeholder="Пароль" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"/>
                    <div class="field-error">
                        <p>
                            Your password must be at least 6 characters as well as contain at least one uppercase, one
                            lowercase, and one number.
                        </p>
                    </div>
                </div>
                <?php if (isset($errors) && is_array($errors)): ?>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li> - <?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <button type="submit" name="submit" class="button">Войти</button>
            </form>
        </div>
    </div>

<?php include ROOT . '/views/layouts/footer.php'; ?>