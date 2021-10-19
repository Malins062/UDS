<?php
include('blocks/cnst.php');

//var_dump($_FILES);
//var_dump($_POST);
if (isset($_POST['i3_date']) && isset($_POST['i3_protocol']) && (isset($_FILES['i3_protocol_scan'])|| isset($_FILES['i3_tc_scan']))) {

    $i3_date=$_POST['i3_date'];
    $i3_protocol=$_POST['i3_protocol'];
    $i3_file1=$_FILES['i3_protocol_scan']['name'];
    $i3_file2=$_FILES['i3_tc_scan']['name'];

// ----------------------------------- ЗАГРУЗКА НА ФТП ------------------------------
//    var_dump($_FILES);

    include('db/ftp_class.php');

    // *** Create the FTP object
    $ftpObj = new FTPClient();

    set_time_limit(180);

    // *** Connect
    if ($ftpObj -> connect(FTP_HOST, FTP_USER, FTP_PASS)) {

        $dateComp=date_parse($i3_date);
        $dir = strtoupper($dateComp['year'].'/'.$dateComp['month'].'/'.$dateComp['day'].'/'.$i3_protocol);

//        echo "\n".$dir. "\n";
//        iconv(mb_detect_encoding($dir,mb_detect_order(),true),'UTF-8',$dir);
//        $i3_file2=mb_convert_encoding($i3_file2,'UTF-8','Windows-1251');

//        echo "\n".$dir. "\n";

        // *** Make directory
        $ftpObj->makeSubDirs("",$dir);

//        print_r($ftpObj -> getMessages());

        ## --------------------------------------------------------
        if ($_FILES['i3_protocol_scan']['tmp_name']!=""){
            $fileFrom = $_FILES['i3_protocol_scan']['tmp_name'];
//        $fileTo = $dir . '/' . $i3_file1;
            $fileTo = $i3_file1;
            // *** Upload local file to new directory on server
            $ftpObj -> uploadFile($fileFrom, $fileTo);
        }

        sleep(2);

        if ($_FILES['i3_tc_scan']['tmp_name']!="") {

//        print_r($ftpObj -> getMessages());

            $fileFrom = $_FILES['i3_tc_scan']['tmp_name'];
            $fileTo = $i3_file2;
            // *** Upload local file to new directory on server
            $ftpObj->uploadFile($fileFrom, $fileTo);
//
//        print_r($ftpObj -> getMessages());

//        print_r($contentsArray);

//        echo ("<script>alert(\"Данные успешно загружены в архив!\");</script>");
        }
    }
} else {
//    echo "<script>alert(\"Не хватает данных!\");</script>";
}
//header('Location: input3.php');
?>
