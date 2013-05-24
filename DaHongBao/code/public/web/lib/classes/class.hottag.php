<?php
/**
  * 类名 FormartedText
  * 功能 按照百分比格式化文字
  * 版本 V1.0
  * 日期 2007-2-26
  * 作者 Nature Zhao
  * 参考文档 无
  */ 
class FormartedText{
	
	private $format_array = array(); /* 百分比字符串数组 */
	
	private $bSort = true; /* 是否排序 */
	
	private $bSort_after_format = true; /* 在格式化之后排序 */
	
	/**
	  * @spec 构造函数
	  * @return void
	  * @params
	  * $_format_array : 百分比字符串数组 2纬数组 包括percentage和format2个纬度
	  * $_bSort 默认true 是否排序
	  * $_bSort_after_format 默认true在格式化之后排序 false在格式化之前排序
	  */
	public function __construct(
		& $_format_array,
		$_bSort = true,
		$_bSort_after_format = true	){
		$this->format_array = $_format_array;
		$this->bSort = $_bSort;
		$this->bSort_after_format = $_bSort_after_format;
	}
	
	/**
	  * @spec 获得格式化后的数组
	  * @return array格式化后的数组
	  * @params
	  * $original_array原始的没有格式化的数组
	  */
	public function get_formated_array(& $original_array){
		// get count of $original_array
		$array_count = count($original_array);

		if (($this->bSort) && (!$this->bSort_after_format)){
			// sort by value
			sort($original_array);
		}
		
		// create key-value array 
		// key is the text which need formating
		// value is the percentage
		$new_array = array();
		foreach ($original_array as $value){
			$new_array[$value] = 0;
		}

		// set value as index of $format_array
		$i = 0;
		reset($new_array);
		foreach ($new_array as $key => $value) {
		    $new_array[$key] = $this->get_format_index($i, $array_count, $this->format_array);
		    $i++;
		}
		
		if (($this->bSort) && ($this->bSort_after_format)){
			// sort by keyword
			ksort($new_array);
		}
		
		// create return_array
		$return_array = array();
		$i = 0;
		foreach ($new_array as $key => $value) {
		    $return_array[$i] = sprintf($this->format_array[$value]['format'], urlencode($key), $key);
		    $i++;
		}
		return $return_array;
	}
	
	/**
	  * @desc 获得格式数组index
	  * @return 格式数组index
	  * @params
	  * $no: 序号（从0开始）
	  * $total: 总数
	  * $format_array: 百分比字符串数组
	  */
	private function get_format_index($no, $total, & $format_array){
	
		if ($no < 0 || $no >= $total) return;
	
		$total_percent = 0;
		foreach ($format_array as $item) {
			$total_percent += $item['percentage'];
			if ($total_percent > 100) return;
		}
		if ($total_percent != 100) return;
	
		$no100 = (int)((($no + 1) * 100 / $total));
		
		// Modified by Nature Zhao Feb. 28, 2007
		// Modify Begin
		//Del for ($i = 0, $array_length = count($format_array); (($i < $array_length) && ($no100 > $format_array[$i]['percentage'])); $i++);
		$i = 0;
		$percentage = 0;
		$array_length = count($format_array);
		$percentage += $format_array[$i]['percentage'];
		while ($no100 > $percentage)
		{
			$i++;
			if ($i == $array_length) break;
			$percentage += $format_array[$i]['percentage'];
		}
		// Modify End
		
		if ($i == $array_length){
			$i = $array_length - 1;
		}
		return $i;
	}// end of function
} // end of class

class HotTag{
	public function show(&$define_format_array, &$tag_array){
		$formatText = new FormartedText($define_format_array);
		$formated_array = $formatText->get_formated_array($tag_array);
		
		// echo keyword with format after sort
		$r = "";
		foreach ($formated_array as $value) {
			$r .= $value;
		}
		return $r;
	}
}
?>