<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PackageController extends Controller
{
    public function index()
    {
        return view('packages');
    }

    public function getPackages(Request $request)
    {
        if ($request->ajax()) {
            $data = Package::select('packages.*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('price', function ($row) {
                    return '$' . number_format($row->price, 2);
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm editPackage"><i class="fa-solid fa-pen"></i></a> ';
                    $btn = $btn.' <a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-danger btn-sm deletePackage"><i class="fa-solid fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $package = Package::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'created package',
            'subject_type' => Package::class,
            'subject_id' => $package->id,
            'description' => 'Created package ' . $package->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Package created successfully'
        ]);
    }

    public function edit($id)
    {
        $package = Package::find($id);
        if (!$package) {
            return response()->json(['success' => false, 'message' => 'Package not found'], 404);
        }
        return response()->json($package);
    }

    public function update(Request $request, $id)
    {
        $package = Package::find($id);
        if (!$package) {
            return response()->json(['success' => false, 'message' => 'Package not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $package->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'updated package',
            'subject_type' => Package::class,
            'subject_id' => $package->id,
            'description' => 'Updated package ' . $package->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Package updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $package = Package::find($id);
        if (!$package) {
            return response()->json(['success' => false, 'message' => 'Package not found'], 404);
        }

        $packageName = $package->name;
        $package->delete();
        
        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'deleted package',
            'subject_type' => Package::class,
            'subject_id' => $id,
            'description' => 'Deleted package ' . $packageName
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Package deleted successfully'
        ]);
    }
}
