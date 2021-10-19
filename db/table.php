<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/cnst_uds.php');
//require_once ($_SERVER['DOCUMENT_ROOT'].PATH.'/libs/Mobile_Detect.php');
//require($_SERVER['DOCUMENT_ROOT'].'/blocks/cnst.php');
//var_dump($_GET);
if (
    ( (isset($_GET['j'])) && ($_GET['j']=="gn") && (!isset($_GET['gn'])) ) ||
    ( (isset($_GET['j'])) && ($_GET['j']=="dat") &&
        (
            (!isset($_GET['j_dat1'])) || (!isset($_GET['j_dat2'])) ||
            (!isset($_GET['j_gibdd'])) || (!isset($_GET['j_inspector'])) ||
            (!isset($_GET['j_raion'])) || (!isset($_GET['j_status'])) || (!isset($_GET['j_ndu']))
        )
    )
   ){
    echo "<td>" . CST_EMPTY . "</td>";
    echo "<td>" . CST_EMPTY . "</td>";
    echo "<td>" . CST_EMPTY . "</td>";
    echo "<td>" . CST_EMPTY . "</td>";
    echo "<td>" . CST_EMPTY . "</td>";
    echo "<td>" . CST_EMPTY . "</td>";
    echo "<td>" . CST_EMPTY . "</td>";
    echo "<td>" . CST_EMPTY . "</td>";
    echo "<td>" . CST_EMPTY . "</td>";
    echo "<td>" . CST_EMPTY . "</td>";
    echo "<td>" . CST_EMPTY . "</td>";
    echo "<td>" . CST_EMPTY . "</td>";
    echo "</tr>\n";
    echo "<span class='s_records0'>".TITLE_RECORDS."0</span>";
} else {

    set_time_limit(0);
    $conn = oci_connect(DB_USER, DB_PASS, DB_CONNECT, DB_CHARSET);
//    $conn = oci_connect("UDS", "udsUDS", "10.50.109.15/BASE1161", "AL32UTF8");
    if (!$conn) {
        $m = oci_error();
        trigger_error(htmlentities($m['message'], ENT_QUOTES), E_USER_ERROR);
    } else {
        if ( ( (isset($_GET['j'])) || (isset($_GET['gn'])) )||
            (
                (isset($_GET['j_dat1'])) || (isset($_GET['j_dat2'])) ||
                (isset($_GET['j_gibdd'])) || (isset($_GET['j_inspector'])) ||
                (isset($_GET['j_raion'])) || (isset($_GET['j_status'])) || (isset($_GET['j_ndu'])) || (isset($_GET['j_addr']))
            ))  {

            if (isset($_GET['gn'])) {
                $textsql="select distinct a.id_ndu, to_char(a.date_z,'dd.mm.yyyy hh24:mi') as \"DATE_Z\", a.inspector, a.raion, a.ADDRESS, a.dtp, c.NAME, a.STATUS, a.PRIM1, a.PRIM2, a.DEPT, g.MERA, a.PRIM_MERA,  a.FOTO from uds.jurnal a left join UDS.JURNAL_NDU b on b.ID_NDU = a.ID_NDU left join uds.ndu c on b.ndu=c.ndu left join uds.mers g on a.status=g.id_mera where a.address like '".mb_strtoupper($_GET['gn'])."' order by 1, 2";
            } elseif (
                (isset($_GET['j_dat1'])) || (isset($_GET['j_dat2'])) ||
                (isset($_GET['j_gibdd'])) || (isset($_GET['j_inspector'])) ||
                (isset($_GET['j_raion'])) || (isset($_GET['j_status'])) || (isset($_GET['j_addr']))
            ) {
                $sqlWhere=" where ";

                $usl=0;
                if (!($_GET['j_dat1']==null)) {
                    $d1=date("d.m.Y", strtotime($_GET['j_dat1']));
                    if ($usl>0) { $sqlWhere = $sqlWhere . " and ";}
                    $sqlWhere=$sqlWhere."a.date_z>=to_date('".$d1."','dd.mm.yyyy') ";
                    $usl++;
                }
                if (!($_GET['j_dat2']==null)) {
                    $d2=date("d.m.Y",strtotime("+1 days", strtotime($_GET['j_dat2'])));
                    if ($usl>0) { $sqlWhere = $sqlWhere . " and ";}
                    $sqlWhere=$sqlWhere."a.date_z<to_date('".$d2."','dd.mm.yyyy') ";
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
                $textsql="select distinct a.id_ndu, to_char(a.date_z,'dd.mm.yyyy hh24:mi') as \"DATE_Z\", a.inspector, a.raion, a.ADDRESS, a.dtp, c.NAME, a.STATUS, a.PRIM1, a.PRIM2, a.DEPT, g.MERA, a.PRIM_MERA , a.FOTO, a.FOTO_M from uds.jurnal a left join UDS.JURNAL_NDU b on b.ID_NDU = a.ID_NDU left join uds.ndu c on b.ndu=c.ndu left join uds.mers g on a.status=g.id_mera ".$sqlWhere . " order by 1, 2";
            } else {
                switch($_GET['j']){
                    case "all":
                        $textsql="select distinct a.id_ndu, to_char(a.date_z,'dd.mm.yyyy hh24:mi') as \"DATE_Z\", a.inspector, a.raion, a.ADDRESS, a.dtp, c.NAME, a.STATUS, a.PRIM1, a.PRIM2, a.DEPT, g.MERA, a.PRIM_MERA, a.FOTO, a.FOTO_M from uds.jurnal a left join UDS.JURNAL_NDU b on b.ID_NDU = a.ID_NDU left join uds.ndu c on b.ndu=c.ndu left join uds.mers g on a.status=g.id_mera order by 1, 2";
                        break;
                    case "today":
                        $d1=date("d.m.Y");
                        $d2=date("d.m.Y",strtotime("+1 days",strtotime($d1)));
                        $textsql="select distinct a.id_ndu, to_char(a.date_z,'dd.mm.yyyy hh24:mi') as \"DATE_Z\", a.inspector, a.raion, a.ADDRESS, a.dtp, c.NAME, a.STATUS, a.PRIM1, a.PRIM2, a.DEPT, g.MERA, a.PRIM_MERA, a.FOTO, a.FOTO_M from uds.jurnal a left join UDS.JURNAL_NDU b on b.ID_NDU = a.ID_NDU left join uds.ndu c on b.ndu=c.ndu left join uds.mers g on a.status=g.id_mera where a.date_z>=to_date('".$d1."','dd.mm.yyyy') and a.date_z<to_date('".$d2."','dd.mm.yyyy') order by 1, 2";
                        break;
                    case "yesterday":
                        $d1=date("d.m.Y");
                        $d2=date("d.m.Y",strtotime("-1 days",strtotime($d1)));
                        $textsql="select distinct a.id_ndu, to_char(a.date_z,'dd.mm.yyyy hh24:mi') as \"DATE_Z\", a.inspector, a.raion, a.ADDRESS, a.dtp, c.NAME, a.STATUS, a.PRIM1, a.PRIM2, a.DEPT, g.MERA, a.PRIM_MERA, a.FOTO, a.FOTO_M from uds.jurnal a left join UDS.JURNAL_NDU b on b.ID_NDU = a.ID_NDU left join uds.ndu c on b.ndu=c.ndu left join uds.mers g on a.status=g.id_mera where a.date_z>=to_date('".$d2."','dd.mm.yyyy') and a.date_z<to_date('".$d1."','dd.mm.yyyy') order by 1, 2";
                        break;
                }
            }

            include('ftp_class.php');

            if ($textsql!="") {
                $res=OCI_Parse($conn,$textsql);
                oci_execute($res);
                $n = 1;
                $id_ndu=0;
                $ftp_ndu='';
                $name_ndu='';
                $foto='0';
                $foto_m='0';
                $status=DEF_STATUS;
                $numRows=oci_fetch_all($res,$records, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                foreach ($records as $row) {
                    if ((!($id_ndu==0)) && ($id_ndu<>$row["ID_NDU"])) {
                        echo "</td>";

                        echo "<td nowrap='nowrap'> <div class='btn-group-sm mr-1 ml-1' role='group'>";
                        echo "<button type='button' class='btn btn-sm btn-outline-primary mr-1' ";
                        echo ($foto=='0' ? "title='Для данного акта фотографий НДУ - нет' disabled>" : "title='Открыть папку с фотографиями НДУ...' onclick='show_ftp(\"$ftp_ndu\")'>");
                        echo "<span class='glyphicon glyphicon-camera'><sup><small> ".$foto."</small></sup></span></button>";
                        echo "<button title='Добавить фото НДУ в журнал...' type='button' id='add" . $id_ndu . "' name='jrn_add_foto' onclick='show_add_foto(\"$id_ndu\",\"".CST_ADD_FOTO."\", \"\")' class='btn-sm btn btn-outline-primary'><span class='glyphicon glyphicon-plus'></span></button></div></td>";

                        echo "<td><button title='Загрузить акт в MS Word...' type='button' name='docs/" . FNAME_DOC1 . "' class='btn-sm btn btn-outline-primary jrn_word_akt' id='" . $id_ndu . "'><span class='glyphicon glyphicon-download'></span></button></td>";
                        echo "<td title='" . $prim_mera . "'>";
                        if ($status==0) {
                            echo "<button type='button' id='" . $id_ndu . "' name='jrn_obrabotka' onclick='show_otrabotka(\"$id_ndu\")' class='btn_mers btn-sm btn btn-danger'>" . $mera."</button>";
                        } else {
                            echo "<button type='button' id='" . $id_ndu . "' name='jrn_obrabotka' class='btn_mers btn-sm btn btn-success' disabled>" . $mera . "</button>";
                        }

                        echo "<td nowrap='nowrap'> <div class='btn-group-sm mr-1 ml-1' role='group'>";
                        echo "<button type='button' class='btn btn-sm btn-outline-primary mr-1' ";
                        echo ($foto_m=='0' ? "title='Для данного акта фото принятых мер - нет' disabled>" : "title='Открыть папку с фотографиями принятых мер...' onclick='show_ftp(\"$ftp_ndu_mera\")'>");
                        echo "<span class='glyphicon glyphicon-camera'><sup><small> ".$foto_m."</small></sup></span></button>";
                        echo "<button title='Добавить фото принятых мер в журнал...' type='button' id='add" . $id_ndu . "' name='jrn_add_foto' onclick='show_add_foto(\"$id_ndu\", \"".CST_ADD_FOTO_M."\", \"".FTP_DIR_MERA."\")' class='btn-sm btn btn-outline-primary'><span class='glyphicon glyphicon-plus'></span></button></div></td>";

                        echo " </td></tr>\n";
                        $n++;
                    }
                    if ((!($id_ndu==0)) && ($id_ndu==$row["ID_NDU"])) {
                        echo $row["NAME"].". ";
                    } else {
                        echo "<tr>\n";
                        echo "<td>".$n."</td>";
                        echo "<td>". sprintf("%08d", $row["ID_NDU"]) ."</td>";
                        echo "<td>".$row["DATE_Z"]."</td>";
                        echo "<td>".$row["INSPECTOR"]."</td>";
                        echo "<td>".$row["DEPT"]."</td>";
                        echo "<td title='Район: " . $row["RAION"] . "'>".$row["ADDRESS"]."</td>";
                        echo "<td>".($row["DTP"]==0 ? "НЕТ" : "ДА")."</td>";
                        echo "<td title='ФАБУЛА: " . $row["PRIM1"] . " ФИКСАЦИЯ: " . $row["PRIM2"] ."'>";
                        echo $row["NAME"] . ". ";
                        $id_ndu=$row["ID_NDU"];
                        $ftp_ndu=FTP_READ . sprintf("%08d",$id_ndu);
                        $ftp_ndu_mera=$ftp_ndu.'/'.FTP_DIR_MERA;
                        $status=$row["STATUS"];
                        $foto=$row["FOTO"];
                        $foto_m=$row["FOTO_M"];
                        $mera=$row["MERA"];
                        $prim_mera=$row["PRIM_MERA"];
                    }
                }
                if (($n>1) || ($n=$numRows)) {
                    echo "<td nowrap='nowrap'> <div class='btn-group-sm mr-1 ml-1' role='group'>";
                    echo "<button type='button' class='btn btn-sm btn-outline-primary mr-1' ";
                    echo ($foto=='0' ? "title='Для данного акта фотографий НДУ - нет' disabled>" : "title='Открыть папку с фотографиями НДУ...' onclick='show_ftp(\"$ftp_ndu\")'>");
                    echo "<span class='glyphicon glyphicon-camera'><sup><small> ".$foto."</small></sup></span></button>";
                    echo "<button title='Добавить фото НДУ в журнал...' type='button' id='add" . $id_ndu . "' name='jrn_add_foto' onclick='show_add_foto(\"$id_ndu\",\"".CST_ADD_FOTO."\", \"\")' class='btn-sm btn btn-outline-primary'><span class='glyphicon glyphicon-plus'></span></button></div></td>";

                    echo "<td><button title='Загрузить акт в MS Word...' type='button' name='docs/" . FNAME_DOC1 . "' class='btn-sm btn btn-outline-primary jrn_word_akt' id='" . $id_ndu . "'><span class='glyphicon glyphicon-download'></span></button></td>";
                    echo "<td title='" . $prim_mera . "'>";
                    if ($status==0) {
                        echo "<button type='button' id='" . $id_ndu . "' name='jrn_obrabotka' onclick='show_otrabotka(\"$id_ndu\")' class='btn_mers btn-sm btn btn-danger'>" . $mera."</button>";
                    } else {
                        echo "<button type='button' id='" . $id_ndu . "' name='jrn_obrabotka' class='btn_mers btn-sm btn btn-success' disabled>" . $mera . "</button>";
//                        echo "<span>" . $prim_mera . "</span>";
                    }

                    echo "<td nowrap='nowrap'> <div class='btn-group-sm mr-1 ml-1' role='group'>";
                    echo "<button type='button' class='btn btn-sm btn-outline-primary mr-1' ";
                    echo ($foto_m=='0' ? "title='Для данного акта фото принятых мер - нет' disabled>" : "title='Открыть папку с фотографиями принятых мер...' onclick='show_ftp(\"$ftp_ndu_mera\")'>");
                    echo "<span class='glyphicon glyphicon-camera'><sup><small> ".$foto_m."</small></sup></span></button>";
                    echo "<button title='Добавить фото принятых мер в журнал...' type='button' id='add" . $id_ndu . "' name='jrn_add_foto' onclick='show_add_foto(\"$id_ndu\", \"".CST_ADD_FOTO_M."\", \"".FTP_DIR_MERA."\")' class='btn-sm btn btn-outline-primary'><span class='glyphicon glyphicon-plus'></span></button></div></td>";

                    echo " </td></tr>\n";
//                    echo "<td><button type='button' class='";
//                    echo ($status==0 ? "btn btn-danger" : "btn btn-success");
//                    echo " btn-sm' name='search'>".$mera."</button> </td>";
//                    echo "</tr>\n";
                } else {
                    echo "<td>" . CST_EMPTY . "</td>";
                    echo "<td>" . CST_EMPTY . "</td>";
                    echo "<td>" . CST_EMPTY . "</td>";
                    echo "<td>" . CST_EMPTY . "</td>";
                    echo "<td>" . CST_EMPTY . "</td>";
                    echo "<td>" . CST_EMPTY . "</td>";
                    echo "<td>" . CST_EMPTY . "</td>";
                    echo "<td>" . CST_EMPTY . "</td>";
                    echo "<td>" . CST_EMPTY . "</td>";
                    echo "<td>" . CST_EMPTY . "</td>";
                    echo "<td>" . CST_EMPTY . "</td>";
                    echo "<td>" . CST_EMPTY . "</td>";
                    echo "</tr>\n";
                    echo "<span class='s_records0' id='s_records0'>".TITLE_RECORDS."0</span>";
                }
                echo "<span class='s_records0' id='s_records0'>".TITLE_RECORDS.$n."</span>";
            }
        } else {

        }

        oci_free_statement($res);
        oci_close($conn);
    }

}


