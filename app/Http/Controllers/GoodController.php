<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessFileRequest;
use App\Models\Good;
use App\Services\GoodService;
use Illuminate\Http\Request;

class GoodController extends Controller
{
    private GoodService $goodService;

    public function __construct(GoodService $goodService)
    {
        $this->goodService = $goodService;
    }
    public function index()
    {
        $goods = Good::paginate(9);

        return view('goods', compact('goods'));
    }

    public function show(Good $good)
    {
        return view('good', compact('good'));
    }

    public function createForm()
    {
        return view('add-goods');
    }

    public function create(ProcessFileRequest $request)
    {
        $this->goodService->createFromFile($request->validated());

        return redirect()->route('goods');
    }
}
