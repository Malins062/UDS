function show_ftp(ftp_ndu) {
    // alert(ftp_ndu);
    window.open(ftp_ndu, '_blank');
}

function show_otrabotka(id_ndu) {

    document.getElementById("otrabotka").setAttribute("style", "display: block");

    document.getElementById("o2_id").setAttribute("value", id_ndu);
    id_ndu = id_ndu.padStart(8,'0');
    document.getElementById("akt_no").innerHTML = id_ndu;
    // document.getElementById("o2_mera").innerHTML = '';
    document.getElementById("o2_prim1").innerHTML = '';
    document.getElementById("o2_file1").innerHTML = '';
    document.getElementById("o2_file2").innerHTML = '';
}

function close_otrabotka() {
    document.getElementById("otrabotka").setAttribute("style", "display: none");
    // document.getElementById("o2_mera").innerHTML = '';
    document.getElementById("o2_prim1").innerHTML = '';
    document.getElementById("o2_file1").innerHTML = '';
    document.getElementById("o2_file2").innerHTML = '';
    $("selectorOfSelect").val('0').selectpicker("refresh");
}

function show_add_foto(id_ndu, s_type, ftp_dir) {

    document.getElementById("add_foto").setAttribute("style", "display: block");

    document.getElementById("f2_id").setAttribute("value", id_ndu);
    id_ndu = id_ndu.padStart(8,'0');
    document.getElementById("f_akt_no").innerHTML = id_ndu;
    document.getElementById("f_foto").innerHTML = s_type;
    document.getElementById("f2_dir").setAttribute("value", ftp_dir);
    document.getElementById("o2_file1").innerHTML = '';
    document.getElementById("o2_file2").innerHTML = '';
}

function close_add_foto() {
    document.getElementById("add_foto").setAttribute("style", "display: none");
    document.getElementById("o2_file1").innerHTML = '';
    document.getElementById("o2_file2").innerHTML = '';
}

