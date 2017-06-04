<?php include ROOT . '/views/layouts/header.php'; ?>

    <!-- content -->
    <div class="row otp_add">
        <div class="col-md-4 col-md-offset-4">
            <form action="" autocomplete="off" method="post">
                <h1>Добавление otp генератора</h1>
                <div class="field-with-error">
                    <input id="serial" name="serial" class="field-serial" required="required" type="text" maxlength="12"
                           placeholder="Серийный номер" pattern="[A-Za-z0-9]{12}"/>
                    <div class="field-error"><p>Длина серийного номера - 12 символов</p></div>
                </div>
                <div class="field-with-error">
                    <input id="manufactureDate" name="manufactureDate" class="field-date" required="required"
                           type="date" placeholder="Дата изготовления"/>
                </div>
                <button type="submit" name="addOtp" class="button">Добавить</button>
            </form>
        </div>
    </div>

<?php include ROOT . '/views/layouts/footer.php'; ?>