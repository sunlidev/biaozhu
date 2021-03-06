<?php namespace App\Http\Controllers\Super;

use App\Flash;

use App\Http\Requests;
use App\Http\Requests\FlashRegisterRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class FlashController extends Controller {

	public function index()
	{
		// 防止session过期
		if (!\Auth::user()) 
		{	
			\Cookie::queue('super', null , -1);
			return redirect('/');
		}

		$flashes = Flash::get();

		return view('super.flash', compact('flashes'));	
	}

	public function postFlash(FlashRegisterRequest $request)
	{
		$flashName = $request->get('flashName');
		$classId = $request->get('classId');
		$hasNorm = $request->get('hasNorm');
		$hasBS = $request->get('hasBS');
		$flashPath = $request->get('flashPath');
		$flashPathBS = $request->get('flashPathBS');

		$flash = Flash::create(
			array_merge(
                ['flashName'   => $flashName],
                ['classId'     => $classId],
                ['hasNorm'     => $hasNorm],
                ['hasBS'       => $hasBS],
                ['flashPath'   => $flashPath],
                ['flashPathBS' => $flashPathBS]
            ));

		return redirect()->back();
	}
}
