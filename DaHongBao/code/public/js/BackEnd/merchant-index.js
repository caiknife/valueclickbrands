$(function(){
    $("#allCheck").click(function(){
        if($(this).prop('checked')){
            $("tbody :checkbox").prop('checked' , true);
        }else{
            $("tbody :checkbox").prop('checked' , false);
        }
    })
    
    $("#sites").change(function(){
        window.location = '/merchant/index?SiteId=' + $(this).val();
    })
});