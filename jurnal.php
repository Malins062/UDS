<!doctype html>
<html lang="ru">
<?php
    require_once  ('blocks/head.php');
    include_once ('db/cnst.php');
?>

<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<!--<link rel="stylesheet" type="text/css" href="css/multiple-select.min.css">-->
<link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/font-glyphicons.css">

<script type="text/javascript" src="js/jquery.min.js"> </script>
<script type="text/javascript" src="js/bootstrap.bundle.js"> </script>
<!--<script type="text/javascript" src="js/multiple-select.min.js"> </script>-->
<script type="text/javascript" src="js/bootstrap-select.min.js"> </script>


<head>
    <style>
        #otrabotka {
            background: rgba(102, 102, 102, 0.5);
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            display: none;
            z-index: 20;
        }

        #add_foto {
            background: rgba(102, 102, 102, 0.5);
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            display: none;
            z-index: 20;
        }
    </style>

    <title>Недостатки УДС</title>
</head>


<body>
    <?php
        require_once "header.php";
    ?>
<!--    <select  id="meras" class="form-control selectpicker" title="Данные не выбраны" data-style="btn-white"-->
<!--            data-header="Выберите принятые меры" multiple>-->
<!--        <option value = 'c' data-subtext='b'>aaa</option>";-->
<!--        <option value = 'c' data-subtext='a'>bbb</option>";-->
<!--        <option value = 'c' data-subtext='b'>ccc</option>";-->
<!--    </select>-->

    <div id="otrabotka">
        <div class="form_group_otrabotka">
            <form class="form_group_insert was-validated" name="f_otrabotka" id="f_otrabotka" method="post" enctype="multipart/form-data">
                <div class="form-row col-md-12 justify-content-center">
                    <h5>Принятые меры по выбранному недостатку УДС (АКТ №<span id="akt_no" class="text-info"></span>)</h5>
                </div>

                <div class="form-group col-md-12">
                    <label for="o2_mera" class="col-xs-2 col-form-label">Вид принятых мер:</label>
                    <select required name="o2_mera" id="o2_mera" class="selectpicker form-control show-menu-arrow" title="Данные не выбраны"
                            data-header="Выберите принятые меры">
                        <?php
                        $conn = oci_connect(DB_USER, DB_PASS, DB_CONNECT, DB_CHARSET);
                        if (!$conn) {
                            $m = oci_error();
                            trigger_error(htmlentities($m['message'], ENT_QUOTES), E_USER_ERROR);
                        } else {
                            $textsql="select a.ID_MERA, a.MERA, a.PRIM from uds.MERS a WHERE a.ID_MERA>0 order by 1";
                            $res=OCI_Parse($conn,$textsql);
                            oci_execute($res);
                            $numRows=oci_fetch_all($res,$records, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                            foreach ($records as $row) {
                                echo "<option value = '".$row["ID_MERA"]."' data-subtext='".$row["PRIM"] ."'>". $row["MERA"] . "</option>";
                            }
                            oci_free_statement($res);
                            oci_close($conn);
                        }
                        ?>
                    </select>
                    <div class="invalid-feedback">Необходимо заполнить принятые меры</div>
                </div>

                <div class="form-group col-md-12">
                    <label for="o2_prim1" class="col-xs-2 col-form-label">Описание принятых мер:</label>
                    <input class="form-control" type="text" required name="o2_prim1" id="o2_prim1" maxlength="500" title="Не более 500 символов">
                    <div class="invalid-feedback">Необходимо заполнить описание принятых мер</div>
                </div>

                <div class="form-group col-md-12" style="display: none">
                    <label for="o2_id" class="col-xs-2 col-form-label"></label>
                    <input class="form-control" type="text" name="o2_id" id="o2_id" title="">
                </div>

                <div class="form-group col-md-6">
                    <label for="o2_file1" class="col-xs-2 col-form-label">Принятые меры (не более 3 Мб):</label>
                    <input class="form-control" type="file" id="o2_file1" name="o2_file1" accept="image/jpeg" placeholder="Принятые меры по недостатку УДС (размер не более 3 Мб)" title="Принятые меры по недостатку УДС (размер не более 3 Мб)">
                </div>

                <div class="form-group col-md-6">
                    <label for="o2_file2" class="col-xs-2 col-form-label">Принятые меры (не более 3 Мб):</label>
                    <input class="form-control" type="file" id="o2_file2" name="o2_file2" accept="image/jpeg" placeholder="Принятые меры по недостатку УДС (размер не более 3 Мб)" title="Принятые меры по недостатку УДС (размер не более 3 Мб)">
                </div>

                <div class="form-group col-md-12">
                    <div class="form_group_btn">
                        <button class="form_button otrabotka" style="width: 70%" type="submit" name="o_otrabotka" id="o_otrabotka">Внести данные в журнал</button>
                        <button class="form_button otrabotka_cancel" style="width: 20%; margin-left:10px" type="reset" onclick="close_otrabotka()">Отмена</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="add_foto">
        <div class="form_group_otrabotka">
            <form class="form_group_insert was-validated" name="f_add_foto" id="f_add_foto" method="post" enctype="multipart/form-data">
                <div class="form-row col-md-12 justify-content-center">
                    <h5>Добавить файлы(фотографии) <span id="f_foto" class="text-info"></span> в журнал (АКТ №<span id="f_akt_no" class="text-info"></span>)</h5>
                </div>

                <div class="form-group col-md-12" style="display: none">
                    <label for="f2_id" class="col-xs-2 col-form-label"></label>
                    <input class="form-control" type="text" name="f2_id" id="f2_id" title="">
                    <input class="form-control" type="text" name="f2_dir" id="f2_dir" title="">
                </div>

                <div class="form-group col-md-6">
                    <label for="f2_file1" class="col-xs-2 col-form-label">Файл 1 (не более 3 Мб):</label>
                    <input class="form-control" type="file" id="f2_file1" name="f2_file1" accept="image/jpeg" placeholder="Дополнительная информация (размер не более 3 Мб)" title="Дополнительная информация (размер не более 3 Мб)">
                </div>

                <div class="form-group col-md-6">
                    <label for="f2_file2" class="col-xs-2 col-form-label">Файл 2 (не более 3 Мб):</label>
                    <input class="form-control" type="file" id="f2_file2" name="f2_file2" accept="image/jpeg" placeholder="Дополнительная информация (размер не более 3 Мб)" title="Дополнительная информация (размер не более 3 Мб)">
                </div>

                <div class="form-group col-md-12">
                    <div class="form_group_btn">
                        <button class="form_button add_foto" style="width: 70%" type="submit" name="btn_add_foto" id="btn_add_foto">Отправить данные в журнал</button>
                        <button class="form_button add_foto_cancel" style="width: 20%; margin-left:10px" type="reset" onclick="close_add_foto()">Отмена</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="form_jurnal" id="jurnal">

        <!--  ----------------------------------------  ПОИСК ПО АДРЕСУ --------------------------------->
        <?php if ( ((isset($_GET['j'])) && ($_GET['j']=="gn")) || (isset($_GET['gn'])) ): ?>
            <form class="form_group_jurnal" name="form_search_gn" action="<?=$_SERVER['PHP_SELF'];?>" method="get">
                <label class="form_label">Введите адрес для поиска:</label>
                <div style="width: 450px; padding-right: 20px">
                    <input class="form_input" type="text" required name="gn" placeholder="АДРЕС" maxlength="50" title="% - любые символы, ? - один любой символ" value="<?php echo $_GET['gn']??''; ?>">
                </div>
                <label class="form_label">    </label>
                <button class="form_button_case" type="submit" name="search_addr">ПОИСК</button>
            </form>
        <?php endif; ?>

        <!--  ----------------------------------------  ПРОИЗВОЛЬНЫЙ ПОИСК  --------------------------------->
        <?php if ( ((isset($_GET['j'])) && ($_GET['j']=="dat")) || ( (isset($_GET['dat1'])) && (isset($_GET['dat2'])) ) ): ?>
            <form class="form_group_jurnal" name="form_search_gn" action="<?=$_SERVER['PHP_SELF'];?>" method="get">

                <div class="form-row col-md-12 justify-content-center">
                    <h5>Задайте параметры выборки данных из журнала: </h5>
                </div>

                <div class="form-row col-md-12">
                    <div class="form-group col-md-3">
                        <label for="dat1" class="col-xs-2 col-form-label">Начальная дата </label>
                        <div class="col-xs-10">
                            <input class="form-control" type="date" name="j_dat1" id="dat1">
                            <!--                                <input class="form-control" type="date" value="--><?php //echo $_GET['dat1']??''; ?><!--" id="dat1">-->
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="dat2" class="col-xs-2 col-form-label">Конечная дата </label>
                        <div class="col-xs-10">
                            <input class="form-control" type="date" name="j_dat2" id="dat2">
                            <!--                                <input class="form-control" type="date" value="--><?php //echo $_GET['dat2']??''; ?><!--" id="dat2">-->
                        </div>
                    </div>
                    <div class="form-group col-md-6" style="padding-top: 5px">
                        <label for="j_gibdd">Подразделение ГИБДД</label>
                        <select id="j_gibdd" name="j_gibdd[]" class="selectpicker w-100 show-menu-arrow"  title="Все подразделения"  data-selected-text-format="count" data-header="Выберите подразделение" data-width="100%" data-container="form_group" data-live-search="true" multiple>
                            <!--                            <option selected disabled>Выберите подразделение</option>-->
                            <?php
                            $conn = oci_connect(DB_USER, DB_PASS, DB_CONNECT, DB_CHARSET);
                            if (!$conn) {
                                $m = oci_error();
                                trigger_error(htmlentities($m['message'], ENT_QUOTES), E_USER_ERROR);
                            } else {
                                $textsql="select b.NAME from UDS.DEPTS b order by 1";
                                $res=OCI_Parse($conn,$textsql);
                                oci_execute($res);
                                $numRows=oci_fetch_all($res,$records, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                                foreach ($records as $row) {
                                    echo "<option value = '". $row["NAME"] ."'>".$row["NAME"] ."</option>";
                                }
                                oci_free_statement($res);
                                oci_close($conn);
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-row col-md-12">
                    <div class="form-group col-md-6">
                        <label for="j_inspector">Инспектор</label>
                        <select id="j_inspector" name="j_inspector[]" class="selectpicker w-100 show-menu-arrow" title="Все инспектора" data-selected-text-format="count" data-header="Выберите должностное лицо" data-width="100%" data-container="form_group" data-live-search="true" multiple>
                            <?php
                            $conn = oci_connect(DB_USER, DB_PASS, DB_CONNECT, DB_CHARSET);
                            if (!$conn) {
                                $m = oci_error();
                                trigger_error(htmlentities($m['message'], ENT_QUOTES), E_USER_ERROR);
                            } else {
                                $textsql="select a.ID_USER, a.FIO, a.ZNAK, b.NAME from uds.users a left join UDS.DEPTS b on b.ID_DEPT = a.ID_DEPT order by 2";
                                $res=OCI_Parse($conn,$textsql);
                                oci_execute($res);
                                $numRows=oci_fetch_all($res,$records, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                                foreach ($records as $row) {
                                    echo "<option value = '".$row["FIO"]."' data-subtext='".$row["ZNAK"] . ' * ' .$row["NAME"] ."'>". $row["FIO"] . "</option>";
                                }
                                oci_free_statement($res);
                                oci_close($conn);
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="j_status">Статус</label>
                        <select id="j_status" name="j_status[]" class="selectpicker w-100 show-menu-arrow" title="Все карточки" data-header="Статус карточек" data-width="100%" data-container="form_group" multiple>
                            <!--                        <select id="j_status" class="selectpicker" multiple>-->
                            <!--            <select required name="i2_raion" id="i2_raion" class="selectpicker w-25">-->
                            <!--                            <option selected disabled>Выберите статус</option>-->
                            <option value = "0">Текущие</option>
                            <option value = "1">Архивные</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="j_raion">Район</label>
                        <select id="j_raion" name="j_raion[]" class="selectpicker w-100 show-menu-arrow" title="Все районы"  data-selected-text-format="count" data-header="Выберите район" data-container="form_group" data-live-search="true"  multiple>
                            <!--                        <select id="j_raion" class="multi_select w-100">-->
                            <!--            <select required name="i2_raion" id="i2_raion" class="selectpicker w-25">-->
                            <!--                            <option selected disabled>Выберите район</option>-->
                            <optgroup label="Г.РЯЗАНЬ">
                                <option value = "МОСКОВСКИЙ">МОСКОВСКИЙ</option>
                                <option value = "ЖЕЛЕЗНОДОРОЖНЫЙ">ЖЕЛЕЗНОДОРОЖНЫЙ</option>
                                <option value = "ОКТЯБРЬСКИЙ">ОКТЯБРЬСКИЙ</option>
                                <option value = "СОВЕТСКИЙ">СОВЕТСКИЙ</option>
                            </optgroup>
                            <optgroup label="ОБЛАСТЬ">
                                <option value = "ЕРМИШИНСКИЙ">ЕРМИШИНСКИЙ</option>
                                <option value = "ЗАХАРОВСКИЙ">ЗАХАРОВСКИЙ</option>
                                <option value = "КАДОМСКИЙ">КАДОМСКИЙ</option>
                                <option value = "КАСИМОВСКИЙ">КАСИМОВСКИЙ</option>
                                <option value = "КЛЕПИКОВСКИЙ">КЛЕПИКОВСКИЙ</option>
                                <option value = "КОРАБЛИНСКИЙ">КОРАБЛИНСКИЙ</option>
                                <option value = "МИЛОСЛАВСКИЙ">МИЛОСЛАВСКИЙ</option>
                                <option value = "МИХАЙЛОВСКИЙ">МИХАЙЛОВСКИЙ</option>
                                <option value = "НОВОДЕРЕВЕНСКИЙ">НОВОДЕРЕВЕНСКИЙ</option>
                                <option value = "ПИТЕЛИНСКИЙ">ПИТЕЛИНСКИЙ</option>
                                <option value = "ПРОНСКИЙ">ПРОНСКИЙ</option>
                                <option value = "ПУТЯТИНСКИЙ">ПУТЯТИНСКИЙ</option>
                                <option value = "РЫБНОВСКИЙ">РЫБНОВСКИЙ</option>
                                <option value = "РЯЖСКИЙ">РЯЖСКИЙ</option>
                                <option value = "РЯЗАНСКИЙ">РЯЗАНСКИЙ</option>
                                <option value = "САПОЖКОВСКИЙ">САПОЖКОВСКИЙ</option>
                                <option value = "САРАЕВСКИЙ">САРАЕВСКИЙ</option>
                                <option value = "САСОВСКИЙ">САСОВСКИЙ</option>
                                <option value = "СКОПИНСКИЙ">СКОПИНСКИЙ</option>
                                <option value = "СПАССКИЙ">СПАССКИЙ</option>
                                <option value = "СТАРОЖИЛОВСКИЙ">СТАРОЖИЛОВСКИЙ</option>
                                <option value = "УХОЛОВСКИЙ">УХОЛОВСКИЙ</option>
                                <option value = "ЧУЧКОВСКИЙ">ЧУЧКОВСКИЙ</option>
                                <option value = "ШАЦКИЙ">ШАЦКИЙ</option>
                                <option value = "ШИЛОВСКИЙ">ШИЛОВСКИЙ</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="form-row col-md-12">
                    <div class="form-group col-md-12">
                        <label for="j_ndu" class="col-xs-2 col-form-label">Вид НДУ:</label>
                        <select name="j_ndu[]" id="j_ndu" class="form-control selectpicker w-100 show-menu-arrow" title="Данные не выбраны" data-selected-text-format="count"
                                data-header="Выберите недостатки НДУ:" data-live-search="true" multiple>
                            <optgroup label="ЭКСПЛУАТАЦИЯ УДС">
                                <option value = "1" disabled>1. Дефекты покрытия проезжей части</option>
                                <option value = "11">1.1. Нарушение требований к продольной ровности</option>
                                <option value = "12">1.2. Выбоина</option>
                                <option value = "13">1.3. Просадка</option>
                                <option value = "14">1.4. Пролом</option>
                                <option value = "15">1.5. Сдвиг, волна</option>
                                <option value = "16">1.6. Гребенка</option>
                                <option value = "17">1.7. Колея</option>
                                <option value = "18">1.8. Отдельное необработанное место выпотевания вяжущего</option>
                                <option value = "19">1.9. Отклонение по вертикали крышки люка относительно поверхности проезжей части</option>
                                <option value = "110">1.10. Отклонение по вертикали решетки дождеприемника относительно поверхности лотка</option>
                                <option value = "111">1.11. Отклонение по вертикали верха головки рельса трамвайных путей, расположенных в пределах проезжей части, относительно поверхности покрытия</option>
                                <option value = "112">1.12. Неровность в покрытии междурельсового пространства (настила) трамвайных путей</option>
                                <option value = "113">1.13. Выступы или углубления в зоне деформационных швов</option>
                                <option value = "114">1.14. Разрушение/отсутствие крышек люков и решеток дождеприемников</option>
                                <option value = "115">1.15. Низкие сцепные качества покрытия проезжей части</option>
                                <option value = "20">2. Загрязнение дорог и улиц</option>
                                <option value = "33">3. Дефекты тротуаров, пешеходных дорожек, посадочных площадок остановочных пунктов и наземных тактильных указателей</option>
                                <option value = "40" disabled>4. Нарушения требований к эксплуатационному состоянию дорог в зимний период</option>
                                <option value = "41">4.1. Наличие рыхлого или талого снега в нарушение установленных требований</option>
                                <option value = "42">4.2. Наличие зимней скользкости в нарушение установленных требований на дорогах, эксплуатируемых без уплотненного снежного покрова</option>
                                <option value = "43">4.3. Нарушение требований к состоянию обочин в зимний период</option>
                                <option value = "44">4.4. Нарушение требований к состояние тротуаров и пешеходных дорожек в зимний период</option>
                                <option value = "45">4.5. Нарушение требований к состоянию элементов обустройства в зимний период</option>
                                <option value = "46">4.6. Нарушение требований к размещению снежных валов</option>
                                <option value = "47">4.7. Нарушение допустимых требований к дорогам с уплотненным снежным покровом (УСП)</option>
                                <option value = "50">5. Наличие на дороге посторонних предметов</option>
                                <option value = "60" disabled>6. Дефекты разделительной полосы</option>
                                <option value = "61">6.1. Занижение разделительной полосы</option>
                                <option value = "62">6.2. Возвышение разделительной полосы</option>
                                <option value = "63">6.3. Повреждения разделительных полос и полос безопасности</option>
                                <option value = "64">6.4. Превышение поперечного уклона разделительных полос относительно нормативного значения</option>
                                <option value = "65">6.5. Нарушение требований к показателям ровности полос безопасности у разделительной полосы</option>
                                <option value = "70" disabled>7. Дефекты обочины</option>
                                <option value = "71">7.1. Занижение обочины</option>
                                <option value = "72">7.2. Возвышение обочины</option>
                                <option value = "73">7.3. Повреждения краевых полос, укрепленных обочин и обочин с дорожной одеждой переходного типа</option>
                                <option value = "74">7.4. Повреждения (деформации и разрушения) неукрепленных обочин</option>
                                <option value = "75">7.5. Растительность на обочине</option>
                                <option value = "76">7.6. Превышение поперечного уклона обочины относительно нормативного значения</option>
                                <option value = "77">7.7. Нарушение требований к показателям ровности полос безопасности у разделительной полосы, укрепительных полос у обочин</option>
                                <option value = "80" disabled>8. Дефекты горизонтальной дорожной разметки</option>
                                <option value = "81">8.1. Износ и разрушение разметки 1.14.1, 1.14.2</option>
                                <option value = "82">8.2. Изменение светотехнических характеристик разметки 1.14.1, 1.14.2</option>
                                <option value = "83">8.3. Неправильное применение разметки 1.14.1, 1.14.2</option>
                                <option value = "84">8.4. Отсутствие разметки 1.14.1, 1.14.2</option>
                                <option value = "85">8.5. Износ и разрушение разметки всех типов, за исключением разметки 1.14.1, 1.14.2</option>
                                <option value = "86">8.6. Изменение светотехнических характеристик разметки всех типов, за исключением разметки 1.14.1, 1.14.2</option>
                                <option value = "87">8.7. Неправильное применение разметки всех типов, за исключением разметки 1.14.1, 1.14.2</option>
                                <option value = "88">8.8. Отсутствие разметки всех типов, за исключением разметки 1.14.1, 1.14.2</option>
                                <option value = "90" disabled>9. Дефекты вертикальной дорожной разметки</option>
                                <option value = "91">9.1. Износ и разрушение вертикальной разметки</option>
                                <option value = "92">9.2. Изменение светотехнических характеристик вертикальной разметки</option>
                                <option value = "93">9.3. Неправильное применение вертикальной разметки</option>
                                <option value = "94">9.4. Отсутствие вертикальной разметки</option>
                                <option value = "1000" disabled>10. Дефекты искусственных неровностей</option>
                                <option value = "1010">10.1. Нарушение целостности конструкции сборно-разборной искусственной неровности</option>
                                <option value = "1020">10.2. Дефекты монолитной искусственной неровности</option>
                                <option value = "1030">10.3. Несоответствие геометрических параметров искусственных неровностей нормативным значениям</option>
                                <option value = "1100">11. Застой воды на проезжей части и/или обочинах</option>
                                <option value = "1200">12. Не обеспечена необходимая видимость в зоне треугольников видимости</option>
                                <option value = "1300" disabled>13. Дефекты дорожных светофоров</option>
                                <option value = "1310">13.1. Неработающий сигнал (сигналы) светофора</option>
                                <option value = "1320">13.2. Нарушение целостности элементов светофора</option>
                                <option value = "1330">13.3. Снижение восприятия сигналов светофора</option>
                                <option value = "1340">13.4. Изменение положения светофора</option>
                                <option value = "1350">13.5. Сбой в работе светофорного объекта</option>
                                <option value = "1360">13.6. Неработающий звуковой сигнал, дублирующий разрешающий сигнал светофора</option>
                                <option value = "1370">13.7. Отказ в работе табло вызывного пешеходного</option>
                                <option value = "1380">13.8. Неправильное применение светофоров</option>
                                <option value = "1390">13.9. Ограничение видимости светофоров</option>
                                <option value = "1400">14. Наличие съездов в неустановленных местах</option>
                                <option value = "1500" disabled>15. Дефекты дорожных знаков</option>
                                <option value = "1510">15.1. Утрата дорожного знака</option>
                                <option value = "1520">15.2. Нарушение целостности лицевой поверхности</option>
                                <option value = "1530">15.3. Изменение светотехнических характеристик</option>
                                <option value = "1540">15.4. Изменение положения знака</option>
                                <option value = "1550">15.5. Ограничение видимости дорожных знаков</option>
                                <option value = "1560">15.6. Неправильное применение дорожных знаков</option>
                                <option value = "1570">15.7. Отсутствие дорожных знаков</option>
                                <option value = "1600" disabled>16. Дефекты бортового камня</option>
                                <option value = "1610">16.1. Повреждение бортового камня </option>
                                <option value = "1620">16.2. Нарушение положения бортового камня</option>
                                <option value = "1700" disabled>17. Дефекты дорожных ограждений</option>
                                <option value = "1710">17.1. Отсутствие элементов конструкции металлических дорожных ограждений разделяющих транспортные потоки встречных направлений</option>
                                <option value = "1720">17.2. Повреждение элементов конструкции металлических или железобетонных ограждений, разделяющих транспортные потоки встречных направлений</option>
                                <option value = "1730">17.3. Нарушение целостности конструкции металлических ограждений, разделяющих транспортные потоки встречных направлений </option>
                                <option value = "1740">17.4. Нарушение правил применения ограждений, разделяющих транспортные потоки встречных направлений</option>
                                <option value = "1750">17.5. Отсутствие элементов конструкции металлического дорожного ограждения на краю проезжей части</option>
                                <option value = "1760">17.6. Повреждение элементов конструкции металлических или железобетонных ограждений на краю проезжей части</option>
                                <option value = "1770">17.7. Нарушение целостности конструкции металлических ограждений на краю проезжей части</option>
                                <option value = "1780">17.8. Нарушение правил применения ограждений на краю проезжей части</option>
                                <option value = "1800" disabled>18. Дефекты пешеходных ограждений</option>
                                <option value = "1810">18.1. Отсутствие элемента пешеходного ограждения</option>
                                <option value = "1820">18.2. Повреждения элементов удерживающего пешеходного ограждения</option>
                                <option value = "1830">18.3. Нарушение правил применения пешеходных ограждений</option>
                                <option value = "1900" disabled>19. Дефекты дорожных сигнальных столбиков</option>
                                <option value = "1910">19.1. Утрата столбика</option>
                                <option value = "1920">19.2. Повреждение конструкции столбика</option>
                                <option value = "1930">19.3. Плохая различимость столбика</option>
                                <option value = "1940">19.4. Неправильное применение столбиков</option>
                                <option value = "1950">19.5. Отсутствие столбиков в необходимых местах</option>
                                <option value = "2000" disabled>20. Дефекты дорожных тумб</option>
                                <option value = "2010">20.1. Утрата дорожной тумбы</option>
                                <option value = "2020">20.2. Повреждение конструкции дорожной тумбы</option>
                                <option value = "2030">20.3. Плохая различимость дорожной тумбы</option>
                                <option value = "2040">20.4. Неправильное применение дорожных тумб</option>
                                <option value = "2050">20.5. Отсутствие дорожных тумб</option>
                                <option value = "2100" disabled>21. Дефекты дорожных световозвращателей</option>
                                <option value = "2110">21.1. Утрата световозвращателя либо световозвращающего элемента</option>
                                <option value = "2120">21.2. Нарушение целостности лицевой поверхности</option>
                                <option value = "2130">21.3. Изменение светотехнических характеристик (в т.ч. по причине загрязнений)</option>
                                <option value = "2140">21.4. Отсутствие дорожных световозвращателей</option>
                                <option value = "2200" disabled>22. Нарушения обустройства мест производства дорожных работ</option>
                                <option value = "2210">22.1. Нарушения правил применения временных дорожных знаков</option>
                                <option value = "2220">22.2. Отсутствие временных дорожных знаков</option>
                                <option value = "2230">22.3. Нарушения правил применения временных светофоров</option>
                                <option value = "2240">22.4. Отсутствие временных светофоров</option>
                                <option value = "2250">22.5. Нарушения правил применения временной разметки</option>
                                <option value = "2260">22.6. Отсутствие временной разметки</option>
                                <option value = "2270">22.7. Нарушения правил применения временных дорожных направляющих устройств</option>
                                <option value = "2280">22.8. Отсутствие временных дорожных направляющих устройств</option>
                                <option value = "2290">22.9. Нарушения правил применения временных дорожных ограждающих устройств</option>
                                <option value = "22100">22.10. Отсутствие временных дорожных ограждающих устройств</option>
                                <option value = "22110">22.11. Нарушения правил применения сигнальных фонарей</option>
                                <option value = "22120">22.12. Отсутствие сигнальных фонарей</option>
                                <option value = "22130">22.13. Нарушения правил применения динамических информационных табло</option>
                                <option value = "22140">22.14. Отсутствие динамических информационных табло</option>
                                <option value = "22150">22.15. Нарушения правил применения передвижных комплексов временных технических средств</option>
                                <option value = "22160">22.16. Отсутствие передвижных комплексов временных технических средств</option>
                                <option value = "22170">22.17. Нарушения правил применения автомобилей прикрытия</option>
                                <option value = "22180">22.18. Отсутствие автомобилей прикрытия</option>
                                <option value = "22190">22.19. Нарушения правил применения информационных щитов</option>
                                <option value = "22200">22.20. Отсутствие информационных щитов</option>
                                <option value = "22210">22.21. Не убраны временные технические средства организации дорожного движения</option>
                                <option value = "2300" disabled>23. Дефекты стационарного электрического освещения</option>
                                <option value = "2310">23.1. Наличие неработающих светильников</option>
                                <option value = "2320">23.2. Отказы в работе наружных осветительных установок (отключение освещения)</option>
                                <option value = "2330">23.3. Недостаточная освещенность</option>
                                <option value = "2400" disabled>24. Нарушение требований к эксплуатационному состоянию железнодорожных переездов</option>
                                <option value = "2410">24.1. Нарушение требований к обустройству переездов системами сигнализации</option>
                                <option value = "2420">24.2. Неисправности либо отсутствие шлагбаумов и/или заграждений дорожных</option>
                                <option value = "2430">24.3. Не обеспечена необходимая видимость на подъезде к железнодорожному переезду</option>
                                <option value = "2440">24.4. Нарушения требований к эксплуатационному состоянию дороги на подъезде к железнодорожному переезду в зимний период</option>
                                <option value = "2450">24.5. Отклонение по вертикали верха головки рельса железнодорожных путей, расположенных в пределах проезжей части, относительно поверхности покрытия</option>
                                <option value = "2460">24.6. Возвышение междурельсового настила над верхом рельсов на железнодорожных переездах</option>
                                <option value = "2470">24.7. Неровность в покрытии междурельсового пространства (настила) железнодорожного переезда</option>
                                <option value = "2480">24.8. Нарушение требований к обустройству переездов дорожными знаками</option>
                                <option value = "2490">24.9. Нарушение требований к обустройству переездов дорожной разметкой</option>
                                <option value = "24100">24.10. Нарушение требований к обустройству переездов светофорами</option>
                                <option value = "24110">24.11. Нарушение требований к обустройству переездов сигнальными столбиками</option>
                            </optgroup>
                            <optgroup label="ОБУСТРОЙСТВО УДС">
                                <option value = "111000">111. Отсутствие элементов остановочных пунктов маршрутных транспортных средств</option>
                                <option value = "112000">112. Нарушения правил размещения остановочных пунктов маршрутных транспортных средств</option>
                                <option value = "113000">113. Отсутствие стационарного электрического освещения в необходимых местах</option>
                                <option value = "114000">114. Отсутствие тротуаров/ пешеходных дорожек в необходимых местах </option>
                                <option value = "115000">115. Отсутствие дорожных светофоров в необходимых местах</option>
                                <option value = "116000">116. Отсутствие либо недостаточная ширина обочин</option>
                                <option value = "117000">117. Несоответствие параметров шумовых полос нормативным требованиям</option>
                                <option value = "118000">118. Несоблюдение требований к минимальной ширине проезжей части в зоне железнодорожного переезда</option>
                                <option value = "119000">119. Отсутствие горизонтальной площадки с твердым покрытием перед железнодорожным переездом в 10-ти метровой зоне от крайнего рельса</option>
                                <option value = "120000">120. Отсутствие дорожных зеркал при несоблюдении условий видимости</option>
                                <option value = "121000">121. Отсутствие ограждений в необходимых местах </option>
                                <option value = "122000">122. Отсутствие пешеходных ограждений в необходимых местах</option>
                                <option value = "123000">123. Отсутствие необходимой полосы безопасности перед ограждением</option>
                                <option value = "124000">124. Отсутствие недостающих переходно-скоростных полос</option>
                                <option value = "125000">125. Отсутствие искусственных неровностей</option>
                                <option value = "126000">126. Недостаточная ширина полосы движения</option>
                                <option value = "127000">127. Нарушения требований безопасности при обустройстве автодорожных тоннелей</option>
                                <option value = "128000" disabled>128. Нарушения в расположении средств наружной рекламы</option>
                                <option value = "128001">128.1. Установка и эксплуатация рекламной конструкции без разрешения</option>
                                <option value = "128002">128.2. Ослепление участников дорожного движения светом, в том числе отраженным</option>
                                <option value = "128003">128.3. Ограничение видимости технических средств регулирования дорожного движения или снижение эффективности</option>
                                <option value = "128004">128.4. Сходство (по внешнему виду, изоюражению или звуковому эффекту) с техническими средствами организации дорожного движения и специальными сигналами</option>
                                <option value = "128005">128.5. Размещение на дорожном знаке, его опоре или на любом другом приспособлении, предназанченном для регулирования движения</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="form-row col-md-12">
                    <div class="form-group col-md-12">
                        <label for="j_addr" class="col-xs-2 col-form-label">Адрес, зафиксированного недостатка УДС:</label>
                        <input class="form-control" type="text" name="j_addr" id="j_addr" placeholder="Любой адрес" maxlength="100" title="% - любые символы, ? - один любой символ">
                    </div>
                </div>
                <!--                    <div class="col-md-12 mb-md-2 justify-content-md-center" style="border: dashed">-->

                <div class="form_group_btn form-row col-md-12">
                    <button class="form_button" style="width: 100%" type="submit" name="search">ПОИСК</button>
                </div>
            </form>
        <?php endif; ?>

<!--  ----------------------------------------  ЗАГОЛОВОК ТАБЛИЦЫ --------------------------------->
        <?php if (!isset($_GET['j']) || !($_GET['j']=="dat")): ?>
            <div class="jurnal_table">
                <?php
                    echo "<h5>" . TITLE_JURNAL . "<button title='Загрузить журнал в MS Word...' type='button' style='z-index: 0;' class='btn-sm btn btn-outline-primary jrn_word' id='";
                    if (isset($_GET['j'])) {
                        echo $_GET['j'] . "'><span class='glyphicon glyphicon-download'></span></button></h5>";
        //                    echo "<a href='#' id=" . $_GET['j'] . " class='jrn_word'><img class='foto-img' src='img/word1.gif' onmouseout=\"this.src='img/word1.gif';\" onmouseover=\"this.src='img/word2.gif';\" alt='MS Word'></a></h5>";
                    } else {
                        if (isset($_GET['gn'])) {
                            echo $_GET['gn'] . "'><span class='glyphicon glyphicon-download'></span></button></h5>";
        //                        echo "<a href='#' id=" . $_GET['gn'] . " class='jrn_word'><img class='foto-img' src='img/word1.gif' onmouseout=\"this.src='img/word1.gif';\" onmouseover=\"this.src='img/word2.gif';\" alt='MS Word'></a></h5>";
                        }
                    }

                    if ( ((isset($_GET['j'])) && ($_GET['j']=="gn")) || (isset($_GET['gn'])) ){
                        if (isset($_GET['gn'])) {
                            echo "<span id='s_title' class='s_title'>".TITLE_JURNAL4. $_GET['gn']."</span>";
                        } else {
                            echo "<span id='s_title' class='s_title'>" . TITLE_JURNAL4 . "</span>";
                        }
                        echo "<span id='sql' style='display: none;'> </span>";
                    }
                    //            elseif ( ((isset($_GET['j'])) && ($_GET['j']=="dat")) || ( (isset($_GET['dat1'])) && (isset($_GET['dat2'])) ) ){
                    elseif (isset($_GET['search'])) {
                        echo "search'><span class='glyphicon glyphicon-download'></span></button></h5>";
        //                    echo "<a href='#' id=" . $_GET['search'] . " class='jrn_word'><img class='foto-img' src='img/word1.gif' onmouseout=\"this.src='img/word1.gif';\" onmouseover=\"this.src='img/word2.gif';\" alt='MS Word'></a></h5>";
                        echo "<br> <span id='s_title' class='s_title'>" . TITLE_JURNAL5;
                        if ( (isset($_GET['j_dat1'])) || (isset($_GET['j_dat2'])) ) {
                            if (($_GET['j_dat1']<>null) && ($_GET['j_dat2']<>null)) {
                                $d1 = date("d.m.Y", strtotime($_GET['j_dat1']));
                                $d2 = date("d.m.Y", strtotime($_GET['j_dat2']));
                                echo " за период c " . $d1 . " по " . $d2 . ";";
                            }
                            if (($_GET['j_dat1']<>null) && ($_GET['j_dat2']==null)) {
                                $d1 = date("d.m.Y", strtotime($_GET['j_dat1']));
                                $d2 = date("d.m.Y");
                                echo " за период c " . $d1 . " по " . $d2 . ";";
                            }
                            if (($_GET['j_dat1']==null) && ($_GET['j_dat2']<>null)) {
                                $d2 = date("d.m.Y", strtotime($_GET['j_dat2']));
                                echo " за весь период по " . $d2 . ";";
                            }
                            if (($_GET['j_dat1']==null) && ($_GET['j_dat2']==null)) {echo " за весь период времени;";}
                        } else {echo " за весь период времени;";}

                        if (isset($_GET['j_gibdd'])) {
                            echo " подразделения (" ;
                            $jj=0;
                            foreach ($_GET['j_gibdd'] as $value) {
                                if ($jj>0) echo ", ";
                                echo $value;
                                $jj++;
                            }
                            echo ")" ;
                        } else {echo " все подразделения;";}
                        if (isset($_GET['j_inspector'])) {
                            echo " инспектора (" ;
                            $jj=0;
                            foreach ($_GET['j_inspector'] as $value) {
                                if ($jj>0) echo ", ";
                                echo $value;
                                $jj++;
                            }
                            echo ")" ;
                        } else {echo " все инспектора;";}

                        if (isset($_GET['j_ndu'])) {
                            echo " виды недостатков (" ;

                            $conn = oci_connect(DB_USER, DB_PASS, DB_CONNECT, DB_CHARSET);
                            if (!$conn) {
                                $m = oci_error();
                                trigger_error(htmlentities($m['message'], ENT_QUOTES), E_USER_ERROR);
                            } else {
                                $textsql = "select a.name  from uds.ndu a ";
                                $textsql = $textsql . "where a.NDU in ('";
                                $jj = 0;
                                foreach ($_GET['j_ndu'] as $value){
                                    if ($jj > 0) $textsql = $textsql . ", '";
                                    $textsql = $textsql . $value . "'";
                                    $jj++;
                                }
                                $textsql = $textsql . ") order by 1";

                                $res=OCI_Parse($conn,$textsql);
                                oci_execute($res);
                                $numRows=oci_fetch_all($res,$records, null, null, OCI_FETCHSTATEMENT_BY_ROW);

                                $jj=0;
                                foreach ($records as $row) {
                                    if ($jj>0) echo ", ";
                                    echo $row["NAME"];
                                    $jj++;
                                }
                                oci_free_statement($res);
                                oci_close($conn);
                            }

                            echo ")" ;
                        } else {echo " все виды недостатков;";}

                        if (isset($_GET['j_status'])) {
                            echo " типы карточек (" ;
                            $jj=0;
                            foreach ($_GET['j_status'] as $value) {
                                if ($jj>0) echo ", ";
                                switch ($value) {
                                    case "1":
                                        echo "архивные";
                                        break;
                                    case "0":
                                        echo "текущие";
                                        break;
                                }
                                $jj++;
                            }
                            echo ");" ;
                        } else {echo " все карточки;";}

                        if (isset($_GET['j_addr']) && ($_GET['j_addr']<>null)) {
                            echo ' по адресу - "'.$_GET['j_addr'].'";';
                        }

                        if (isset($_GET['j_raion'])) {
                            echo " районы (" ;
                            $jj=0;
                            foreach ($_GET['j_raion'] as $value) {
                                if ($jj>0) echo ", ";
                                echo $value;
                                $jj++;
                            }
                            echo ")." ;
                        } else {echo " все районы области.";}

                        echo "</span>";

                        // ------------- Создание SQL для Word -------------------------
                        $sqlWhere=" where ";
                        $usl=0;
                        if (!($_GET['j_dat1']==null)) {
                            $d1=date("d.m.Y", strtotime($_GET['j_dat1']));
                            $sqlWhere=$sqlWhere."a.date_z >= to_date('".$d1."','dd.mm.yyyy')";
                            $usl++;
                        }
                        if (!($_GET['j_dat2']==null)) {
                            $d2=date("d.m.Y",strtotime("+1 days", strtotime($_GET['j_dat2'])));
                            if ($usl>0) { $sqlWhere = $sqlWhere . " and ";}
                            $sqlWhere=$sqlWhere."a.date_z < to_date('".$d2."','dd.mm.yyyy')";
                            $usl++;
                        }

                        if (isset($_GET['j_gibdd'])) {
                            if ($usl>0) { $sqlWhere = $sqlWhere . " and ";}
                            $sqlWhere = $sqlWhere . " a.DEPT in ('";
                            $usl++;
                            $jj = 0;
                            foreach ($_GET['j_gibdd'] as $v){
                                if ($jj > 0) $sqlWhere = $sqlWhere . ", '";
                                $sqlWhere = $sqlWhere . $v . "'";
                                $jj++;
                            }
                            $sqlWhere = $sqlWhere . ")";
                        }

                        if (isset($_GET['j_raion'])) {
                            if ($usl>0) { $sqlWhere = $sqlWhere . " and ";}
                            $sqlWhere = $sqlWhere . " a.RAION in ('";
                            $usl++;
                            $jj = 0;
                            foreach ($_GET['j_raion'] as $v){
                                if ($jj > 0) $sqlWhere = $sqlWhere . ", '";
                                $sqlWhere = $sqlWhere . $v . "'";
                                $jj++;
                            }
                            $sqlWhere = $sqlWhere . ")";
                        }

                        if (isset($_GET['j_inspector'])) {
                            if ($usl>0) { $sqlWhere = $sqlWhere . " and ";}
                            $sqlWhere = $sqlWhere . " a.INSPECTOR in ('";
                            $usl++;
                            $jj = 0;
                            foreach ($_GET['j_inspector'] as $v){
                                if ($jj > 0) $sqlWhere = $sqlWhere . ", '";
                                $sqlWhere = $sqlWhere . $v . "'";
                                $jj++;
                            }
                            $sqlWhere = $sqlWhere . ")";
                        }

                        if (isset($_GET['j_ndu'])) {
                            if ($usl>0) { $sqlWhere = $sqlWhere . " and ";}
                            $sqlWhere = $sqlWhere . " c.NDU in ('";
                            $usl++;
                            $jj = 0;
                            foreach ($_GET['j_ndu'] as $v){
                                if ($jj > 0) $sqlWhere = $sqlWhere . ", '";
                                $sqlWhere = $sqlWhere . $v . "'";
                                $jj++;
                            }
                            $sqlWhere = $sqlWhere . ")";
                        }

                        if (isset($_GET['j_status'])) {
                            if ($usl>0) { $sqlWhere = $sqlWhere . " and ";}
                            $sqlWhere = $sqlWhere . " (";
                            $usl++;
                            $jj = 0;
                            foreach ($_GET['j_status'] as $v){
                                if ($jj > 0) $sqlWhere = $sqlWhere . " or ";
                                switch ($v) {
                                    case "0":
                                        $sqlWhere = $sqlWhere . " (a.status = 0)";
                                        break;
                                    case "1":
                                        $sqlWhere = $sqlWhere . " (a.status > 0)";
                                        break;
                                }
                                $jj++;
                            }
                            $sqlWhere = $sqlWhere . ")";
                        }

                        if (!($_GET['j_addr']==null)) {
                            if ($usl>0) { $sqlWhere = $sqlWhere . " and ";}
                            $sqlWhere=$sqlWhere."a.address like '".$_GET['j_addr']."'";
                            $usl++;
                        }

                        if ($usl==0) { $sqlWhere=''; }
                        $textsql="select distinct a.id_ndu, to_char(a.date_z,'dd.mm.yyyy hh24:mi') as \"DATE_Z\", a.inspector, a.raion, a.ADDRESS, a.dtp, c.NAME, a.STATUS, a.PRIM1, a.PRIM2, a.DEPT, g.MERA, a.PRIM_MERA , a.FOTO from uds.jurnal a left join UDS.JURNAL_NDU b on b.ID_NDU = a.ID_NDU left join uds.ndu c on b.ndu=c.ndu left join uds.mers g on a.status=g.id_mera ".$sqlWhere." order by 1, 2";
                        echo '<span id="sql" style="display: none;">' . $textsql . '</span>';
                    } else {
                        if (isset($_GET['j'])) {
                            switch ($_GET['j']) {
                                case "all":
                                    $s = TITLE_JURNAL2 . TITLE_JURNAL3;
                                    break;
                                case "today":
                                    $s = TITLE_JURNAL2 . date("d.m.Y");
                                    break;
                                case "yesterday":
                                    $s = date("d.m.Y");
                                    $s= TITLE_JURNAL2. date("d.m.Y",strtotime("-1 days",strtotime($s)));
                                    break;
                                case "gn": $s = TITLE_JURNAL4;
                                    break;
                                case "dat": $s = TITLE_JURNAL5;
                                    break;
                            }
                            echo "<span id='s_title' class='s_title'>" . $s . "</span>";
                            echo "<span id='sql' style='display: none;'> </span>";
                        }
                    }
                ?>
                <span class="s_records" id="s_records">Количество записей:</span>
                <table>
                    <thead>
                    <tr>
                        <th rowspan="2">№</th>
                        <th rowspan="2">№ акта</th>
                        <th rowspan="2">Дата и время</th>
                        <th rowspan="2">Инспектор</th>
                        <th rowspan="2">Подразделение</th>
                        <th rowspan="2">Адрес</th>
                        <th rowspan="2">ДТП</th>
                        <th rowspan="2">НДУ</th>
                        <th rowspan="2">Фото</th>
                        <th rowspan="2">Акт</th>
                        <th colspan="2">Принятые меры</th>
                    </tr>
                    <tr>
                        <th>Вид</th>
                        <th>Фото</th>
                    </tr>
                    </thead>

                    <tbody>
                        <?php require "db/table.php" ?>
                    </tbody>
                </table>
                <span class="s_records_end" id="s_records_end">Количество записей:</span>
            </div>
        <?php endif; ?>
    </div>

        <script type="text/javascript">
            // if (window.jQuery) alert("jQuery подключен");
            // else alert("jQuery не подключен");
            // $('select').multipleSelect({
            //     filter: true,
            //     filterAcceptOnEnter: true,
            //     selectAll: false
            // });
            // $('select').selectpicker()

        </script>

    <?php require_once "blocks/footer.php" ?>

<!--    <script type="text/javascript" src="js/jquery.min.js"> </script>-->
<!--    <script type="text/javascript" src="js/bootstrap.bundle.min.js"> </script>-->
<!--    <script type="text/javascript" src="js/multiple-select.min.js"> </script>-->
<!--    <script type="text/javascript" src="js/bootstrap-select.min.js"> </script>-->
<!--    <script type="text/javascript" src="js/bootstrap-select.min.js"> </script>-->
    <script src="js/wrd_jrn.js"></script>

</body>
</html>
