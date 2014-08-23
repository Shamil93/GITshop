/**
 * Created by zhalnin on 22/08/14.
 */
$(document).ready(function(){

    /**
     * Обработчик удаления из раздела "Товары"
     */
   $('.delete').click(function(){
       var rel = $(this).attr("rel");
       $.confirm({
           'title': 'Потверждение удаления',
           'message': 'После удаления восстановление будет невозможно! Продолжить?',
           'buttons': {
               'Да': {
                   'class': 'blue',
                   'action': function(){
                       location.href = rel;
                   }
               },
               'Нет': {
                   'class': 'gray',
                   'action': function(){}
               }
           }
       });
   });

    /**
     * Обработчик сортировки товаров по типам
     */
    $('#select-links').click(function(){
       $('#list-links').slideToggle(200);
    });

    /**
     * Обработчик сокрытия/отображения поля с текстовым редактором
     */
    $('.h3click').click(function(){
        $(this).next().slideToggle(400);
    });

    /**
     * Динамически добавляем input для загрузки изображений
     */
    var count_input = 1;
    $('#add-input').click(function(){
        count_input++;
        $('<div id="addimage'+count_input+'" class="addimage">'+
            '<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />'+
            '<input type="file" name="galleryimg[]" />'+
            '<a class="delete-input" rel="'+count_input+'" >Удалить</a></div>').fadeIn(300).appendTo('#objects');
    });

    /**
     * Удаляем динамически созданный input
     */
    $('.delete-input').live('click',function(){
        var rel = $(this).attr('rel');

        $('#addimage'+rel).fadeOut(300, function(){
            $('#addimage'+rel).remove();
        });
    });

});