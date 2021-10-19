<!doctype html>
<html lang="ru">
<?php include ('blocks/head.php'); ?>
<body>
    <?php include('header.php'); ?>

    <form class="form" name="f_input3" id="f_input3" method="post" action="" enctype="multipart/form-data">
        <h1 class="form_title">ФОТО и ВИДЕО, ЗАДЕРЖАННОГО ТС</h1>
        <div class="form_group">
            <label class="form_label">Дата:</label>
            <input class="form_input" type="date" id="i3_date" required name="i3_date"  placeholder="Дата" value="<?php echo date("Y-m-d");?>">
        </div>

        <div class="form_group">
            <label class="form_label">Номер протокола: <b><u><?php echo SERNUM_PROTOCOL; ?></u> </b> </label>
            <input class="form_input" type="text" pattern="[0-9]{6}" required name="i3_protocol" placeholder="ВВЕДИТЕ НОМЕР ПРОТОКОЛА ЗАДЕРЖАНИЯ" maxlength="6" minlength="6" title="6 цифр">
        </div>


        <div class="form_group">
            <label class="form_label">Фото протокола:</label>
            <input class="form_input" type="file" id="file1" name="i3_protocol_scan" accept="image/jpeg" placeholder="Фото протокола">
        </div>
        <div class="form_group">
            <label class="form_label">Фото ТС:</label>
            <input class="form_input" type="file" id="file2" name="i3_tc_scan" accept="image/jpeg" placeholder="Фото ТС">
        </div>
        <button class="form_button" id="load_ftp" type="submit" name="load_ftp">Загрузить в архив</button>
<!---->
<!--        <script type="text/javascript">-->
<!--            if (window.jQuery) alert("jQuery подключен");-->
<!--            else alert("jQuery не подключен");-->
<!--        </script>-->
<!--        <script type="text/javascript">-->
<!--            $(document).ready(function(){-->
<!--                alert(jQuery.fn.jquery);-->
<!--            });-->
<!--        </script>-->

<!--        <div class="form_messase" id="form_message" style="display:none">-->
<!--            <div class="progress">-->
<!--                <span>Подождите, идёт загрузка сведений на сервер УГИБДД...</span>-->
<!--                <span id="success_message"></span>-->
<!--            </div>-->
<!--        </div>-->

    </form>

    <?php include "blocks/footer.php" ?>

    <script src="js/input3.js"></script>

</body>
</html>
