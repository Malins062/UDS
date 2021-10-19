<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/cnst_uds.php');
require($_SERVER['DOCUMENT_ROOT'] . PATH . '/db/cnst.php');

if ( ($_FILES['f2_file1']['name']!="" || $_FILES['f2_file2']['name']!="") && isset($_POST['f2_id']) ) {

        // ----------------------------------- ЗАГРУЗКА НА ФТП ------------------------------
        include('ftp_class.php');

        $f2_id = sprintf("%08d", $_POST['f2_id']);

        set_time_limit(300);

        $f2["file1"]=$_FILES['f2_file1']['name'];
        $f2["file2"]=$_FILES['f2_file2']['name'];
        $cnt_f=0;

        // *** Create the FTP object
        $ftpObj = new FTPClient();

        // *** Connect
        if ($ftpObj->connect(FTP_HOST, FTP_USER, FTP_PASS)) {

            // *** Make directory
            $ftpObj->makeSubDirs("", $f2_id);
            if ($_POST['f2_dir']==FTP_DIR_MERA) {
                $ftpObj->makeSubDirs("", FTP_DIR_MERA);
            }
//            print_r($ftpObj->getMessages());

            if ($_FILES['f2_file1']['name']!=""){

                $fileFrom = $_FILES['f2_file1']['tmp_name'];
                $fileTo = $f2["file1"];
                $ftpObj->uploadFile($fileFrom, $fileTo);

                sleep(2);
//            print_r($ftpObj->getMessages());
                $cnt_f++;

            }

            if ($_FILES['f2_file2']['name']!=""){
                $fileFrom = $_FILES['f2_file2']['tmp_name'];
                $fileTo = $f2["file2"];

                $ftpObj->uploadFile($fileFrom, $fileTo);

//            print_r($ftpObj->getMessages());
                $cnt_f++;
            }
//            echo "Данные успешно загружены в архив!";
//            echo "<script>alert(\"Данные успешно загружены в архив!\");</script>";
            
            // ----------------------------------- ОБНОВЛЕНИЕ БД -------------------------------------------
            $conn = oci_connect(DB_USER, DB_PASS, DB_CONNECT, DB_CHARSET);
            if (!$conn) {
                $m = oci_error();
                trigger_error(htmlentities($m['message'], ENT_QUOTES), E_USER_ERROR);
                exit;
            }

            if ($_POST['f2_dir']==FTP_DIR_MERA) {
                $s = oci_Parse($conn, "UPDATE UDS.JURNAL A SET A.FOTO_M =  (A.FOTO_M +" . $cnt_f .") WHERE A.ID_NDU = ". $f2_id);
            } else {
                $s = oci_Parse($conn, "UPDATE UDS.JURNAL A SET A.FOTO =  (A.FOTO +" . $cnt_f .") WHERE A.ID_NDU = ". $f2_id);
            }

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

        }  else {
            echo "<script type=\"text/javascript\"> alert(\"Ошибка загрузки фото на сервер!\");</script>";
        }
//exit();
}

//header('Location: '.$_SERVER['HTTP_REFERER']);
