{include file="simple_head.tpl"}
{include file="head.tpl"}
<div id="main">
<div id="left" class="fillBg">
	{$RESOURCE_INCLUDE}
	<div class="categorymenu">
		<ul>
		{section name=outer loop=$category}
	  	<li><a href="{$category[outer].category_url}" class="cmenu">{$category[outer].category_name}</a></li>
		{/section}
		</ul>
	</div>
</div>

<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			<div class="local">{$navigation_path}</div>	
			<!--end adv -->
		<div>
		<table>
		<tr>
    <td width="100%">
      <p class="H1"><b><font size="5">��˽���� </font></b>  </p>

    <p class="MsoNormal"><font face="Arial" size="2">ͨ�������£���������ȡ�����桢ʹ�á������û��������ʶ�����ϣ�����������⣬���磬ע���ҷ���Ϣ�ʼ�������ע���ҷ��ṩ���Ի����񣨸����Ż���Ϣ�������ڴ�����Ϣ���ҷ������Ƿ��ṩ������ṩ���Լ��ṩ����ľ���Ȩ���������������ҷ���˽�������£�</i> <O:P> </O:P><br>
        <br>
        </font><font face="Arial" size="2">������Ϊ���������Ż���Ϣ�Ĺ˿��ṩ�Ķ���վ��, ���з���ȫ����Mezi Media֮�ӻ��������ṩ. Mezi Media �ǳ������û�����˽Ȩ, ���, �ҷ�һ����ѭ��Ϣ����ԭ��, ����������������Ϣ������������� ����˽��������һ����.<O:P> 
        </O:P><br>
        <br>
        </font><font face="Arial" size="2"><b style="mso-bidi-font-weight: normal">��˽����������Χ:</b><br>
        ����˽����������Mezi Media ��ȡ��ʹ�á����桢������Ϣ���κ��������Σ��ҷ����ڻ�ȡ��Ϣ֮ǰ��ȷ��ʾ��ͬʱ�û�����ѡ��Ȩ�����Ƿ������ṩ��Ϣ��<O:P> 
        </O:P><br>
        <br>
        </font><font face="Arial" size="2">��ע�⣬��Ϊ������ɲ��֣�Mezi Media Ϊ��������վ�ṩ�������ӣ�ͬʱΪ���ṩ���������ҷ��Ե�������ȡ��Ϣ֮��Ϊ�����������Ρ�ͬʱ���ҷ�����ʶ�ɵ������ṩ�����ܻ�ȡ�û�cookies�� �����ڸ��ټ�¼��ʶʹ�������</font></p>
      </td>
  </tr>
		</table>
		</div>
			{include file="foot.tpl"}
			<!--end footer -->
		</div>
		<!--end middlecontent -->
	</div>
	<!--end mcontent -->
</div>
<!--end middle -->
<!--
   make_stat({$category_cur},{$merchant_cur},{$coupon_cur},1);
   afp_stat();
//-->
</script>

</div>
<!--end main -->
</body>
</html>
