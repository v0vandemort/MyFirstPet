//$('div');//обращение по тегу
//$('div nav');//обращение по тегу к подтегу div - nav
//$('.logs');//обращение по классу
//$('.logs .icons');//обращение по классу к подклассу к всем потомкам
//$('.logs > .icons');//обращение по классу к подклассу  к прямым потомкам
//$('.logs .icons + h4');//обращение к элементу через соседство (ТОЛЬКО h4 дочки .logs которые стоят после .icons)
//$('#logs');//обращение по атрибуту
//$('img[height=150]');//обращение через тег и фильтр по значению атрибута
//$('img[height^=1]');//обращение через тег и фильтр по началу строки значения атрибута
//$('img[height$=0]');//обращение через тег и фильтр по концу строки значения атрибута
//$('img[height*=0]');//обращение через тег и фильтр по наличию в строке значения атрибута кусочка строки
// function logs()
// {
//     $(request.data).find('div h7');
// }
//
// function dialogue()
// {
//     $(request.data).find('h7[class=dialogue]')
//
// }
// function resetForm()
// {
//     $('#cityPaste')[0].reset();
// }
//
// function submitForm()
// {
//     $("#btnSubmit").on("click", function() {
//         var $this 		    = $("#btnSubmit"); //submit button selector using ID
//         var $caption        = $this.html();// We store the html content of the submit button
//         var form 			= "#cityPaste"; //defined the #form ID
//         // var formData        = $(form).serializeArray(); //serialize the form into array
//         var route 			= $(form).attr('action'); //get the route using attribute action
//
//         // Ajax config
//         $.ajax({
//             type: "POST", //we are using POST method to submit the data to the server side
//             // url: route, // get the route value
//             data: cityPaste, // our serialized array data for server side
//             beforeSend: function () {//We add this before send to disable the button once we submit it so that we prevent the multiple click
//                 $this.attr('disabled', true).html("Отправка...");
//             },
//             success: function (response) {//once the request successfully process to the server side it will return result here
//                 $this.attr('disabled', false).html($caption);
//
//                 // Reload lists LOGS
//                 logs();
//
//                 // We will display the result using alert
//                 alert(response);
//
//                 // Reset form
//                 resetForm();
//             },
//             error: function (XMLHttpRequest, textStatus, errorThrown) {
//                 // You can put something here if there is an error from submitted request
//             }
//         });
//     });
// }
//
// function subForm(){
//     var form 			= "#cityPaste";
//     $.ajax({
//         type:'POST',
//         url: ". ./game.php",
//         data: form,
//         dataType: "json",
//         success: function (responce) {
//             $('.dialogue').html(responce.dialogue);
//
//
//         }
//
//     })
// }




// function subForm(city) {
//     $.ajax({
//         type: "POST",
//         url: "../game.php",
//         data: { city: city },
//         dataType: "json",
//         success: function (response) {
//             // Обновление элементов на странице
//             $('.dialogue').html(response.dialogue);
//             $('div h7').html(response.div);
//         },
//         error: function (xhr, status, error) {
//             // Обработка ошибок
//             console.error(error);
//         }
//     });
// }
//
// $(document).ready(function () {
//     // Выполнение AJAX-запроса при загрузке страницы
//
//     subForm();
//     $("#cityPaste").submit(function (e){
//         e.preventDefault();
//
//         var city = $("#cityInput").val()
//         subForm(city);
//     });
//
//     // Допустим, у вас есть кнопка, по которой нужно обновлять элементы
//     $("#btnSubmit").on("click", function () {
//
//         var city = $("#cityInput").val()
//         subForm(city);
//     });
// });


function logs()
{
    $logs = find('div h7');
}

function dialogue()
{
    $(request.data).find('h7[class=dialogue]')

}
function resetForm()
{
    $('#cityPaste')[0].reset();
}

$("#btnSubmit").click(function() {
    // Получение текста из текстового поля
    var text = $("#cityInput").val();

    // Отправка текста на сервер с помощью AJAX
    $.ajax({
        url: "../game.php", // Путь к вашему файлу game.php
        method: "POST", // Используем метод POST
        data: { text: text }, // Отправляем текст в виде параметра
        dataType: "json", // Ожидаем ответ в формате JSON
        success: function(response) {
            // Обработка успешного ответа
            // Выводим полученные данные на страницу
            $(".dialogue-container").text(JSON.stringify(response));
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Обработка ошибки
            // Выводим сообщение об ошибке
            $("#result").text("Ошибка: " + textStatus + " " + errorThrown);
        }



    });
    // $('div h7').html(responce.div);
    // $('h7[class=dialogue]').html(responce.dialogue);
});
