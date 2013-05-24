$(function(){
    $("input[name='CouponName']").bind("keyup change" , function(){
        StringLength($(this) , 12);
        return false;
    });
    
});