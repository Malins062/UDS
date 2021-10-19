<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/cnst_uds.php');
require($_SERVER['DOCUMENT_ROOT'] . PATH . '/db/cnst.php');

if ( isset($_POST['o2_mera']) && isset($_POST['o2_prim1']) && isset($_POST['o2_id']) ) {
    $o2["id_mera"]=$_POST['o2_mera'];
    $o2["prim1"]=$_POST['o2_prim1'];
    $o2["id_ndu"] = $_POST['o2_id'];

    $dateComp = date_parse($o2["date"]);
//    $o2_id=$dateComp['year'] . $dateComp['month'] . $dateComp['day'] . $dateComp['hour']. $dateComp['minute']. $dateComp['second'];

    //    var_dump($_POST);
//    var_dump($_FILES);
//    var_dump($o2);
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


    // **************  Вставка записи *********************
    $o2["date_mera"]=date("Y-m-d H:i:s");
    $s = oci_Parse($conn, "UPDATE UDS.JURNAL A SET A.STATUS = " . $o2['id_mera'] .", A.PRIM_MERA = '" . $o2['prim1'] .
        "', A.DATE_MERA = to_date('" . $o2['date_mera'] . "','yyyy-mm-dd hh24:mi:ss') WHERE A.ID_NDU = ". $o2['id_ndu']);

    $result = oci_execute($s, OCI_DEFAULT);
    if($result)
    {
        oci_commit($conn);
        echo "Data Updated Successfully !";
    }
    else {
        echo "Error.";
    }

    oci_free_statement($s);
    oci_close($conn);
//    var_dump($_POST);

// ----------------------------------- ЗАГРУЗКА НА ФТП ------------------------------
    if ($_FILES['o2_file1']['name']!="" || $_FILES['o2_file2']['name']!="") {
        include('ftp_class.php');

        $o2_id = sprintf("%08d", $o2_id);

        set_time_limit(300);

        $o2["file1"]=$_FILES['o2_file1']['name'];
        $o2["file2"]=$_FILES['o2_file2']['name'];

        // *** Create the FTP object
        $ftpObj = new FTPClient();

        // *** Connect
        if ($ftpObj->connect(FTP_HOST, FTP_USER, FTP_PASS)) {

//            print_r($ftpObj->getMessages());

//            $dateComp = date_parse($o2["date"]);
////            $dir = strtoupper($dateComp['year'] . '/' . $dateComp['month'] . '/' . $dateComp['day'] . '/' . $o2["protocol"]);
//            $dir = strtoupper($dateComp['year'] . $dateComp['month'] . $dateComp['day'] . $dateComp['hour'] . $dateComp['minute'].$dateComp['second'] );
//var_dump($o2);
//        echo "\n".$dir. "\n";
//          iconv(mb_detect_encoding($o2["file2"],mb_detect_order(),true),'windows-1251',$o2["file2"]);
//        echo "\n".$dir. "\n";

            // *** Make directory
            $ftpObj->makeSubDirs("", $o2_id);
//            print_r($ftpObj->getMessages());

            if ($_FILES['o2_file1']['name']!=""){

                $fileFrom = $_FILES['o2_file1']['tmp_name'];
//        $fileTo = $dir . '/' . $o2["file1"];
                $fileTo = $o2["file1"];
                $ftpObj->uploadFile($fileFrom, $fileTo);

                sleep(2);
//            print_r($ftpObj->getMessages());

            }

            if ($_FILES['o2_file2']['name']!=""){
                $fileFrom = $_FILES['o2_file2']['tmp_name'];
                $fileTo = $o2["file2"];

                $ftpObj->uploadFile($fileFrom, $fileTo);

//            print_r($ftpObj->getMessages());
            }
//            echo "Данные успешно загружены в архив!";
//            echo "<script>alert(\"Данные успешно загружены в архив!\");</script>";
        }  else {
            echo "<script type=\"text/javascript\"> alert(\"Ошибка загрузки фото на сервер!\");</script>";
        }
    }
//exit();
}

//header('Location: '.$_SERVER['HTTP_REFERER']);
