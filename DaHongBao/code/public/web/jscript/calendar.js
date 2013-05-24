var dateArray=["日","一","二","三","四","五","六"];
var MilanCalendarPanelID="MilanCalendarPanel";
var MilanRightPanelID="MilanRightPanel";
var MilanRightTabID="MilanRightTab";
var prevButton2ID="prevButton2"; 
var MilanleftTabID="MilanleftTab";
var MilanLeftPanelID="MilanLeftPanel";
var prevButtonID="prevButton";
var Regs = /\d/; 
var date=new Date();
var date2=new Date();
var todayDate=new Date();
var bbDate=new Date();
var fromDate=bbDate.getFullYear()+"-"+(bbDate.getMonth()+1)+"-"+bbDate.getDay();
 
//时间控件的显示或隐藏的参数
var XP_isInDateDiv;
//声明控件的变量名称
var xp_txt_InputDates;
//是否显示所有日期
var xp_showAllDates;
//是否隐藏页面所有的下拉列表框
var xp_isHideDropDownList=true;

function ConsturctionCalendar()
{   
    dateSource=new Date();
    dateSource2=new Date(dateSource.getFullYear(),(dateSource.getMonth()+1));
    initial(dateSource,MilanleftTabID,prevButtonID,MilanLeftPanelID);
    initial2(dateSource2,MilanRightTabID,prevButton2ID,MilanRightPanelID);
    attachEvent2();
}
function DoActive(obj,xpisshow)
{
    xp_showAllDates=xpisshow;
    ConsturctionCalendar();
    obj.focus();
    xp_txt_InputDates=obj.id;
    SetCalendarPosition(obj);
    ShowMotherPanel();
}
/// <summary>
/// 设置位置
/// </summary>
function SetCalendarPosition(obj)
{
    var MilanCalendarP=document.getElementById(MilanCalendarPanelID);    
    if(document.all)
    {
        MilanCalendarP.style.left=XP_getPoint(obj).x-25+"px";
        MilanCalendarP.style.top=XP_getPoint(obj).y-3+"px";  
    }
    else
    {
        MilanCalendarP.style.left=XP_getPoint(obj).x-25+"px";
        MilanCalendarP.style.top=XP_getPoint(obj).y-3+"px"; 
    }
}
/// <summary>
/// 获取当前元素的X，Y位置
/// </summary>
function XP_getPoint(source)
{
	var pt = {x:0,y:0};	
	do
	{
		pt.x += source.offsetLeft;
		pt.y += source.offsetTop;
		source = source.offsetParent;
	}
	while(source);		
	return pt;
}
/// <summary>
/// 打开日期框
/// </summary>
function ShowMotherPanel()
{
    var Panel=document.getElementById(MilanCalendarPanelID);
    Panel.style.display="block";
    Panel.style.zIndex="99999";
}
/// <summary>
/// 关闭日期的显示
/// </summary>
function CloseMotherPanel()
{    
    var Panel=document.getElementById(MilanCalendarPanelID);
    Panel.style.display="none";
    Panel.style.zIndex="";
}
/// <summary>
/// 前进
/// </summary>
function GrayUnRight()
{
    var tds=document.getElementById(MilanRightTabID).getElementsByTagName("td");
    for(var i=9;i<tds.length;i++)
    {
        if(Regs.test(tds[i].innerHTML))
        {
           var theday=date2.getFullYear()+"-"+(date2.getMonth()+1)+"-"+tds[i].innerHTML;
           if(CompareDate(theday,fromDate)==false)
           {
                tds[i].style.color="#ddd";
                tds[i].onclick="";
                tds[i].onmouseover="";
                tds[i].onmouseout="";
                tds[i].style.cursor="default";
           }
        }
    }
}
/// <summary>
/// 后退
/// </summary>
function GrayLeft()
{
    var tds=document.getElementById(MilanleftTabID).getElementsByTagName("td");
    for(var i=9;i<tds.length;i++)
    {
        if(Regs.test(tds[i].innerHTML))
        {
           var theday=date.getFullYear()+"-"+(date.getMonth()+1)+"-"+tds[i].innerHTML;
           if(CompareDate(theday,fromDate)==false)
           {
                tds[i].style.color="#ddd";
                tds[i].onclick="";
                tds[i].onmouseover="";
                tds[i].onmouseout="";
                tds[i].style.cursor="default";
           }
        }
    }
}
/// <summary>
/// 左侧选择日期后
/// </summary>
function trOnClick()
{   
       var startDayInput= document.getElementById(xp_txt_InputDates);
       startDayInput.value=Milan_FormatDateString(date.getFullYear()+"-"+(date.getMonth()+1)+"-"+this.innerHTML);
       attachEvent2();
       CloseMotherPanel(); 
       return;
}
/// <summary>
/// 右侧选择日期后
/// </summary>
function trOnClick2()
{
   try
   { 
       var startDayInput= document.getElementById(xp_txt_InputDates);
       startDayInput.value=Milan_FormatDateString(date2.getFullYear()+"-"+(date2.getMonth()+1)+"-"+this.innerHTML);
       attachEvent2();
       CloseMotherPanel(); 
       return;               
   }
   catch(e)
   {
        alert(e);
   }
}
/// <summary>
/// 为选择日期加上鼠标事件
/// </summary>
function attachEvent2()
{
    var tds=document.getElementById(MilanleftTabID).getElementsByTagName("td");
    for(var i=9;i<tds.length;i++)
    {
        if(Regs.test(tds[i].innerHTML))
        {
            
            var todayDate=date.getFullYear()+"-"+(date.getMonth()+1)+"-"+tds[i].innerHTML;
            
            var dateToday=new Date();
            var NowToday=dateToday.getFullYear()+"-"+(dateToday.getMonth()+1)+"-"+dateToday.getDate();

            if(CompareDate(NowToday,todayDate)=="equal")
            {                
                tds[i].style.cursor="pointer";
                tds[i].title="今天是:"+NowToday;
                tds[i].style.border="1px solid red";
            }
            else if(!xp_showAllDates)
            {
                if(CompareDate(NowToday,todayDate)==true)
                {
                    tds[i].style.color="#fff";
                    tds[i].style.cursor="default";
                    continue;
                }
                else
                {
                    tds[i].onmouseover=function()
                    {
                        this.style.cursor="pointer";
                        this.style.backgroundColor="#ebebeb";
                    }
                    tds[i].onmouseout=function()
                    {
                        this.style.backgroundColor="";
                    }
                }
            }
            else
            {
                tds[i].onmouseover=function()
                {
                    this.style.cursor="pointer";
                    this.style.backgroundColor="#ebebeb";
                }
                tds[i].onmouseout=function()
                {
                    this.style.backgroundColor="";
                }
            }            
            tds[i].style.color="black";
            tds[i].onclick=trOnClick;
        }
    }
    tds=document.getElementById(MilanRightTabID).getElementsByTagName("td");
    for(var i=9;i<tds.length;i++)
    {
        if(Regs.test(tds[i].innerHTML))
        {
            var todayDate=date2.getFullYear()+"-"+date2.getMonth()+"-"+tds[i].innerHTML;
            var dateToday=new Date();
            var NowToday=dateToday.getFullYear()+"-"+dateToday.getMonth()+"-"+dateToday.getDate();
            if(CompareDate(NowToday,todayDate)=="equal")
            {
                
                tds[i].style.cursor="pointer";
                tds[i].title="今天是:"+dateToday.getFullYear()+"-"+(dateToday.getMonth()+1)+"-"+dateToday.getDate();
                tds[i].style.border="1px solid red";
            }
            else if(!xp_showAllDates)
            {
                if(CompareDate(NowToday,todayDate)==true)
                {
                    tds[i].style.color="#fff";
                    tds[i].style.cursor="default";
                    continue;
                }
                else
                {
                    tds[i].onmouseover=function()
                    {
                        this.style.cursor="pointer";
                        this.style.backgroundColor="#ebebeb";
                    }
                    tds[i].onmouseout=function()
                    {
                        this.style.backgroundColor="";
                    }
                }
            }
            else
            {
                tds[i].onmouseover=function()
                {
                    this.style.cursor="pointer";
                    this.style.backgroundColor="#ebebeb";
                }
                tds[i].onmouseout=function()
                {
                    this.style.backgroundColor="";
                }
            }
            tds[i].style.color="black";
            tds[i].onclick=trOnClick2;
        }
    }
}

