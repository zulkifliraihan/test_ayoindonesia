<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Matchs;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class MatchsController extends Controller
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
        $matchs = Matchs::with('hometeam', 'guestteam');

        if (request()->ajax()) {
            return DataTables()->eloquent($matchs)
            ->addColumn('DT_RowIndex', function ($number){
                $i = 1;
                return $i++;
            })
            ->addColumn('tuanrumah', function ($query){
                return $query->hometeam->nama;
            })
            ->addColumn('tamurumah', function ($query){
                return $query->guestteam->nama;
            })
            ->addColumn('tanggaltanding', function ($query){
                return Carbon::parse($query->date_match)->format('d m Y');
            })
            ->addColumn('waktutanding', function ($query){
                return Carbon::parse($query->time_match)->format('H:i');
            })
            ->addColumn('report', function ($query){
                if ($query->total_skor == null) {
                    $button = '
                    <a href="'. route('admin.matchs.report.create', $query->id) .'">
                        <button type="button" class="btn btn-outline-primary btn-sm waves-effect">
                            Create Report
                        </button>
                    </a>
                    ';
                }
                else {
                    $button = '
                    <a href="'. route('admin.matchs.report.index', $query->id) .'">
                        <button type="button" class="btn btn-outline-primary btn-sm waves-effect">
                            Show Report
                        </button>
                    </a>
                    ';
                }
                return $button;
            })
            ->addColumn('action', function ($action){

                $new_button_all = '
                <div class="dropdown dropup custom-dropdown">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink-1">
                        <a class="dropdown-item edit-item" href="'. route('admin.matchs.edit', $action->id) .'" data-mid="'. $action->id .'">
                            Edit
                        </a>
                        <a class="dropdown-item delete-item" href="javascript:void(0);" data-mid="'. $action->id .'">
                            Hapus
                        </a>
                    </div>
                </div>';
                return  $new_button_all;
            })
            ->rawColumns(['DT_RowIndex', 'tuanrumah', 'tamurumah', 'tanggaltanding', 'waktutanding', 'report', 'action'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('dashboard.components.match.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teams = Team::all();

        $data = [
            'teams' => $teams
        ];

        return view('dashboard.components.match.create', $data);
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
            'home_team' => 'required',
            'guest_team' => 'required',
            'date_match' => 'required',
            'time_match' => 'required',
        ];

        $messages = [
            'home_team.required' => 'Team Tuan Rumah wajib di isi !',
            'guest_team.required' => 'Team Tamu wajib di isi !',
            'date_match.required' => 'Tanggal Pertandingan wajib di isi !',
            'time_match.required' => 'Waktu Pertandingan wajib di isi !',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->errorvalidator($validator->errors()->first());
        }
        // End : Validation

        $data = $request->all();
        $data['id'] = $this->uuid;
        $match = Matchs::create($data);

        $function = "created";
        $route = route('admin.matchs.index');
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
        $match = Matchs::find($id);
        $teams = Team::all();

        $data = [
            'teams' => $teams,
            'match' => $match
        ];

        return view('dashboard.components.match.edit', $data);
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
            'home_team' => 'required',
            'guest_team' => 'required',
            'date_match' => 'required',
            'time_match' => 'required',
        ];

        $messages = [
            'home_team.required' => 'Team Tuan Rumah wajib di isi !',
            'guest_team.required' => 'Team Tamu wajib di isi !',
            'date_match.required' => 'Tanggal Pertandingan wajib di isi !',
            'time_match.required' => 'Waktu Pertandingan wajib di isi !',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->errorvalidator($validator->errors()->first());
        }
        // End : Validation

        $data = $request->all();
        $match = Matchs::find($id);
        $match->update($data);

        $function = "updated";
        $route = route('admin.matchs.index');
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
        $match = Matchs::find($id)->delete();

        $function = "deleted";

        return $this->success($function);

    }
}
