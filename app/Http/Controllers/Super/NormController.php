<?php namespace App\Http\Controllers\Super;

use App\Norm;
use App\Flash;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class NormController extends Controller {

	public function firstNormShow()
	{
		// 防止session过期
		if (!\Auth::user()) 
		{
			\Cookie::queue('super', null , -1);
			return redirect('/');
		}

		$superId = \Auth::user()->id;
		// 得到所有FLASH面板
		$flashes = Flash::get();
		
		// 构造一级规范
		$zeroLevel = array();
		for ($i=0; $i<count($flashes); $i++)
		{
			$normsArr = Norm::where('flashId', '=', $flashes[$i]['id'])->get();
			$normsArr2 = array();
			// 获得与flashId相同的规范
			for ($j=0; $j<count($normsArr); $j++)
			{
				array_push($normsArr2, $normsArr[$j]['zeroLevel']);
			}
				
			$normsArr2 = implode(',', $normsArr2);
			
			array_push($zeroLevel, $normsArr2);
		}
		// 防止一级规范为空
		for ($i=0; $i<count($zeroLevel); $i++)
		{
			if (count($zeroLevel[$i]) == 0)
			{
				$zeroLevel[$i] = '';
			}
		}
		// dd($zeroLevel);
		return view('super.norm.first', compact('flashes', 'zeroLevel'));
	}

	public function secondNormShow()
	{
		// 防止session过期
		if (!\Auth::user()) 
		{
			\Cookie::queue('super', null , -1);
			return redirect('/');
		}

		$superId = \Auth::user()->id;

		$norms = Norm::where('superId', '=', $superId)->get();
		
		return view('super.norm.second', compact('norms'));
	}

	public function thirdNormShow()
	{
		// 防止session过期
		if (!\Auth::user()) 
		{
			\Cookie::queue('super', null , -1);
			return redirect('/');
		}

		$superId = \Auth::user()->id;
		$types = Norm::where('superId', '=', $superId)->get();
		
		return view('super.norm.third', compact('types'));
	}

	/**
	 * 修改一级规范，一级规范的逻辑很重要，二级以下需要依靠一级！！
	 */
	public function postFirstNorm(Request $request)
	{	
		$superId = \Auth::user()->id;
		$tabVal = $request->get('tab_val');
		// 零级规范间以空格分割
		$tabArr = explode(' ', $tabVal);
		
		// 存储所有现有的标注规范
		$norms = Norm::where('superId', '=', $superId)
				->get();
		
		// 删除超级管理员所有的标注规范
		Norm::where('superId', '=', $superId)
				->delete();		
		// dd($norms);

		// 对于每个面板对应的一级标注规范
		for ($i=0; $i<count($tabArr); $i++) 
		{
			// 形如文本标注XXXXX1
			$tempArr = explode('XXXXX', $tabArr[$i]);
			// 形如预处理，预处理2
			$tempArrArr = explode(',', $tempArr[0]);
			
			// 如果规范为空，规范表里直接插入一条数据
			if (count($norms) == 0) 
			{
				for ($k=0; $k<count($tempArrArr); $k++)
				{
					Norm::create(
		    			array_merge(
		        			['flashId'     => $tempArr[1]],
		        			['superId'     => $superId],
		        			['zeroLevel'   => $tempArrArr[$k]],
		        			['firstLevel'  => ''], 
		        			['secondLevel' => ''] 
		    			));	
				}
			}
			// 如果不为空，
			// 先在已有的规范里找到符合条件的规范，原样插入，
			// 然后单独插入
			else 
			{
				for ($k=0; $k<count($tempArrArr); $k++)
				{
					$equal = 0;
					for ($j=0; $j<count($norms); $j++) 
					{
						if ($tempArr[1]==$norms[$j]['flashId'] 
							&& $tempArrArr[$k]==$norms[$j]['zeroLevel'])
						{
							$equal = 1;
							Norm::create(
			        			array_merge(
			            			['flashId'     => $tempArr[1]],
			            			['superId'     => $superId],
			            			['zeroLevel'   => $norms[$j]['zeroLevel']],
			            			['firstLevel'  => $norms[$j]['firstLevel']], 
			            			['secondLevel' => $norms[$j]['secondLevel']]
			        			));
						}
					}
					// 如果在之前的规范中没有符合条件的，单独插入
					if ($equal == 0)
					{
						Norm::create(
	        			array_merge(
	            			['flashId'     => $tempArr[1]],
	            			['superId'     => $superId],
	            			['zeroLevel'   => $tempArrArr[$k]],
	            			['firstLevel'  => ''], 
        					['secondLevel' => '']	
	        			));	
					}
				}
			}
		}
	}

	/**
	 * 修改二级规范，同时修改具体名称的顺序
	 */
	public function postSecondNorm(Request $request)
	{	
		$superId = \Auth::user()->id;
		$tabVal = $request->get('tab_val');
		// 零级规范间以空格分割
		$tabArr = explode(' ', $tabVal);
		// 找到需要更改的规范进行更新
		$norms = Norm::where('superId', '=', $superId)
				->get();

		// 删除超级管理员所有的标注规范
		Norm::where('superId', '=', $superId)
				->delete();		
		// dd($tabArr);

		// 对于每个面板对应的一级标注规范
		for ($i=0; $i<count($tabArr); $i++) 
		{
			// 形如文本标注XXXXX1
			$tempArr = explode('XXXXX', $tabArr[$i]);
			
			// 如果规范为空，规范表里直接插入一条数据
			if (count($norms) == 0) 
			{
				Norm::create(
	    			array_merge(
	        			['flashId'     => $tempArr[1]],
	        			['superId'     => $superId],
	        			['zeroLevel'   => $tempArr[2]],
	        			['firstLevel'  => $tempArr[0]], 
	        			['secondLevel' => ''] 
	    			));	
			}
			// 如果不为空，
			// 先在已有的规范里找到符合条件的规范，原样插入，
			// 然后单独插入
			else 
			{
				$equal = 0;
				for ($j=0; $j<count($norms); $j++) 
				{
					if ($tempArr[1]==$norms[$j]['flashId'] 
						&& $tempArr[2]==$norms[$j]['zeroLevel']
						&& $tempArr[0]==$norms[$j]['firstLevel'])
					{
						$equal = 1;
						Norm::create(
		        			array_merge(
		            			['flashId'     => $tempArr[1]],
		            			['superId'     => $superId],
		            			['zeroLevel'   => $norms[$j]['zeroLevel']],
		            			['firstLevel'  => $norms[$j]['firstLevel']], 
		            			['secondLevel' => $norms[$j]['secondLevel']]
		        			));
					}
				}
				// 如果在之前的规范中没有符合条件的，单独插入
				if ($equal == 0)
				{
					Norm::create(
        			array_merge(
            			['flashId'     => $tempArr[1]],
            			['superId'     => $superId],
            			['zeroLevel'   => $tempArr[2]],
            			['firstLevel'  => $tempArr[0]], 
    					['secondLevel' => '']	
        			));	
				}
			}
		}
	}

	/**
	 * 修改具体名称
	 */
	public function postThirdNorm(Request $request)
	{
		$superId = \Auth::user()->id;
		$tabVal = $request->get('tab_val');
		// 零级规范间以“。”分割
		$tabArr = explode('。', $tabVal);

		$types = Norm::where('superId', '=', $superId)
				->get();

		// dd($tabVal);

		for ($i=0; $i<count($tabArr); $i++) {
			$types[$i]->secondLevel = $tabArr[$i];
			$types[$i]->save();
		}

		$types = Norm::where('superId', '=', $superId)
				->get();

		$fileName = "flash/assets/xml/label_types_" . $superId . ".xml";
	    $handle = fopen($fileName, "w");
	    $str = '<?xml version="1.0" encoding="utf-8"?><Types>';
	    $countState=1;
	    for ($i=0; $i<count($types); $i++) {
	        // 声明为数组
	        $array = array();
	        $Tags1 = array();
	        $Tags2 = array();
	        $Tags1 = explode(",", $types[$i]['firstLevel']);
	        $Tags2 = explode(",", $types[$i]['secondLevel']);

	        // dd($Tags2);
	        // 分组数组中第一个元素应为-1
	        array_push($array, -1);
	        // 得到二级标签中‘.’的下标，作为后来分组的依据
	        for($j=0; $j<count($Tags2); $j++)
	        {
	            if($Tags2[$j] == '.')
	            {
	                array_push($array, $j);
	            }
	        }
	        
	        $str .= '<Layer1 layerID="'.$countState.'" label="'.$types[$i]['zeroLevel'].'" classid="'.Norm::find($types[$i]['id'])->belongsToFlash['classId'].'" gf="'.Norm::find($types[$i]['id'])->belongsToFlash['hasNorm'].'">';
	        for($j=0; $j<count($Tags1); $j++)
	        {
	            $str .= '<Layer2 layerID="'.($countState + $j + 1).'" label="'.$Tags1[$j].'">';
	            // dd($Tags2);
	            // 在分组数组中找到第i个元素，直到第i+1个元素之间的$Tags2元素即为当前一级名称下的二级名称
	            for($k=$array[$j] + 1; $k<$array[$j + 1]; $k++)
	            {
	                // 获得三层以下的结构
	                $tag3 = $Tags2[$k];
	                $str .= BuildLayer($tag3);
	            }      
	            $str .= '</Layer2>';
	        }
	        $str .= '</Layer1>';
	        $countState = $countState + count($Tags1) + 1;
	    }

	    $str .= '</Types>';
	    fwrite($handle, $str);
	    fclose($handle);

		return redirect()->back();
	}	

}
