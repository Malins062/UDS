<?php

define('FTP_HOST', '10.50.109.20');
define('FTP_USER', 'gai/uds_rw');
define('FTP_PASS', 'RW___uds62');
define('FTP_PORT', '21');
define('FTP_READ', 'ftp://uds_r:R_uds62@10.50.109.20:21/');

define('DB_USER', 'UDS');
define('DB_PASS', 'udsUDS');
define('DB_CONNECT', '10.50.109.15/BASE1161');
define('DB_CHARSET', 'AL32UTF8');

//define('FNAME_JRN_DOT', 'template_jurnal.docx');
//define('FNAME_JRN', 'jurnal.docx');
const FNAME_DOC1 = 'template_akt.docx';
const FTP_DIR_MERA = 'MERA';

const CST_ADD_FOTO = 'НДУ';
const CST_ADD_FOTO_M = 'принятых мер';

const DEF_STATUS = '0';
const CST_EMPTY = '-';
const TITLE_JURNAL = 'Журнал учёта недостатков улично-дорожной сети. ';
const TITLE_JURNAL2 = '<b>ВЫБОРКА ЗА: </b>';
const TITLE_JURNAL3 = 'всё время.';
const TITLE_JURNAL4 = '<b>ВЫБОРКА ПО АДРЕСУ: </b>';
const TITLE_JURNAL5 = '<b>ВЫБОРКА ПО ЗАДАННЫМ ПАРАМЕТРАМ: </b>';
const TITLE_RECORDS = 'Количество записей: ';

const CITY = 'Г.РЯЗАНЬ, ';
const FNAME_JRN = 'jurnal.docx';
const FNAME_JRN_DOT = 'template_jurnal.docx';
const FNAME_ACT_DOT = 'template_akt.docx';
const FNAME_ACT = 'akt.docx';

const PROGRESS2 = 'Передача данных...';
const PROGRESS1 = 'Передача в MS Word...';

function ru_hour($hour){
    switch ($hour){
        case "01":
            return 'часа';
            break;
        case "02":
            return  'часа';
            break;
        case "03":
            return  'часа';
            break;
        case "04":
            return  'часа';
            break;
        default:
            return  'часов';
    }
}

function ru_month($month) {
    switch ($month){
        case "01":
            return "января";
            break;
        case "02":
            return "февраля";
            break;
        case "03":
            return "марта";
            break;
        case "04":
            return "апреля";
            break;
        case "05":
            return "мая";
            break;
        case "06":
            return "июня";
            break;
        case "07":
            return "июля";
            break;
        case "08":
            return "августа";
            break;
        case "09":
            return "сентября";
            break;
        case "10":
            return "октября";
            break;
        case "11":
            return "ноября";
            break;
        case "12":
            return "декабря";
            break;
        default: return " ";
    }
}