/// <summary>
/// 画出日期框
/// </summary>
function DrawCalendar(date2,MilanRightTabID2,prevButton2ID2,MilanRightPanelID2)
{
    var monthDays=getDaysFromTheMonth(date2.getMonth(),date2.getFullYear());
        
    var html="<div>";
    html+="<table id='"+MilanRightTabID2+"' style='width:208px;z-index:9998px;'>";
    html+="<tr style='background:#eee;height:15px;line-height:15px;'><td style=\"height:15px;\"><div class='MilanCalendarArrowLeft' id='"+prevButton2ID2+"' onclick='prevMonth();'></div></td><td colspan='5' style='text-align:center;cursor:default;height:15px;line-height:15px;'><div><span style='color:red;font-size:14px;font-weight:bolder;'>"+date2.getFullYear()+"</span>年<span style='color:red;font-size:16px;font-weight:bolder;'>"+(date2.getMonth()+1)+"</span>月"+"</div></td><td style=\"height:15px;line-height:15px;\"><div class='MilanCalendarArrowRight' onclick='nextMonth()' ></div></td></tr>";
    html+="<tr style='cursor:default;font-weight:bold;background:#eee;height:15px;line-height:15px;'>";
    for(var i=0;i<dateArray.length;i++)
    {
        html+="<td style=\"height:15px;line-height:15px;\">"+dateArray[i]+"</td>";
    }
    html+="</tr>";
    var newDate=new Date(date2.getFullYear(),date2.getMonth(),1)
    var theDay=newDate.getDay();
    var startCellIndex=theDay;
    var days=1;
    var loop=true;
    while(days<=monthDays)
    {
        html+="<tr>";
        if(loop==true)
        {
             for(var i=0;i<startCellIndex;i++)
                {
                    html+="<td  style=\"height:14px;line-height:14px;\"></td>"; 
                }
                for(var i=startCellIndex;i<7;i++)
                {
                    
                    html+="<td  style=\"height:14px;line-height:14px;\">"+days+"</td>"; 
                    days++;
                    if(days>monthDays)
                    break;
                }
                loop=false;
         }
         else
         {
            for(var i=0;i<7;i++)
            {
                html+="<td style=\"height:14px;line-height:14px;\">"+days+"</td>"; 
                days++;
                if(days>monthDays)
                break;
            }
         }
        html+="</tr>";
    }
    html+="</table>";
    html+="</div>";
    document.getElementById(MilanRightPanelID2).innerHTML=html;
}
function initial(dateTime,MilanleftTabID2,prevButtonID2,MilanLeftPanelID2)
{
    date=dateTime;
    DrawCalendar(date,MilanleftTabID2,prevButtonID2,MilanLeftPanelID2);
    if(!xp_showAllDates)
    {
        disableFalseMonth(date,prevButtonID2);
    }
}
function initial2(dateTime,MilanRightTabID2,prevButton2ID2,MilanRightPanelID2)
{   
    date2=dateTime;
    DrawCalendar(date2,MilanRightTabID2,prevButton2ID2,MilanRightPanelID2);
    if(!xp_showAllDates)
    {
        disableFalseMonth(date2,prevButton2ID2);
    }
}
function disableFalseMonth(datec,prevButton2ID2)
{
    var TodayDate=new Date();
    var prevButton=document.getElementById(prevButton2ID2);
    if(datec>TodayDate)
    {   
       prevButton.style.visibility="visible";
    }
    else
    {
       prevButton.style.visibility="hidden";
    }
}
/// <summary>
/// 上一个月
/// </summary>
function prevMonth()
{
    var NewDate=new Date(date.getFullYear(),(parseInt(date.getMonth())-1));
    initial(NewDate,MilanleftTabID,prevButtonID,MilanLeftPanelID);
    var NewDate2=new Date(date2.getFullYear(),(parseInt(date2.getMonth())-1));
    initial2(NewDate2,MilanRightTabID,prevButton2ID,MilanRightPanelID);
    attachEvent2();
}
/// <summary>
/// 下一个月
/// </summary>
function nextMonth()
{
    var NewDate=new Date(date.getFullYear(),(parseInt(date.getMonth())+1));
    initial(NewDate,MilanleftTabID,prevButtonID,MilanLeftPanelID);
    var NewDate2=new Date(date2.getFullYear(),(parseInt(date2.getMonth())+1));
    initial2(NewDate2,MilanRightTabID,prevButton2ID,MilanRightPanelID);
    attachEvent2();
    if(!xp_showAllDates)
    {
        GrayLeft();
        GrayUnRight();   
    }
}

