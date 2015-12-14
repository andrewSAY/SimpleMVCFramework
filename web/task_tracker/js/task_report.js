/**
 * Created by Сапрыкин А_Ю on 09.09.15.
 */
function initTaskShowScripts()
{
    var formDialog = form_dialog_construct('#form_dialog', '#error_dialog', afterSuccessPost);
    var accordionO = $($('#report_accordion'));
    var commentsCount = $($('#comments_count'));
    accordionO.accordion();

    $('#new_report').click(function(e){
        e.preventDefault();
        formDialog.open('Новый комментарий', formUrl );
        return false;
    });
    //alert(print_object(widget));

    function afterSuccessPost(data, submited)
    {
        if(data.status == 'ok')
        {
            accordionO.prepend(data.view).accordion('refresh');
            var currenCount = commentsCount.text()*1;
            currenCount = currenCount + 1;
            commentsCount.text(commentsCount.text() *1 + 1);
            submited.close();
        }
    }
}