$(document).ready(function(){

    document.getElementById("s_records").textContent = document.getElementById("s_records0").textContent;
    document.getElementById("s_records_end").textContent = document.getElementById("s_records0").textContent;

    $('.jrn_word').on('click', function(event){
    // $('body').on('click', '.jrn_word', function(event){
         event.preventDefault();
        //получение id формы
        // var formID=$(this).attr('id');
        // var formNm=$('#'+formID);
        // var val=document.getElementById("$_GET");
        // alert(  document.getElementById("s_title").innerHTML);
        // alert(  document.getElementById("sql").innerHTML);
        // alert(  document.getElementById("sql").textContent);

        $.ajax({
            type: "POST",
            url: 'wrd/word_jrn.php',
            data: {
                'j': $(this).attr('id'),
                'value': document.getElementById("s_title").textContent,
                'sql_text': document.getElementById("sql").textContent},
            cache: false,
            dataType: "html",
            beforeSend:function()
            {
                // alert('Before');
                // $(form_data).html('<p style="text-align:center"> Отправка на ФТП... </p>');
                $('.cssload-loading').html('Передача в MS Word...');
                $('.cssload-loader').attr('style','box-shadow: 0 0 50px greenyellow');
                $('#cssload-wrapper').css('display', '');
                $('.jrn_word').attr('disabled', 'disabled');
            },
            success:function(result){
                // alert(result);
               // window.location.href="wrd/wrd_load.php?file_name="+result;
               //
               if (result != "NOT") {
                   window.location.href="wrd/wrd_load.php?file_name="+result;
               }
            },
            complete:function() {
                // var data=JSON.parse(data);
                //  alert(data);
                // $(form_data).html('<p style="text-align:center">ЗАВЕРШЕНО</p>');
                $('#cssload-wrapper').css('display', 'none');
                $('.jrn_word').attr('disabled', false);
                $('#cssload-wrapper')[0].reset();
                // $('#jurnal')[0].reset();
            },
            error: function(jqXHR,text,error){
                alert(error);
            }
        });

        return false;
    });

    $('.jrn_word_akt').on('click', function(event){
    // $('body').on('click', '.jrn_word', function(event){
         event.preventDefault();
        //получение id формы
        // var formID=$(this).attr('id');
        // var formNm=$('#'+formID);
        // var val=document.getElementById("$_GET");
        // alert($(this).attr('href'));

        $.ajax({
            type: "POST",
            url: 'wrd/word_jrn_akt.php',
            data: {
                'j': $(this).attr('id'),
                'value': $(this).attr('name')},
            cache: false,
            dataType: "html",
            beforeSend:function()
            {
                // alert('Before');
                // $(form_data).html('<p style="text-align:center"> Отправка на ФТП... </p>');
                $('.cssload-loading').html('Передача в MS Word...');
                $('.cssload-loader').attr('style','box-shadow: 0 0 50px greenyellow');
                $('#cssload-wrapper').css('display', '');
                $('.jrn_word_akt').attr('disabled', 'disabled');
            },
            success:function(result){
                // alert(result);
               // window.location.href="wrd/wrd_load.php?file_name="+result;
               //
               if (result != "NOT") {
                   window.location.href="wrd/wrd_load.php?file_name="+result;
               }
            },
            complete:function() {
                // var data=JSON.parse(data);
                //  alert(data);
                // $(form_data).html('<p style="text-align:center">ЗАВЕРШЕНО</p>');
                $('#cssload-wrapper').css('display', 'none');
                $('.jrn_word_akt').attr('disabled', false);
                $('#cssload-wrapper')[0].reset();
                // $('#jurnal')[0].reset();
            },
            error: function(jqXHR,text,error){
                alert(error);
            }
        });

        return false;
    });

    $('#f_otrabotka').on('submit', function(event){
        event.preventDefault();
        // alert('begin');

        var form_data = new FormData(document.getElementById("f_otrabotka"));

        // alert(document.getElementById("f_otrabotka").getAttribute('id'));
        // alert( 'mera - ' + document.getElementById("o2_mera").getAttribute('value') +
        //     'prim - ' + document.getElementById("o2_prim1").getAttribute('innerHTML')  +
        //     'file1 - ' + document.getElementById("o2_file1").textContent +
        //     'file2 - ' + document.getElementById("o2_file2").textContent +
        //     'akt_no - ' + document.getElementById("akt_no").getAttribute('name')
        // );
        // alert(form_data);

        $.ajax({
            type: "POST",
            url: 'db/otrabotka.php',
            // data: {
            //     'o2_mera': document.getElementById("o2_mera").getAttribute('textContent'),
            //     // 'o2_prim1': document.getElementById("o2_prim").getAttribute('textContent'),
            //     // 'o2_file1': document.getElementById("o2_file1").getAttribute('textContent'),
            //     // 'o2_file2': document.getElementById("o2_file2").getAttribute('textContent'),
            //     'o2_id_ndu':  document.getElementById("akt_no").getAttribute('textContent')},
            // data: {form_data, 'id': document.getElementById("akt_no").getAttribute('name')},
            data: form_data,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend:function()
            {
                // alert('Before');
                $('.cssload-loading').html('Передача в базу данных...');
                $('.cssload-loader').attr('style','box-shadow: 0 0 50px greenyellow');
                $('#cssload-wrapper').css('display', '');
            },
            success:function(result){
                // alert("success");
            },
            complete:function() {
                // var data=JSON.parse(data);
                //  alert("Данные успешно внесены.");
                $('#cssload-wrapper').css('display', 'none');
                $('#cssload-wrapper')[0].reset();
                close_otrabotka();
                location.reload();
            },
            error: function(jqXHR,text,error){
                alert(error);
                close_otrabotka();
                location.reload();
            }
        });

        return false;
    });

    $('#f_add_foto').on('submit', function(event){
        event.preventDefault();
        // alert(document.getElementById("f2_dir").innerHTML);
        // alert(document.getElementById("f_foto").innerHTML);

        var form_data = new FormData(document.getElementById("f_add_foto"));

        $.ajax({
            type: "POST",
            url: 'db/add_foto.php',
            data: form_data,
            // data: {
            //     'j': 'a',
            //     'value': 'b'},
            processData: false,
            contentType: false,
            cache: false,
            beforeSend:function()
            {
                // alert('Before');
                $('.cssload-loading').html('Загрузка файлов на сервер...');
                $('.cssload-loader').attr('style','box-shadow: 0 0 50px greenyellow');
                $('#cssload-wrapper').css('display', '');
                // $('#btn_add_foto').attr('disabled', 'disabled');
                close_add_foto();
            },
            success:function(result){
                // alert("success");
                // $('#add_foto').attr('disabled', false);
                // $('#f_add_foto')[0].reset();
                window.location.replace(document.location.href);

            },
            complete:function() {
                $('#cssload-wrapper').css('display', 'none');
                $('#cssload-wrapper')[0].reset();
                // alert("Данные успешно внесены.");
            },
            error: function(jqXHR,text,error){
                alert(error);
                close_add_foto();
            }
        });

        return false;
    });

    // $('.otrabotka_cancel').on('click', function(event){
    //     event.preventDefault();
    //     alert($(this).attr('id'));
    //
    //     $('#cssload-wrapper').css('display', 'none');
    //     $('.otrabotka').attr('disabled', false);
    //     $('.otrabotka').css('display', 'none');
    //     $('#cssload-wrapper')[0].reset();
    //
    //     location.reload();
    //
    //     return false;
    // });
    //

    // $('.selectpicker').selectpicker('refresh');
    $('select').selectpicker();

    // $('select').multipleSelect(
    //         filter: true,
    //         filterAcceptOnEnter: true,
    //         selectAll: false,
    //         showClear: false,
    //         minimumCountSelected: 1
    // });

});
