$(function(){
    $(".alert").alert();
});

//验证表单
//显示错误
var showError = function(jQDom , message){
    var jQDom = jQDom.parents('.input-group');
    jQDom.addClass('error').append("<span class='help-inline'>" + message + "</span>");
};

//字数限制
var StringLength = function(jQDom , maxLength){
    var count = maxLength - jQDom.val().length;
    if(count < 0){
        jQDom.parent().find(".help-inline").remove();
        jQDom.after("<span class='red help-inline'>&nbsp;&nbsp;&nbsp;&nbsp;已超出(" 
                + (0 - count) + ")个字,前台可能会换行</span>");
        return;
    }
    jQDom.parent().find(".help-inline").remove();
    jQDom.after("<span class='help-inline'>&nbsp;&nbsp;&nbsp;&nbsp;还可以输入(" 
            + count + ")个字,超出前台可能会换行</span>");
};