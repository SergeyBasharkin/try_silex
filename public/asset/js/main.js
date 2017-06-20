/**
 * Created by sergey on 20.06.17.
 */
$(document).on('click',"#parent-comment-js", function () {
   console.log($(this).data('id'));
   $("#parent").val($(this).data('id').toString());

});