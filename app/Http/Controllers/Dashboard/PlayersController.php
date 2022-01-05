<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class PlayersController extends Controller
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
        $players = Player::all();

        if (request()->ajax()) {
            return DataTables()->of($players)
            ->addColumn('DT_RowIndex', function ($number){
                $i = 1;
                return $i++;
            })
            ->addColumn('tinggi', function ($query){
                return $query->tinggi_badan . "CM";
            })
            ->addColumn('berat', function ($query){
                return $query->berat_badan . "KG";
            })
            ->addColumn('action', function ($action){

                $new_button_all = '
                <div class="dropdown dropup custom-dropdown">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink-1">
                        <a class="dropdown-item edit-item" href="javascript:void(0)" data-pid="'. $action->id .'">
                            Edit
                        </a>
                        <a class="dropdown-item delete-item" href="javascript:void(0);" data-pid="'. $action->id .'">
                            Hapus
                        </a>
                    </div>
                </div>';
                return  $new_button_all;
            })
            ->rawColumns(['DT_RowIndex', 'tinggi', 'berat', 'action'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('dashboard.components.player.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'nomor_punggung' => 'required|unique:players,nomor_punggung',
            'tinggi_badan' => 'required',
            'berat_badan' => 'required',
            'posisi' => 'required',
        ];

        $messages = [
            'nama.required' => 'Nama Pemain wajib di isi !',
            'nomor_punggung.required' => 'Nomor Punggung wajib di isi !',
            'nomor_punggung.unique' => 'Nomor Punggung sudah ada !',
            'tinggi_badan.required' => 'Provinsi wajib di isi !',
            'berat_badan.required' => 'Kota wajib di isi !',
            'posisi.required' => 'Alamat wajib di isi !',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->errorvalidator($validator->errors()->first());
        }
        // End : Validation

        $data = $request->all();
        $data['id'] = $this->uuid;
        $player = Player::create($data);

        $function = "created";

        return $this->success($function);

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
        $player = Player::find($id);

        return $player;
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
            'nomor_punggung' => 'required',
            'tinggi_badan' => 'required',
            'berat_badan' => 'required',
            'posisi' => 'required',
        ];

        $messages = [
            'nama.required' => 'Nama Pemain wajib di isi !',
            'nomor_punggung.required' => 'Nomor Punggung wajib di isi !',
            'tinggi_badan.required' => 'Provinsi wajib di isi !',
            'berat_badan.required' => 'Kota wajib di isi !',
            'posisi.required' => 'Alamat wajib di isi !',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->errorvalidator($validator->errors()->first());
        }
        // End : Validation

        $data = $request->all();
        $player = Player::find($id);
        $player->update($data);

        $function = "updated";

        return $this->success($function);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $player = Player::find($id)->delete();


        $function = "deleted";

        return $this->success($function);
    }
}
