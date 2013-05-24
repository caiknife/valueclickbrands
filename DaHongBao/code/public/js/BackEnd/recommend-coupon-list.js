$(function(){
    $("#sites").change(function(){
        location.href = '/recommend/couponList?SiteID=' + $(this).val();
    });
    
});