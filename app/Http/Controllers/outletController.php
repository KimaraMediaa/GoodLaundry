<?php

namespace App\Http\Controllers;

use App\Models\area;
use App\Models\outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class outletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('outlets as o')
                ->join('areas as a', 'a.idArea', '=', 'o.idArea')
                ->where('o.vOutlet', 1)
                ->orderBy('o.namaOutlet')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn =
                        '
                            <a class="nav-link" href="#navbar-third" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M9 6l11 0"></path>
                                <path d="M9 12l11 0"></path>
                                <path d="M9 18l11 0"></path>
                                <path d="M5 6l0 .01"></path>
                                <path d="M5 12l0 .01"></path>
                                <path d="M5 18l0 .01"></path>
                            </svg>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item btnEditOutlet" href="#" data-id="' . $row->idOutlet . '">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-color-picker" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M11 7l6 6"></path>
                                    <path d="M4 16l11.7 -11.7a1 1 0 0 1 1.4 0l2.6 2.6a1 1 0 0 1 0 1.4l-11.7 11.7h-4v-4z"></path>
                                </svg>
                                    Edit
                                </a>
                            </div>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $area = area::where('vArea', 1)->orderBy('namaArea')->get();
        return view('admins.outlet', compact('area'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = array(
            'formOutletNama' => 'required',
            'formOutletArea' => 'required',
            'formOutletKontak' => 'required',
            'formOutletAlamat' => 'required',
            'formOutletLat' => 'required',
        );

        $pesan = [
            'formOutletNama.required' => 'Nama Outlet harus diisi!',
            'formOutletArea.required' => 'Area Outlet harus diisi!',
            'formOutletKontak.required' => 'Kontak Outlet harus diisi!',
            'formOutletAlamat.required' => 'Alamat Outlet harus diisi!',
            'formOutletLat.required' => 'Silahkan klik posisi Outlet pada peta!',
        ];

        $error = Validator::make($request->all(), $rules, $pesan);
        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        outlet::updateOrCreate(
            [
                'idOutlet' => $request->formOutletId,
            ],
            [
                'namaOutlet' => $request->formOutletNama,
                'kontakOutlet' => $request->formOutletKontak,
                'alamatOutlet' => $request->formOutletAlamat,
                'idArea' => $request->formOutletArea,
                'latOutlet' => $request->formOutletLat,
                'longOutlet' => $request->formOutletLong,
            ]
        );

        return response()->json(['success' => 'Berhasil']);
    }

    /**
     * Display the specified resource.
     */
    public function show(outlet $outlet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = outlet::find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, outlet $outlet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(outlet $outlet)
    {
        //
    }
}
