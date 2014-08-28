/**
 * Created by zhalnin on 22/08/14.
 */
$(document).ready(function(){

    /**
     * Обработчик удаления из раздела "Товары"
     */
   $('.delete').click(function(e){
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
       $('#list-links,#list-links-sort').slideToggle(200);
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


    /**
     * Удаляем изображения из галереи изображений
     * в описании
     */
    $('.del-img').click(function(){
        var img_id = $(this).attr('img_id');
        var title_img = $("#del"+img_id+" > img").attr('title');

        $.ajax({
            type: "POST",
            url: "actions/delete-gallery.php",
            data: "id="+img_id+"&title="+title_img,
            dataType: "html",
            cache: false,
            success: function(data) {
                if(data == "delete") {
                    $("#del"+img_id).fadeOut(300);
                }
            }
        });
    });


    /**
     * Удаляем категории по выбору из select - option
     */
    $('.delete-cat').click(function(){
         var select_id = $('#cat_type option:selected').val();
        if(!select_id) {
            $('#cat_type').css('borderColor', '#f5a4a4');
        } else {
            $.ajax({
                type: 'POST',
                url: 'actions/delete-category.php',
                data: 'id='+select_id,
                dataType: 'html',
                cache: false,
                success: function(data) {
                    if(data == 'delete') {
                        $('#cat_type option:selected').remove();
                    }
                }
            });
        }
    });


    /**
     * Скрываем отображаем блок с управление пользователей
     */
    $('.block-clients').click(function(e){
        e.preventDefault();
        $(this).find('ul').slideToggle(300);
    });


    /**
     * Сокрытие или отображение модального окна
     * добавления новости
     */
    $(".news").fancybox();


    /**
     * Обработчик для выделения или снятия чекбоксов в привелегиях
     */
    $('#select-all').click(function(){
         $('.privilege input:checkbox').attr('checked', true);
    });
    $('#remove-all').click(function(){
         $('.privilege input:checkbox').attr('checked', false);
    });
});