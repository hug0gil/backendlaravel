<?php

namespace App\Business\Services;

use App\Business\Entities\ConceptEntity;
use App\Business\Entities\SaleEntity;
use App\Http\Requests\SaleRequest;
use App\Models\Concept;
use App\Models\Product;
use App\Models\Sale;

class CreateSaleService
{

    public function create(SaleRequest $request)
    {
        $conceptsEntity = [];
        foreach ($request->concepts as $concept) {
            $conceptsEntity[] = new ConceptEntity($concept['quantity'], Product::find($concept['product_id'])->price, $concept['product_id']);
        }

        $saleEntity = new SaleEntity(null, $request->email, $request->sale_date, $conceptsEntity);

        $sale = Sale::create([
            'email' => $request->email,
            'sale_date' => $request->sale_date,
            'total' => $saleEntity->total
        ]);

        foreach ($conceptsEntity as $conceptEntity) {
            $concept = Concept::create([
                'quantity' => $conceptEntity->quantity,
                'price' => $conceptEntity->price,
                'product_id' => $conceptEntity->product_id,
                'sale_id' => $sale->id
            ]);
            $concept->save();
        }

        $saleEntity->id = $sale->id;
        return $saleEntity;
    }
}
