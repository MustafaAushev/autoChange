/* eslint no-undef:off*/
/* eslint quotes:off */
/* eslint indent:off*/
/* eslint no-unused-vars:off*/

$(document).on('click', '#vozvrat', function(e) {   
    const autoId = $('#autoInfo').attr('car');
    const stadia = $('#autoInfo').attr('stadia');
    $.ajax({
        type: 'POST',
        url: './ajax/index.php',
        data: 'type=vozvrat&autoId=' + autoId + '&stadia=' + autoId,
        success: function(ret) {
            $('#body').html(ret);
        }
    });
});

$(document).on('click', '#vozvratGai', function(e) {
    const autoId = $('#autoInfo').attr('car');
    const stadia = $('#autoInfo').attr('stadia');
    $.ajax({
        type: 'POST',
        url: './ajax/index.php',
        data: 'type=vozvratGai&autoId=' + autoId + '&stadia=' + autoId,
        success: function(ret) {
            $('body').html(ret);
        }
    });
});

$(document).on('click', '#saveNewClient', function(e) {  // Обработчик события при нажатия кнопки сохранения клиента
    const str = $('#newClientData').serialize();
    if (formEmpty('input')) {
        alert('Заполните все поля!!!');
        return false;
    }
    $.ajax({
        type: 'POST',
        url: './ajax/index.php',
        data: 'type=saveNewClient&' + str,
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});

$(document).on('click', '.sel', function(e) {
    $('.sel').removeClass('act');
    $(this).addClass('act');
});

$(document).on('click', '#adminStadia', function(e) {
    if (formEmpty('input')) {
        alert('Заполните все поля');
    }
    const autoId = $(this).attr('car');
    const stadia = $('.act').attr('stadia');
    $.ajax({
        type: 'POST',
        url: './ajax/index.php',
        data: 'type=adminStadia&stadia=' + stadia + '&autoId=' + autoId,
        success: function(ret) {
            $('#body').html(ret);
        }
    });
});

function formEmpty(className) {
    array = $('.' + className); //получаем массив из полей которые надо проверить
    for (let i = 0; i < array.length; i++) {
        if (!(array[i].value)) {
            return true;
        }
    }
    return false;
}
$(document).on('click', '#saveNewAuto', function(e) {   //Обработчик события при нажатии кнопки сохранения авто
    const str = $('#newAutoData').serialize();
    if (formEmpty('input')) {
        alert('Заполните все поля!!!');
        return false;
    }
    $.ajax({
        type: 'POST',
        url: './ajax/index.php',
        data: 'type=saveNewAuto&' + str,
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});

$(document).on('click', '.doci', function(e) {
    $.ajax({
        type: "POST",
        url: '../ajax/index.php',
        data: "type=checkDoc&doc=" + $(this).attr('value') + "&stadia=" + $('#stadia').attr('number'),
        success: function(ret) {
            $('#docdiv').html(ret);
        }
    });
});

$(document).on('click', '.stadia', function(e) {
    $.ajax({
        type: "POST",
        url: '../ajax/index.php',
        data: "type=goStadia&stadia=" + $(this).attr("stadia") + "&autoId=" + $(this).attr("autoId"),
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});

$(document).on('click', '#print', function(e) {
    const autoId = $(this).attr('car');
    const stadia = $(this).attr('stadia');
    $.ajax({
        type: "POST",
        url: './shablon/doc.php',
        data: "type=print&stadia=" + stadia + "&autoId=" + autoId,
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});

$(document).on('click', '.comeOn', function(e) {
    const doc = $(this).attr('value');
    const autoId = $(this).attr('car');
    const stadia = $(this).attr('stadia');
    $.ajax({
        type: "POST",
        url: '../ajax/index.php',
        data: 'type=comeOn&doc=' + doc + '&autoId=' + autoId + '&stadia=' + stadia,
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});

$(document).on('click', '.otkat', function(e) {
    const doc = $(this).attr('value');
    const autoId = $(this).attr('car');
    const stadia = $(this).attr('stadia');
    $.ajax({
        type: "POST",
        url: '../ajax/index.php',
        data: 'type=otkat&doc=' + doc + '&autoId=' + autoId + '&stadia=' + stadia,
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});

$(document).on('click', '#nextS', function(e) {
    const autoId = $(this).attr('car');
    const stadia = $(this).attr('stadia');
    $.ajax({
        type: 'POST',
        url: '../ajax/index.php',
        data: 'type=nextS&autoId=' + autoId + '&stadia=' + stadia,
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});

$(document).on('click', '#back', function(e) {
    const autoId = $(this).attr('car');
    const stadia = $(this).attr('stadia');
    $.ajax({
        type: 'POST',
        url: '../ajax/index.php',
        data: 'type=back&autoId=' + autoId + '&stadia=' + stadia,
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});

$(document).on('click', '.optionStadia', function(e) {
    const stadia = $(this).attr('stadia');
    $('.disp').attr('style', 'display:none;');
    if (stadia == 'all') {
        $('.disp').attr('style', '');
        $('.noAll').attr('style', 'display:none');
    } else
    if ( (stadia == 100) || (stadia == 101) || (stadia == 102) ) {
        $('.stadia' + stadia).attr('style', '');
    } else {
        for (let i = 0; i < 9 ; i++) {
            $('.stadia' + i).attr('style', 'display:none;');
        }
        $('.stadia' + stadia).attr('style', '');
    }
    
});

$(document).on('click', '#searchGo', function(e) {
    const value = $('#searchField').attr('value');
    alert(value);
});

$(document).on('click', '#arhiv', function(e) {
    const autoId = $(this).attr('car');
    $.ajax({
        type: 'POST',
        url: '../ajax/index.php',
        data: 'type=arhiv&autoId=' + autoId,
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});

$(document).on('click', '.clientFilter', function(e) {
    const id = $(this).attr('client');
    if (id == 'all') {
        for (let i = 0;i < 1000; i++) {
            $('.name' + i).attr('style', '');
        }
    } else{
        for (let i = 0;i < 1000; i++) {
            $('.name' + i).attr('style', 'display:none;');
        }
        $('.name' + id).attr('style', '');
    }
    
});