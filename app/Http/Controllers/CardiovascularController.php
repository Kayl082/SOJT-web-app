<?php

namespace App\Http\Controllers;

use App\Services\CDCDataService;
use Illuminate\Http\Request;

class CardiovascularController extends Controller
{
    protected $cdcService;

    public function __construct(CDCDataService $cdcService)
    {
        $this->cdcService = $cdcService;
    }

    public function index(Request $request)
    {
        $limit = $request->get('limit', 100);
        $offset = $request->get('offset', 0);

        $data = $this->cdcService->getCardiovascularData($limit, $offset);

        return response()->json($data);
    }

    public function byState($state)
    {
        return response()->json(
            $this->cdcService->filterByState($state)
        );
    }

    public function byYear($year)
    {
        return response()->json(
            $this->cdcService->filterByYear($year)
        );
    }
}