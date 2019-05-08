/* eslint no-undef:off*/
/* eslint quotes:off */
/* eslint indent:off*/

function formEmpty(className) {  //Проверка всех input на пустоту
    array = $('.' + className); //получаем массив из полей которые надо проверить
    for (let i = 0; i < array.length; i++) {
        if ((array[i].name !== 'comment') && !(array[i].value)) {
            return true;
        }
    }
    return false;
}


$(document).on('click', '#back', function() {  //Возврат авто на предыдущую стадию
    const autoId = $(this).attr('car');
    const stadia = $(this).attr('stadia');
    const comment = $('.input');
    if (!comment[0].value) {
        alert('Заполните поле комментария');
        return false;
    }
    $.ajax({
        type: 'POST',
        url: '../ajax/index.php',
        data: 'type=back&comment=' + comment[0].value + '&autoId=' + autoId + '&stadia=' + stadia,
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});

$(document).on('click', '#vozvrat', function() {   
    const autoId = $('#autoInfo').attr('car');
    const stadia = $('#autoInfo').attr('stadia');
    $.ajax({
        type: 'POST',
        url: './ajax/index.php',
        data: 'type=vozvrat&autoId=' + autoId + '&stadia=' + stadia,
        success: function(ret) {
            $('#body').html(ret);
        }
    });
});

$(document).on('click', '#saveNewClient', function() {  // Обработчик события при нажатия кнопки сохранения клиента
    if (formEmpty('input')) {
        alert('Заполните все поля!!!');
        return false;
    }
    const str = $('#newClientData').serialize();
    $.ajax({
        type: 'POST',
        url: './ajax/index.php',
        data: 'type=saveNewClient&' + str,
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});

$(document).on('click', '.sel', function() {    //Вспомогательный обработчик для перевода админом на нужную стадию
    $('.sel').removeClass('act');
    $(this).addClass('act');
});

$(document).on('click', '#adminStadia', function() {    //Перевод на нужную стадию админом
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

$(document).on('click', '#saveNewAuto', function() {   //Обработчик события при нажатии кнопки сохранения авто
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



$(document).on('click', '.stadia', function() {   //передаём на сервер данные по авто и стадии, получаем в ответ инфо по авто и работу для перевода на след стадию 
    $.ajax({
        type: "POST",
        url: '../ajax/index.php',
        data: "type=goStadia&stadia=" + $(this).attr("stadia") + "&autoId=" + $(this).attr("autoId"),
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});


$(document).on('click', '.comeOn', function() { //изменение статуса документа (готово)
    const value = $(this).attr('value');
    const autoId = $(this).attr('car');
    const stadia = $(this).attr('stadia');
    $.ajax({
        type: "POST",
        url: '../ajax/index.php',
        data: 'type=comeOn&doc=' + value + '&autoId=' + autoId + '&stadia=' + stadia,
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});

$(document).on('click', '.otkat', function() { // изменение статуса документа (не готово)
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

$(document).on('click', '#newDate', function() {
    const autoId = $(this).attr('car');
    const form = $('#dateForm').serialize();
    let date = '';
    for (let i = 5;i < form.length; i++) {
        date += form[i];
    }
    $("#date").html('Дата сдачи - ' + date);
    $.ajax({
        type: 'POST',
        url: '../ajax/index.php',
        data: 'type=dateSave&autoId=' + autoId + '&date=' + date,
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});
$(document).on('click', '#poldoc', function() {
    const autoId = $(this).attr('car');
    let stadia = $(this).attr('stadia');
    $.ajax({
        type: 'POST',
        url: '../ajax/index.php',
        data: 'type=nextS&autoId=' + autoId + '&stadia=' + stadia,
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});

$(document).on('click', '#nextS', function() {            //перевод на след стадию
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

$(document).on('click', '.optionStadia', function() { // Фильтр по стадиям
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

$(document).on('click', '#searchGo', function() {   
    const value = $('#searchField').attr('value');
    alert(value);
});

$(document).on('click', '#arhiv', function() { //Перевести в архив
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

$(document).on('click', '.clientFilter', function() { // Фильтр по имени клиента
    const id = $(this).attr('client');
    if (id == 'all') $('.cl').attr('style', '');
    else {
        $('.cl').attr('style', 'display:none;');
        $('#name' + id).attr('style', '');
    }
});

$(document).on('click', '.clientType', function() {  //Фильтр по типу клиентов
    $('.cl').attr('style', 'display:none;');
    $('.' + $(this).attr('client')).attr('style', '');
});

$(document).on('click', '.iconFull', function() {   //Полная инфа по авто или клиенту
    let type = $(this).attr('type');
    let id = $(this).attr(type);
    $.ajax({
        type: 'POST',
        url: '../ajax/index.php',
        data: 'type=full&typeChange=' + type + '&id=' + id,
        success: function(ret) {
            $('.body').html(ret);
            $('.body').css('overflow', 'scroll');
        }
    });
});

$(document).on('click', '#changeData', function() {        // Снять атрибут readonly и disabled со всех Input на странице
    $('input').removeAttr('readonly');
    $('select').removeAttr('disabled');
    $('#fieldSave').html("<div class='flleft info btn' id=save >Сохранить</div><div class='flleft info btn' id='cancel'>Отмена</div>");
});

$(document).on('click', '#cancel', function() {
    $('#fieldSave').html("");
    $('input').attr('readonly', 'true');
    $('select').prop('disabled', true);
});

$(document).on('click', '#save', function() {  //Сохранить внесенные изменения по авто или клиенту
    const typeChange = $('#what').attr('typechange');
    const str = $('#data').serialize();
    if (formEmpty('input')) {
        alert('Заполните все поля!!!');
        return false;
    }
    $.ajax({
        type: 'POST',
        url: '../ajax/index.php',
        data: 'type=changeData&typeChange=' + typeChange + '&' + str,
        success: function(ret) {
            $('.body').html(ret);
        }
    });
});

$(document).on('click', '.xlsx', function() {
    const auto = $(this).attr('auto');
    $.ajax({
        type: 'POST',
        url: '../ajax/index.php',
        data: `type=xlsx&auto=${auto}`,
        success: () => {
            return;
        }
    });
});