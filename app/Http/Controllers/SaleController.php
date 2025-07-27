<?php

namespace App\Http\Controllers;

use App\Business\Entities\ConceptEntity;
use App\Business\Entities\SaleEntity;
use App\Business\Services\CreateSaleService;
use App\Http\Requests\SaleRequest;
use App\Models\Concept;
use App\Models\Product;
use App\Models\Sale;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends Controller
{
    public function __construct(protected CreateSaleService $createSaleService) {}
    public function get()
    {
        return response()->json(Sale::all(), Response::HTTP_OK);
    }

    public function create(SaleRequest $request)
    {
        try {
            $saleEntity = $this->createSaleService->create($request);
            return response()->json($saleEntity, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
