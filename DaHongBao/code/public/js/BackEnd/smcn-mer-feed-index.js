
$(function(){
    $("form").submit(function(){
        if($("input[name='value']").val() == ''){
            return false;
        }
    })
});