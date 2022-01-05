<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\Create;
use App\Helpers\Delete;
use App\Helpers\Retrunjson;
use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class TeamsController extends Controller
{
    protected $uuid;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::all();
        $provinces = Province::all();

        if (request()->ajax()) {
            return DataTables()->of($teams)
            ->addColumn('DT_RowIndex', function ($number){
                $i = 1;
                return $i++;
            })
            ->addColumn('alamat_lengkap', function ($query){
                $data = '
                <p>
                    Provinsi : '. $query->provinsi->name .' <br/>
                    Kota : '. $query->kota->name .' <br/>
                    Alamat : '. $query->alamat .'
                </p>
                ';
                return $data;
            })
            ->addColumn('foto', function($query) {
                return '<img style="width: 75%; height: 70%;" src="'. Storage::url($query->file->path) .'" alt="'. $query->foto .'">';
            })
            ->addColumn('action', function ($action){

                $new_button_all = '
                <div class="dropdown dropup custom-dropdown">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink-1">
                        <a class="dropdown-item edit-item" href="'. route('admin.teams.edit', $action->id) .'" id="'. $action->id .'">
                            Edit
                        </a>
                        <a class="dropdown-item delete-item" href="javascript:void(0);" data-tid="'. $action->id .'">
                            Hapus
                        </a>
                    </div>
                </div>';
                return  $new_button_all;
            })
            ->rawColumns(['DT_RowIndex', 'alamat_lengkap', 'foto', 'action'])
            ->addIndexColumn()
            ->make(true);
        }

        $data = [
            'teams' => $teams,
            'provinces' => $provinces
        ];

        return view('dashboard.components.team.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provinces = Province::all();

        $data = [
            'provinces' => $provinces
        ];

        return view('dashboard.components.team.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Start : Validation
        $rules = [
            'nama' => 'required',
            'tahun' => 'required',
            'provinsi_id' => 'required',
            'kota_id' => 'required',
            'alamat' => 'required',
            'foto' => 'required|mimes:jpg,png|max: 2000',
        ];

        $messages = [
            'nama.required' => 'Nama Team wajib di isi !',
            'tahun.required' => 'Tahun Berdiri wajib di isi !',
            'provinsi_id.required' => 'Provinsi wajib di isi !',
            'kota_id.required' => 'Kota wajib di isi !',
            'alamat.required' => 'Alamat wajib di isi !',
            'foto.required' => 'Foto wajib di isi!',
            'foto.mimes' => 'Foto hanya bisa format JPG dan PNG!',
            'foto.max' => 'Foto maksimal 2MB!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail-validator',
                'message' => $validator->errors()->first()
            ], 400);
        }
        // End : Validation

        $data = $request->all();
        $user = Auth::user();
        $data['id'] = $this->uuid;

        // Start : Upload Image
        $requestFile = $request->file('foto');
        $folder = 'teams';
        $path = $folder . '/' . $user->id ;
        $fileName = $request->foto->getClientOriginalName();

        // This code below for upload file to local storage with Helper function
        $file = Create::fileUpload($requestFile, $fileName, $path, $folder);
        // End : Upload Image

        $data['file_id'] = $file->id;

        $team = Team::create($data);

        $function = "created";
        $route = route('admin.teams.index');

        return $this->successroute($function, $route);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $team = Team::find($id);
        $provinces = Province::all();

        $data = [
            'team' => $team,
            'provinces' => $provinces
        ];
        return view('dashboard.components.team.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Start : Validation
        $rules = [
            'nama' => 'required',
            'tahun' => 'required',
            'provinsi_id' => 'required',
            'kota_id' => 'required',
            'alamat' => 'required',
            'foto' => 'mimes:jpg,png|max: 2000',
        ];

        $messages = [
            'nama.required' => 'Nama Team wajib di isi !',
            'tahun.required' => 'Tahun Berdiri wajib di isi !',
            'provinsi_id.required' => 'Provinsi wajib di isi !',
            'kota_id.required' => 'Kota wajib di isi !',
            'alamat.required' => 'Alamat wajib di isi !',
            'foto.mimes' => 'Foto hanya bisa format JPG dan PNG!',
            'foto.max' => 'Foto maksimal 2MB!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail-validator',
                'message' => $validator->errors()->first()
            ], 400);
        }
        // End : Validation

        $data = $request->all();
        $user = Auth::user();

        $team = Team::find($id);

        if (isset($data['foto'])) {
            $deleteFile = Delete::storageFile($team);

            // Start : Upload Image
            $requestFile = $request->file('foto');
            $folder = 'teams';
            $path = $folder . '/' . $user->id ;
            $fileName = $request->foto->getClientOriginalName();

            // This code below for upload file to local storage with Helper function
            $file = Create::fileUpload($requestFile, $fileName, $path, $folder);
            // End : Upload Image

            $data['file_id'] = $file->id;
        }

        $team->update($data);

        $function = "updated";
        $route = route('admin.teams.index');

        return $this->successroute($function, $route);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $team = Team::find($id);

        $deleteFile = Delete::storageFile($team);

        $team->delete();

        $function = "deleted";

        return $this->success($function);
    }
}
