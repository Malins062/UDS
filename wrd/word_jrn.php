<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/cnst_uds.php');
require($_SERVER['DOCUMENT_ROOT'] . PATH . '/db/cnst.php');

if (isset($_POST['j'])) {
    set_time_limit(0);
    $conn = oci_connect(DB_USER, DB_PASS, DB_CONNECT, DB_CHARSET);
//    $conn = oci_connect("UDS", "udsUDS", "10.50.109.15/BASE1161", "AL32UTF8");
    if (!$conn) {
        $m = oci_error();
        trigger_error(htmlentities($m['message'], ENT_QUOTES), E_USER_ERROR);
    } else {
        $title=$_POST['value'];
        switch($_POST['j']){
            case "all":
                $textsql="select distinct a.id_ndu, to_char(a.date_z,'dd.mm.yyyy hh24:mi') as \"DATE_Z\", a.inspector, a.raion, a.ADDRESS, a.dtp, c.NAME, a.STATUS, a.DEPT, a.PRIM_MERA from uds.jurnal a left join UDS.JURNAL_NDU b on b.ID_NDU = a.ID_NDU left join uds.ndu c on b.ndu=c.ndu order by 1, 2";
                $textsql_cnt="select a.ID_NDU from uds.jurnal a";
//                $title=TITLE_JURNAL.TITLE_JURNAL2.TITLE_JURNAL3;
                break;
            case "today":
                $d1=date("d.m.Y");
                $d2=date("d.m.Y",strtotime("+1 days",strtotime($d1)));
                $textsql="select distinct a.id_ndu, to_char(a.date_z,'dd.mm.yyyy hh24:mi') as \"DATE_Z\", a.inspector, a.raion, a.ADDRESS, a.dtp, c.NAME, a.STATUS, a.DEPT, a.PRIM_MERA  from uds.jurnal a left join UDS.JURNAL_NDU b on b.ID_NDU = a.ID_NDU left join uds.ndu c on b.ndu=c.ndu where a.date_z>=to_date('".$d1."','dd.mm.yyyy') and a.date_z<to_date('".$d2."','dd.mm.yyyy') order by 1, 2";
                $textsql_cnt="select a.id_ndu from uds.jurnal a where a.date_z>=to_date('".$d1."','dd.mm.yyyy') and a.date_z<to_date('".$d2."','dd.mm.yyyy')";
//                $title=TITLE_JURNAL.TITLE_JURNAL2.$d1;
                break;
            case "yesterday":
                $d1=date("d.m.Y");
                $d2=date("d.m.Y",strtotime("-1 days",strtotime($d1)));
                $textsql="select distinct a.id_ndu, to_char(a.date_z,'dd.mm.yyyy hh24:mi') as \"DATE_Z\", a.inspector, a.raion, a.ADDRESS, a.dtp, c.NAME, a.STATUS, a.DEPT, a.PRIM_MERA  from uds.jurnal a left join UDS.JURNAL_NDU b on b.ID_NDU = a.ID_NDU left join uds.ndu c on b.ndu=c.ndu where a.date_z>=to_date('".$d2."','dd.mm.yyyy') and a.date_z<to_date('".$d1."','dd.mm.yyyy') order by 1, 2";
                $textsql_cnt="select a.id_ndu from uds.jurnal a where a.date_z>=to_date('".$d2."','dd.mm.yyyy') and a.date_z<to_date('".$d1."','dd.mm.yyyy')";
//                $title=TITLE_JURNAL.TITLE_JURNAL2.$d2;
                break;
            case "gn":
                $textsql="select distinct a.id_ndu, to_char(a.date_z,'dd.mm.yyyy hh24:mi') as \"DATE_Z\", a.inspector, a.raion, a.ADDRESS, a.dtp, c.NAME, a.STATUS, a.DEPT, a.PRIM_MERA from uds.jurnal a left join UDS.JURNAL_NDU b on b.ID_NDU = a.ID_NDU left join uds.ndu c on b.ndu=c.ndu where a.address like '".mb_strtoupper($_GET['gn'])."' order by 1, 2";
                $textsql_cnt="select a.id_ndu from uds.jurnal a where a.address like '".mb_strtoupper($_GET['gn'])."'";
//                $title=TITLE_JURNAL.TITLE_JURNAL4.'***';
//                $title=TITLE_JURNAL.TITLE_JURNAL4.mb_strtoupper($_POST['value']);
                break;
            case "search":
                $textsql=$_POST['sql_text'];
                $textsql_cnt=$textsql;
                break;
        }
        if ($textsql!="") {
            $res = OCI_Parse($conn, $textsql_cnt);
            oci_execute($res);
            $numRows=oci_fetch_all($res,$records);

            require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
//                $template_document = new \PhpOffice\PhpWord\TemplateProcessor($_SERVER['DOCUMENT_ROOT'] . '/docs/template_jurnal.docx');
//                $template_document = new \PhpOffice\PhpWord\TemplateProcessor($_SERVER['DOCUMENT_ROOT'] . '/docs/' . FNAME_JRN_DOT);
            $template_document = new \PhpOffice\PhpWord\TemplateProcessor($_SERVER['DOCUMENT_ROOT'] .PATH.'/docs/' . FNAME_JRN_DOT);
            $template_document->setValue('j_title1', TITLE_JURNAL);
            $template_document->setValue('j_title2', $title);
//            $template_document->setValue('j_ktitle', $title);
//                $template_document->Footer->setValue('j_ktitle', $title);

            if ($numRows<1) {
                $n=1;
                $template_document->cloneRow('j_1', 1);
                $template_document->setValue('j_1' . '#' . $n, CST_EMPTY);
                $template_document->setValue('j_2' . '#' . $n, CST_EMPTY);
                $template_document->setValue('j_3' . '#' . $n, CST_EMPTY);
                $template_document->setValue('j_4' . '#' . $n, CST_EMPTY);
                $template_document->setValue('j_5' . '#' . $n, CST_EMPTY);
                $template_document->setValue('j_6' . '#' . $n, CST_EMPTY);
                $template_document->setValue('j_7' . '#' . $n, CST_EMPTY);
                $template_document->setValue('j_8' . '#' . $n, CST_EMPTY);
                $template_document->setValue('j_9' . '#' . $n, CST_EMPTY);
            } else {
                $template_document->cloneRow('j_1', $numRows);

                $res = OCI_Parse($conn, $textsql);
                oci_execute($res);
                $allRows=oci_fetch_all($res,$records, null, null, OCI_FETCHSTATEMENT_BY_ROW);

                $id_ndu=0;
                $name_ndu='';
                $n=1;
                foreach ($records as $row) {
                    if ((!($id_ndu==0)) && ($id_ndu<>$row["ID_NDU"])) {
                        $id_ndu = FTP_READ . $id_ndu;
                        // завершение таблицы
                        $template_document->setValue('j_8'.'#'.$n, $name_ndu);
                        $name_ndu='';
                        $n++;
                    }
                    if ((!($id_ndu==0)) && ($id_ndu==$row["ID_NDU"])) {
                        $name_ndu=$name_ndu . $row["NAME"]. ".";
                    } else {
                        $template_document->setValue('j_1'.'#'.$n, $n);
                        $template_document->setValue('j_2'.'#'.$n, sprintf("%08d", $row["ID_NDU"]));
                        $template_document->setValue('j_3'.'#'.$n, $row["DATE_Z"]);
                        $template_document->setValue('j_4'.'#'.$n, $row["INSPECTOR"]);
                        $template_document->setValue('j_5'.'#'.$n, $row["DEPT"]);
                        $template_document->setValue('j_6'.'#'.$n, $row["ADDRESS"]);
                        $template_document->setValue('j_7'.'#'.$n, ($row["DTP"]==0 ? "НЕТ" : "ДА"));
                        $template_document->setValue('j_9'.'#'.$n, $row["PRIM_MERA"]);
                        $name_ndu=$name_ndu . $row["NAME"]. ".";
                        $id_ndu=$row["ID_NDU"];
                        $status=$row["STATUS"];
                    }
                } //foreach
                if (!$name_ndu=='') {
                    $template_document->setValue('j_8'.'#'.$n, $name_ndu);
                }
            } // if else Numrows

//        $temp_file=tempnam(sys_get_temp_dir(),'PHPWord');
            $temp_file = $_SERVER['DOCUMENT_ROOT'] . PATH. '/docs/' . FNAME_JRN;
            $template_document->saveAs($temp_file);
//                sleep(10);
            echo $temp_file;
        } else {
            echo "NOT";
        }
        oci_free_statement($res);
        oci_close($conn);
    }
}