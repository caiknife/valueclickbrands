
$(function(){
    $("button[name='submit']").removeAttr('disabled');
    $('form').submit(function(){
        var validator = new Validator;
        validator.username($("input[name='username']"));
        validator.notEmpty($("input[name='password']"));
        if(!validator.isValid){
            return false;
        }
    });
});