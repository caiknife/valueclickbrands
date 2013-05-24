$(function(){
    $.datepicker.setDefaults({dateFormat:'yy-mm-dd'});
    
    $("#startTime,#endTime").datepicker();
    
    $("#allCheck").click(function(){
        if($(this).prop('checked')){
            $("tbody :checkbox").prop('checked' , true);
        }else{
            $("tbody :checkbox").prop('checked' , false);
        }
        
    });
    
    $("#sites").change(function(){
        window.location='?siteid=' + $(this).val();
    });
})