function getDaysFromTheMonth(month,year)
{
    var count=0;
    switch(month)
    {
        case 0:
        case 2:
        case 4:
        case 6:
        case 7:
        case 9:
        case 11:
            count=31;
            break;
        case 3:
        case 5:
        case 8:
        case 10:
            count=30;
            break;
       case 1:
    if(year%4==0)
        {
            count=29;
            break;
        }
    else
        {
            if((year%100==0)&(year%400!=0))
                count=29;
                count=28;
        }
    }
    return count;
}

function Milan_FormatDateString(dateString)
{
    var dar=dateString.split("-");
    var mo=dar[1];
    var da=dar[2];
    if(mo<10)
    {
        if(mo.length<=1)
            mo="0"+mo;
    }
    if(da<10)
    {
        if(da.length<=1)
           da="0"+da;
    }
    return dar[0]+"-"+mo+"-"+da;
} 
/// <summary>
/// 验证日期格式
/// </summary>
function CompareDate(date1,date2)
{
    var splitLetter="-";
    var dateArray1=date1.split(splitLetter);
    var dateArray2=date2.split(splitLetter);
    if(dateArray1.length!=3||dateArray2.length!=3)
        return "length error";
    for(var i=0;i<3;i++)
    {
        if(dateArray1[i]==""||dateArray2[i]=="")
            return "year-month-day error";
    }
    if(dateArray1[0].length!=4||dateArray2[0].length!=4)
    {
       return "relly?the year Number is right?";
    }
    if(dateArray1[0]<=2006||dateArray2[0]<=2006)
    {
        return "too earyly error";
    }
    if(dateArray1[1]<=0||dateArray1[1]>12)
    {
        return "what a big month you called!";
    }
    if(dateArray1[2]<=0||dateArray1[2]>31)
    {
        return "day must between 1 and 31!";
    }
    if(dateArray2[1]<=0||dateArray2[1]>12)
    {
        return "what a big month you called!";
    }
    if(dateArray2[2]<=0||dateArray2[2]>31)
    {
        return "day must between 1 and 31!";
    }
    if(dateArray1[0]<dateArray2[0])
    {
        return false;
    }
    else if(parseInt(dateArray1[0])>parseInt(dateArray2[0]))
    {
        return true;
    }
    else
    {
        if(parseInt(dateArray1[1])<parseInt(dateArray2[1]))
        {
            return false;
        }
        else if(parseInt(dateArray1[1])>parseInt(dateArray2[1]))
        {
            return true;
        }
        else
        {
            if(parseInt(dateArray1[2])<parseInt(dateArray2[2]))
            {
                return false;
            }
            else if(parseInt(dateArray1[2])>parseInt(dateArray2[2]))
            {
                return true;
            }
            else
            {
                return "equal";
            }
        }
    }       
}
/*初始化日期的值*/
function Milan_FillDateText()
{
    var sDate=document.getElementById("MilanIntel_StartTimeTextBox");
    sDate.value=Milan_GetFiveLateAfterToday(5);
    var eDate=document.getElementById("MilanIntel_BackTimeTextBox");
    eDate.value=Milan_GetFiveLateAfterToday(10);
}
function Milan_GetFiveLateAfterToday(addDays)
{
    var sDate=new Date();
    var tDate=new Date(sDate.getFullYear(),sDate.getMonth(),sDate.getDate());
    tDate.setDate(tDate.getDate()+addDays);
    return Milan_FormatDate(tDate); 
}
function Milan_FormatDate(tDate)
{   
   var ye=tDate.getFullYear();
   var mo=tDate.getMonth()+1;
   if(mo<10)
        mo="0"+mo;
   var day=tDate.getDate();
   if(day<10)
        day="0"+day;
   return ye+"-"+mo+"-"+day;
}

