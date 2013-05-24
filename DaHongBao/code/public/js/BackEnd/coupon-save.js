$(function(){
    $.datepicker.setDefaults({dateFormat:'yy-mm-dd'});
    
    $("#SearchMechant").click(function(){
        $( "#merchant" ).dialog({
            modal:true,
            buttons:{
                "选择":function(){
                    $("input[name='MerchantID']").val($("input[name='mid']:checked").val());
                    $("#merchantName").text($("input[name='mid']:checked").next("span").text());
                    $("input[name='MerchantName']").val($("input[name='mid']:checked").next("span").text());
                    creatCates($("input[name='mid']:checked").val());
                    affiliateID = $("input[name='mid']:checked").next("span").next().val();
                    $("select[name='AffiliateID']").val(affiliateID);
                    $( this ).dialog( "close" );
                    
                }
            }
        });
    })
    
    
    $("#search").click(function(){

        var params = {};
        params.MerchantName = $("#searchMerchant").val();
        if('' == params.MerchantName){
            return false;
        }
        
        $("#loading").show();
        
        $("#result").empty();
        $.get('/ajax/getMerchants' , params , function(data){
            $("#loading").hide();
            if('' == data){
                $("#result").append("<li>没有结果</li>");
            }
            data = eval('(' + data + ')');
            
            $.each(data , function(i , v){
                $("#result").append("<li><input type='radio' name='mid' value='" + v.MerchantID + "' /><span>" 
                        + v.MerchantName + "</span><input type='hidden' value='" + v.AffiliateID + "' /></li>");
                $("#selecedMerchant").show();
            });
            
            
        });
    });
    
    $("input[name='CouponStartDate']").datepicker();
    $("input[name='CouponEndDate']").datepicker();
    $("select[name='SiteID']").change(function(){
        location.href = '?' + $("#query").val() + "&SiteID=" + $(this).val();
    });
    
    $("#creatName").click(function(){
        if($("input[name='CouponAmount']").val() != '' && $("input[name='CouponAmount']").val() != '0'){
            couponName = "满" + $("input[name='CouponAmount']").val() 
            + "减"  + $("input[name='CouponReduction']").val();
        }else if($("input[name='CouponDiscount']").val() != '' && $("input[name='CouponDiscount']").val() != '0'){
            couponName = $("input[name='CouponDiscount']").val() + '折';
        }else{
            return false;
        }
        
        $("input[name='CouponName']").val(couponName);
        return true;
    })
    
    $(".cleditor").cleditor({
        width:        500, // width not including margins, borders or padding
        height:       250, // height not including margins, borders or padding
        controls:     // controls to add to the toolbar
                      "bold italic underline strikethrough subscript superscript | font size " +
                      "style | color highlight removeformat | bullets numbering | outdent " +
                      "indent | alignleft center alignright justify | undo redo | " +
                      "rule image link unlink | cut copy paste pastetext | print source",
        colors:       // colors in the color popup
                      "FFF FCC FC9 FF9 FFC 9F9 9FF CFF CCF FCF " +
                      "CCC F66 F96 FF6 FF3 6F9 3FF 6FF 99F F9F " +
                      "BBB F00 F90 FC6 FF0 3F3 6CC 3CF 66C C6C " +
                      "999 C00 F60 FC3 FC0 3C0 0CC 36F 63F C3C " +
                      "666 900 C60 C93 990 090 399 33F 60C 939 " +
                      "333 600 930 963 660 060 366 009 339 636 " +
                      "000 300 630 633 330 030 033 006 309 303",    
        fonts:        // font names in the font popup
                      "Arial,Arial Black,Comic Sans MS,Courier New,Narrow,Garamond," +
                      "Georgia,Impact,Sans Serif,Serif,Tahoma,Trebuchet MS,Verdana",
        sizes:        // sizes in the font size popup
                      "1,2,3,4,5,6,7",
        styles:       // styles in the style popup
                      [["Paragraph", "<p>"], ["Header 1", "<h1>"], ["Header 2", "<h2>"],
                      ["Header 3", "<h3>"],  ["Header 4","<h4>"],  ["Header 5","<h5>"],
                      ["Header 6","<h6>"]],
        useCSS:       false, // use CSS to style HTML when possible (not supported in ie)
        docType:      // Document type contained within the editor
                      '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
        docCSSFile:   // CSS file used to style the document contained within the editor
                      "", 
        bodyStyle:    // style to assign to document body contained within the editor
                      "margin:4px; font:10pt Arial,Verdana; cursor:text"
    });
})

//获取商家类别
var creatCates = function(mid){
    var loading = $("#loading").clone();
    
    $("#categories").empty().append(loading);
    
    $.get('/ajax/getMerchantCate' , {mid:mid} , function(data){
        if('' == data){
            return false;
        }
        
        data = eval('(' + data + ')');
        $("#categories").empty()
        $.each(data , function(i,v){
            $("#categories").append("<label><input name='CategoryID[]' type='checkbox' value='"
                    + v.CategoryID + "' checked='checked' />"+ v.CategoryName + "</label>");
        })
    })
}