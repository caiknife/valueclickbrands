$(function(){
    setOrderNumber();
    $( "#list" ).sortable({
        'stop': function(event,ui){
            setOrderNumber();
        }
    });
    $("#list").disableSelection();
    count = $("#list li").size();
    
    $("form").submit(function(){
        $("#list li").each(function(i,v){
            $(this).find('input').val(count - i);
        });
        
        return true;
    });
});

var setOrderNumber = function(){
    $("#list li").each(function(i,v){
        $(this).find(".orderNumber").text(i + 1);
    });
}