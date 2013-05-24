$(function(){
    $(".CouponName").tooltip();
    
    $(".btn-delete").click(function(){
        if(confirm('是否删除这条Coupon?')){
            location.href= $(this).attr('data-href');
        }else{
            return false;
        }
    })
});