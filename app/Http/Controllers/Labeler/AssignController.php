<?php namespace App\Http\Controllers\Labeler;

use App\Assign;
use App\Norm;
use App\Flash;
use App\Labeler;
use App\User;
use App\Proj;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AssignController extends Controller {

	public function index()
	{	
		// 防止session过期
		if (!\Auth::user()) 
		{
			\Cookie::queue('labeler', null , -1);
			return redirect('/');
		}

		$assigns = Assign::where('labeler', '=', \Auth::user()->labelerName)
					->where('claim', '!=', 1)
					->where('claim', '!=', 8)
					->get();
		
		$userId = Labeler::find(\Auth::user()->id)->belongsToUser['id'];

		// 通过查询User中的userId得到superId，再查询Norm中的superId得到使用到的规范
		$superId = Proj::find($userId)->belongsToSuper['id'];
		
		\Cookie::queue('labeler', \Auth::user()->labelerName, 120);

		return view('labeler.assign.index', compact('assigns'));
	}

	public function getLabel($assignId) 
	{
		// 防止session过期
		if (!\Auth::user()) 
		{
			\Cookie::queue('labeler', null , -1);
			return redirect('/');
		}
		$assign = Assign::where('id', '=', $assignId)->first();

		$assign['labelerId'] = Labeler::where('labelerName', '=', $assign['labeler'])->first()['id'];

		$projId = User::find($assign['userId'])->belongsToProj['id'];
		$superId = Proj::find($projId)->belongsToSuper['id'];
		$norms = Norm::where('superId', '=', $superId)->get();
	
		$assign['flashPath'] = Flash::where('id', '=', Norm::where('zeroLevel', '=', $assign['state'])->first()['flashId'])->first()['flashPath'];
		
		// dd($assign);
		return view('labeler.assign.label', compact('assign'));
	}

	public function getCheck($assignId) 
	{
		// 防止session过期
		if (!\Auth::user()) 
		{
			\Cookie::queue('labeler', null , -1);
			return redirect('/');
		}
		$assign = Assign::where('id', '=', $assignId)->first();
		$assign['labelerId'] = Labeler::where('labelerName', '=', $assign['labeler'])->first()['id'];

		$projId = User::find($assign['userId'])->belongsToProj['id'];
		$superId = Proj::find($projId)->belongsToSuper['id'];
		$norms = Norm::where('superId', '=', $superId)->get();
		
		$assign['flashPath'] = Flash::where('id', '=', Norm::where('zeroLevel', '=', $assign['state'])->first()['flashId'])->first()['flashPathBS'];
		
		return view('labeler.assign.check', compact('assign'));
	}

	public function getAssistFlash($style, $yuliaoID)
	{
		$assign = Assign::where('id', '=', $yuliaoID)->first();

		if ($style == 1) {
			// 返回改动后的xml
			echo $assign['xml'];

		} else if ($style == 2) {
			// 返回初始xml
			echo $assign['initXml'];

		}  else if ($style == 7) {
			
			Assign::where('id', '=', $yuliaoID)->update(
				array_merge(
				    ['finishTime' => date("Y-m-d H:i:s")],
				    ['claim'      => 7]
				));
		}
	}

	public function postAssistFlash($style, $yuliaoID, Request $request)
	{
		$assign = Assign::where('id', '=', $yuliaoID)->first();

		$postData = $request->getContent();
		
		if ($style == 3) { // 只保存，不提交
		
			Assign::where('id', '=', $yuliaoID)->update(
				array_merge(
				    ['xml'    => $postData]
				));

		} else if ($style == 4) { // 提交
			
			Assign::where('id', '=', $yuliaoID)->update(
				array_merge(
				    ['xml'        => $postData],
				    ['finishTime' => date("Y-m-d H:i:s")],
				    ['claim'      => 7]
				));			

		} else if ($style == 5) { // 上传图片

			// dd($request);
			$fileName = preg_replace("/\+/", "\/", $yuliaoID);
			$file = fopen($fileName, "w");
			fwrite($file, $postData);
			fclose($file);

		} else if ($style == 6) {

			Assign::where('id', '=', $yuliaoID)->update(
				array_merge(
				    ['initXml'    => $postData]
				));

		}
	}
}
