<?php
//echo "<script type='text/javascript'>window.alert('Ошибка добавления данных в журнал!')</script>";

require_once($_SERVER['DOCUMENT_ROOT'] . '/cnst_uds.php');
require($_SERVER['DOCUMENT_ROOT'] . PATH . '/db/cnst.php');
//include('blocks/cnst.php');

if ( isset($_POST['i2_date']) && isset($_POST['i2_time']) && isset($_POST['i2_inspector']) && isset($_POST['i2_ndu']) &&
    isset($_POST['i2_dtp']) && isset($_POST['i2_address']) && isset($_POST['i2_raion']) ) {
    $i2["date"]=$_POST['i2_date'].' '.$_POST['i2_time'];
    $i2["inspector"]=($_POST['i2_inspector']);
    $i2["raion"]=(trim($_POST['i2_raion']));
    $i2["address"]=($_POST['i2_address']);
    $i2["dtp"]=($_POST['i2_dtp']);
//    $i2["ndu"]=implode(":",$_POST['i2_ndu']);
    $i2["ndu"]=$_POST['i2_ndu'];
    $i2["ip"]=$_SERVER['REMOTE_ADDR'];
    $i2_status=DEF_STATUS;
    $i2["prim1"]=$_POST['i2_prim1'];
    $i2["prim2"]=$_POST['i2_prim2'];

    if ($_FILES['i2_file1']['name']!="" || $_FILES['i2_file2']['name']!="") {
        if ($_FILES['i2_file1']['name']!="" && $_FILES['i2_file2']['name']!="") {
            $i2_foto = '2';
        } else {
            $i2_foto = '1';
        }
    } else {
        $i2_foto = '0';
    }

    $i2_foto_m='0';
    $dateComp = date_parse($i2["date"]);
//    $i2_id=$dateComp['year'] . $dateComp['month'] . $dateComp['day'] . $dateComp['hour']. $dateComp['minute']. $dateComp['second'];
//    $i2_id = date("YmdHis");

    //    var_dump($_POST);
//    var_dump($_FILES);
//    var_dump($i2);
//    exit();
// ----------------------------------- ЗАГРУЗКА В БД -------------------------------------------
//    set_time_limit(0);
    $conn = oci_connect(DB_USER, DB_PASS, DB_CONNECT, DB_CHARSET);

//    $conn = oci_connect("UDS", "udsUDS", "10.50.109.15/BASE1161", "AL32UTF8");
    if (!$conn) {
        $m = oci_error();
        trigger_error(htmlentities($m['message'], ENT_QUOTES), E_USER_ERROR);
        exit;
    }

    // **************  Вычисление номера акта *********************
    $s = oci_Parse($conn, "SELECT ID_NDU FROM UDS.JURNAL WHERE ROWNUM=1 ORDER BY ID_NDU DESC");
    $i2_id = 0;
    if (($s != false) && (oci_execute($s))) {
        $num_rows = oci_fetch_all($s,$records);
        $i2_id = ($num_rows < 1 ? 1 : $records["ID_NDU"][0] + 1);
    }

    // **************  Заполнение данных инспектора и подразделения *********************
    $i2_FIO = '';
    $i2_ZNAK = '';
    $i2_ZVAN = '';
    $i2_DOLJ = '';
    $i2_DEPT = '';
    if (!($i2["inspector"]=='')) {
        $s = oci_Parse($conn, "select a.FIO, a.ZNAK, a.dolj, a.ZVAN,b.NAME from uds.users a left join UDS.DEPTS b on b.ID_DEPT = a.ID_DEPT where a.id_user=".$i2["inspector"]);
        if (($s != false) && (oci_execute($s))) {
            $num_rows = oci_fetch_all($s,$records);
            $i2_FIO = $records["FIO"][0];
            $i2_ZNAK = $records["ZNAK"][0];
            $i2_ZVAN = $records["ZVAN"][0];
            $i2_DOLJ = $records["DOLJ"][0];
            $i2_DEPT = $records["NAME"][0];
        }
    }

    // **************  Вставка записи *********************
    $s = oci_Parse($conn, "insert into uds.jurnal values (to_date(:bind1,'yyyy-mm-dd hh24:mi:ss'), :bind2, :bind3, :bind4, :bind5, :bind6, :bind7, :bind8, :bind9, to_date(:bind10,'yyyy-mm-dd hh24:mi:ss'), :bind11, :bind12, :bind13, :bind14, :bind15, :bind16, to_date(:bind17,'yyyy-mm-dd hh24:mi:ss'), :bind18, :bind19)");
    if ($s != false){
        // parsing empty query != false
        $i2["date_in"]=date("Y-m-d H:i:s");
        $prim_mera='';
        OCIBindByName($s, ":bind1", $i2["date"]);
        OCIBindByName($s, ":bind2", $i2_FIO);
        OCIBindByName($s, ":bind3", $i2["address"]);
        OCIBindByName($s, ":bind4", $i2["raion"]);
        OCIBindByName($s, ":bind5", $i2["dtp"]);
        OCIBindByName($s, ":bind6", $i2["ip"]);
        OCIBindByName($s, ":bind7", $i2_id);
        OCIBindByName($s, ":bind8", $i2_status);
        OCIBindByName($s, ":bind9", $i2["prim1"]);
        OCIBindByName($s, ":bind10", $i2["date_in"]);
        OCIBindByName($s, ":bind11", $i2_DEPT);
        OCIBindByName($s, ":bind12", $i2_ZVAN);
        OCIBindByName($s, ":bind13", $i2_DOLJ);
        OCIBindByName($s, ":bind14", $i2_ZNAK);
        OCIBindByName($s, ":bind15", $i2["prim2"]);
        OCIBindByName($s, ":bind16", $prim_mera);
        OCIBindByName($s, ":bind17", $i2["date_in"]);
        OCIBindByName($s, ":bind18", $i2_foto);
        OCIBindByName($s, ":bind19", $i2_foto_m);

        if (!oci_execute($s)){
            $e = oci_error($s);
//            echo "<script type=\"text/javascript\"> alert(\"Ошибка добавления данных в журнал!\");</script>";
            echo $s.' | '. $e['message'];
        } else {
            foreach ($i2["ndu"] as $value) {

                $s = oci_Parse($conn, "insert into uds.jurnal_ndu values (:bind1, :bind2)");

                if($s != false){
                    OCIBindByName($s, ":bind1", $i2_id);
                    OCIBindByName($s, ":bind2", $value);

                    if(!oci_execute($s)){
                        $e = oci_error($s);
                        echo "<script type=\"text/javascript\"> alert(\"Ошибка добавления данных в журнал!\");</script>";
                        echo $s.' | '. $e['message'];
                    } else {
                        echo "<div> class='alert alert-dark' role='alert'> Данные ВНЕСЕНЫ </div>";
                    }
                }
                else{
                    $e = oci_error($conn);
                    echo $s.' | '. $e['message'];
                }
            } // for each NDU
        }
    }
    else{
        $e = oci_error($conn);
        echo $s.' | '. $e['message'];
//        echo "<script>alert(\"Ошибка 2!\");</script>";
    }

    oci_free_statement($s);
    oci_close($conn);
//    var_dump($_POST);

// ----------------------------------- ЗАГРУЗКА НА ФТП ------------------------------
    if ($_FILES['i2_file1']['name']!="" || $_FILES['i2_file2']['name']!="") {
        include('db/ftp_class.php');

        $i2_id = sprintf("%08d", $i2_id);

        set_time_limit(300);

        $i2["file1"]=$_FILES['i2_file1']['name'];
        $i2["file2"]=$_FILES['i2_file2']['name'];

        // *** Create the FTP object
        $ftpObj = new FTPClient();

        // *** Connect
        if ($ftpObj->connect(FTP_HOST, FTP_USER, FTP_PASS)) {

//            print_r($ftpObj->getMessages());

//            $dateComp = date_parse($i2["date"]);
////            $dir = strtoupper($dateComp['year'] . '/' . $dateComp['month'] . '/' . $dateComp['day'] . '/' . $i2["protocol"]);
//            $dir = strtoupper($dateComp['year'] . $dateComp['month'] . $dateComp['day'] . $dateComp['hour'] . $dateComp['minute'].$dateComp['second'] );
//var_dump($i2);
//        echo "\n".$dir. "\n";
//          iconv(mb_detect_encoding($i2["file2"],mb_detect_order(),true),'windows-1251',$i2["file2"]);
//        echo "\n".$dir. "\n";

            // *** Make directory
            $ftpObj->makeSubDirs("", $i2_id);
//            print_r($ftpObj->getMessages());

            if ($_FILES['i2_file1']['name']!=""){

                $fileFrom = $_FILES['i2_file1']['tmp_name'];
//        $fileTo = $dir . '/' . $i2["file1"];
                $fileTo = $i2["file1"];
                $ftpObj->uploadFile($fileFrom, $fileTo);

                sleep(2);
//            print_r($ftpObj->getMessages());

            }

            if ($_FILES['i2_file2']['name']!=""){
                $fileFrom = $_FILES['i2_file2']['tmp_name'];
                $fileTo = $i2["file2"];

                $ftpObj->uploadFile($fileFrom, $fileTo);

//            print_r($ftpObj->getMessages());
            }
//            echo "Данные успешно загружены в архив!";
//            echo "<script>alert(\"Данные успешно загружены в архив!\");</script>";
        }  else {
            echo "<script type=\"text/javascript\"> alert(\"Ошибка загрузки фото на сервер!\");</script>";
        }
    }
//header('Location: input2.php');
//exit();
}