/// <summary>
/// 判断日期
/// </summary>
function xp_isDate(str){ 
var reg = /^((((1[6-9]|[2-9]\d)\d{2})-(0?[13578]|1[02])-(0?[1-9]|[12]\d|3[01]))|(((1[6-9]|[2-9]\d)\d{2})-(0?[13456789]|1[012])-(0?[1-9]|[12]\d|30))|(((1[6-9]|[2-9]\d)\d{2})-0?2-(0?[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))-0?2-29-))$/
if (reg.test(str)) return true;
return false;
}

/** 
 * 添加鼠标事件，主要用于点击不是在日期框内时的隐藏
 */ 
document.onclick = function(oEvent) {
	var e;
	var ta;
	if(document.all) {
		e = window.event;
		ta = e.srcElement;
	}
	else {
		e = oEvent;
		ta = e.target;
	}
	//日期
	if ("destination" != ta.id) {
		$("#citylist").hide();
	}
	if ("flightFromCity" != ta.id) {
		$("#flightCitylist").hide();
	}
	
	
	var begiontime = document.getElementById(xp_txt_InputDates);
	if(begiontime != null) {
		if(ta.id != xp_txt_InputDates) {
			if(XP_isInDateDiv){
				return;
			}
			else if(ta.id=="MilanIntel_StartTimeBtn") {
				return;
			}
			else if(ta.id=="MilanIntel_BackTimeBtn") {
				return;
			}
			else if(ta.id=="xp_spanreturndate") {
				return;
			}
			else if(ta.id=="xp_spangodate") {
				return;
			}
			CloseMotherPanel();
		}
	}

	

}
