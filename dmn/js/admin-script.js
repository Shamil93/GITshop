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

});