<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Inventory;
use App\Models\Log as InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
public function index(Request $request)
    {
        $query = Inventory::query();

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('categories', 'LIKE', "%{$searchTerm}%")
                ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        $inventories = $query->get();

        return response()->json($inventories, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'categories'  => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity'    => 'required|integer|min:0',
            'price'       => 'required|numeric|min:0',
            'picture'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        $filenameString = 'default.jpg';

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            

            $filenameString = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            

            $file->storeAs('items', $filenameString, 'public');
        }

        $validated['picture'] = $filenameString;

        $inventory = Inventory::create($validated);

        InventoryLog::create([
            'name'         => $inventory->name,
            'categories'   => $inventory->categories,
            'action'       => 'create',
            'description'  => "Item created with quantity {$inventory->quantity} and price {$inventory->price}",
            'quantity'     => $inventory->quantity,
            'price'        => $inventory->price,
            'picture'      => $inventory->picture, 
            'inventory_id' => $inventory->id,
            'changed_by'   => $request->user()->id, 
        ]);

        return response()->json($inventory, 201);
    }

    public function show(Inventory $inventory)
    {
        return response()->json($inventory);
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'categories'  => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity'    => 'required|integer|min:0',
            'price'       => 'required|numeric|min:0',
            'picture'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $filenameString = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('items', $filenameString, 'public');
            
            $validated['picture'] = $filenameString;
        } else {
            unset($validated['picture']);
        }


        $inventory->update($validated);


        InventoryLog::create([
            'name' => $inventory->name,
            'categories' => $inventory->categories,
            'action' => 'update',
            'description' => !empty($changes) ? implode(', ', $changes) : 'No changes detected',
            'quantity' => $inventory->quantity,
            'price' => $inventory->price,
            'picture' => $inventory->picture,
            'inventory_id' => $inventory->id,
            'changed_by' => $request->user()->id,
        ]);

        return response()->json($inventory, 200);
    }

    public function destroy(Request $request, Inventory $inventory)
    {
        InventoryLog::create([
            'name' => $inventory->name,
            'categories' => $inventory->categories,
            'action' => 'delete',
            'description' => 'Item deleted',
            'quantity' => $inventory->quantity,
            'price' => $inventory->price,
            'picture' => $inventory->picture,
            'inventory_id' => $inventory->id,
            'changed_by' => $request->user()->id,
        ]);

        $inventory->delete();

        return response()->json(['message' => 'Inventory item deleted']);
    }

    public function dashboardStats()
    {
        $categoryData = DB::table('inventories')
        ->join('logs', 'inventories.id', '=', 'logs.inventory_id') 
        ->select('logs.categories as category', DB::raw('count(inventories.id) as total'))
        ->whereNotNull('logs.categories')
        ->groupBy('logs.categories')
        ->get();

    $staffCount = User::count();
    $logCount = InventoryLog::count();

    return response()->json([
        'categories'  => $categoryData,
        'staff_count' => $staffCount,
        'log_count'   => $logCount
    ], 200);
    }
}