<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class PurchaseController extends Controller
{
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('purchase', compact('item'));
    }
}
