<?php
class SPFormVerification{

	/**
	 * 表单验证
	 *
	 * 详细说明
	 * @形参
	 * @访问      公有
	 * @返回值    void
	 * @throws
	 * helius
	 */
	function form($form,array $reg,$is_return = FALSE)
	{
		if(is_array($form))
		{
			$new = array();
		}
		else
		{
			$new = NULL;
		}

		foreach($reg AS $k=>$row)
		{
			if(isset($row['reg']))
			{
				if(is_array($form))
				{
					$f = $form[$k];
				}
				else
				{
					$f = $form;
				}

				if($row['is_empty'] === TRUE && empty($f))
				{
					if(is_array($form))
					{
						$new[$k] = $f;
					}
					else
					{
						$new = $f;
					}
				}
				elseif($row['is_empty'] === FALSE && empty($f))
				{
					$e = array(
						'error'	=> array(
							'error_typ' =>'isEmpty',
							'error_msg'	=> (empty($row['name'])?$k:$row['name']).'-字段不能为空'
						)
					);

					if($is_return === TRUE)
					{
						return $e;
					}
					else
					{
						$this->show_message($e['error']['error_msg']);
					}
				}
				else
				{
					if(preg_match($row['reg'], $f))
					{
						if(is_array($form))
						{
							$new[$k] = $f;
						}
						else
						{
							$new = $f;
						}
					}
					else
					{
						$e = array(
							'error'	=> array(
								'error_typ' =>'regexNotMatch',
								'error_msg'	=> (empty($row['name'])?$k:$row['name']).'-字段不符合格式要求'
							)
						);

						if($is_return === TRUE)
						{
							return $e;
						}
						else
						{
							$this->show_message($e['error']['error_msg']);
						}
					}
				}
			}
			else
			{
				/*if(empty($form[$k]))
				{
					continue;
				}*/

				if(!isset($form[$k][0]))
				{
					$tmp = $form[$k];
					unset($form[$k]);
					$form[$k][0] = $tmp;
				}

				foreach($form[$k] AS $ks=>$rows)
				{
					$t = $this->form($rows,$row,TRUE);

					$new[$k][] = $t;

					if(!empty($t['error']))
					{
						if($is_return === TRUE)
						{
							return $t;
						}
						else
						{
							$this->show_message((empty($row['name'])?$k:$row['name']).'-'.$t['error']['error_msg']);
						}
					}
				}
			}
		}

		return $new;
	}
	
	/**
	 * 消息输出
	 *
	 * 详细说明
	 * @形参
	 * @访问      公有
	 * @返回值    void
	 * @throws
	 * helius
	 */
	function show_message($msg)
	{
		//此处可自行更换输出形式
		die($msg);
	}
}

$val = new SPFormVerification();

$result = $val->form($_POST, array(
	'type'          =>	array(
		'reg'		    =>'/^[0-1]{1}$/',
		'is_empty'	    =>true,
		'name'          =>'类别'
	),
	'order'          =>	array(
		'reg'		    =>'/^[0-9]{1,}$/',
		'is_empty'	    =>false,
		'name'          =>'排序'
	),
	'url' =>	array(
		'reg'		    =>'/^[^\n]{0,}$/',
		'is_empty'	    =>true,
		'name'          =>'分类icon'
	),
	'mode' =>	array(
		array(
			'reg'		    =>'/^[0-2]{0,}$/',
			'is_empty'	    =>true,
			'name'          =>'所属身份'
		)
	),
));

?>
