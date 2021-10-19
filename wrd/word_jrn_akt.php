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
        $textsql="select distinct a.id_ndu, to_char(a.date_z,'dd.mm.yyyy hh24:mi') as \"DATE_Z\", a.inspector, a.raion, a.ADDRESS, a.dtp, c.NAME, a.STATUS, a.DEPT, a.DOLJ, a.ZVAN, a.ZNAK, a.PRIM1, a.PRIM2 from uds.jurnal a left join UDS.JURNAL_NDU b on b.ID_NDU = a.ID_NDU left join uds.ndu c on b.ndu=c.ndu where a.ID_NDU='" . $_POST['j'] . "'";
        if ($textsql!="") {

            require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
//                $template_document = new \PhpOffice\PhpWord\TemplateProcessor($_SERVER['DOCUMENT_ROOT'] . '/docs/template_jurnal.docx');
//                $template_document = new \PhpOffice\PhpWord\TemplateProcessor($_SERVER['DOCUMENT_ROOT'] . '/docs/' . FNAME_JRN_DOT);
            $template_document = new \PhpOffice\PhpWord\TemplateProcessor($_SERVER['DOCUMENT_ROOT'] .PATH.'/docs/' . FNAME_ACT_DOT);


            $res = OCI_Parse($conn, $textsql);
            oci_execute($res);
            $allRows=oci_fetch_all($res,$records, null, null, OCI_FETCHSTATEMENT_BY_ROW);

            if ($allRows>0) {
                $template_document->setValue('j_dtp', ($records[0]["DTP"]==0 ? " " : "ДТП"));
                $template_document->setValue('j_idndu', sprintf("%08d", $records[0]["ID_NDU"]));
                $template_document->setValue('j_dolj', $records[0]["DOLJ"]);
                $template_document->setValue('j_dept', $records[0]["DEPT"]);
                $template_document->setValue('j_zvan', $records[0]["ZVAN"]);
                $template_document->setValue('j_fio', $records[0]["INSPECTOR"]);
                $template_document->setValue('j_znak', $records[0]["ZNAK"]);
                $template_document->setValue('j_address', $records[0]["ADDRESS"]);

                $name_ndu='';
                foreach ($records as $row) { $name_ndu=$name_ndu . $row["NAME"]. ". "; }
//                $template_document->setValue('j_ndu', $name_ndu);

                $template_document->setValue('j_prim1', $records[0]["PRIM1"]);
                $template_document->setValue('j_prim2', $records[0]["PRIM2"]);

                $date = date_create($records[0]["DATE_Z"]);
                $template_document->setValue('j_d1', date_format($date, "d"));
                $template_document->setValue('j_d2', ru_month(date_format($date, "m")));
                $template_document->setValue('j_d3', date_format($date, "Y"));
                $template_document->setValue('j_t1', date_format($date, "H"));

                $template_document->setValue('j_tt', ru_hour(date_format($date, "H")));
                $template_document->setValue('j_t2', date_format($date, "i"));

            } // allrows > 0

            $temp_file = $_SERVER['DOCUMENT_ROOT'] . PATH. '/docs/' . FNAME_ACT;
            $template_document->saveAs($temp_file);
            echo $temp_file;
        } else {
            echo "NOT";
        }
        oci_free_statement($res);
        oci_close($conn);
    }
}