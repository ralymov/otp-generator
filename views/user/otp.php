<?php include ROOT . '/views/layouts/header.php'; ?>

    <!-- content -->
    <div class="row login">
        <div class="col-md-6 col-md-offset-3 blog-posts">
            <form action="#" autocomplete="off" method="post">
                <h1>Введите одноразовый пароль</h1>
                <div class="field-with-error">
                    <input id="otp" name="otp" class="field-pass" required="required" type="text"
                           pattern="([0-9]+){6}" placeholder="Одноразовый пароль"/>
                    <div class="field-error"><p>Пароль имеет длину 6 цифр</p></div>
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