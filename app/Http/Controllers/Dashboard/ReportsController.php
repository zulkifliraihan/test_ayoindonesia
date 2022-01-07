<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Matchs;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class ReportsController extends Controller
{
    protected $uuid;
    protected $match;
    protected $players;
    protected $report;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->uuid = Uuid::uuid4()->toString();
        $this->match = Matchs::find($request->route('matchId'));

        $playerHomeTeam = $this->match->hometeam->player;
        $playerGuestTeam = $this->match->guestteam->player;

        $players = [];

        foreach ($playerHomeTeam as $value) {
            $item = [];
            $item['id'] = $value->id;
            $item['nama'] = $value->nama;
            array_push($players, $item);
        }

        foreach ($playerGuestTeam as $value) {
            $item = [];
            $item['id'] = $value->id;
            $item['nama'] = $value->nama;
            array_push($players, $item);
        }

        $this->players = $players;
        $this->report = Report::where('match_id', $this->match->id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (request()->ajax()) {
            return DataTables()->eloquent($this->report)
            ->addColumn('DT_RowIndex', function ($number){
                $i = 1;
                return $i++;
            })
            ->addColumn('nama_pemain', function ($query){
                return $query->player->nama;
            })
            ->addColumn('nomor_punggung_pemain', function ($query){
                return $query->player->nomor_punggung;
            })
            ->addColumn('waktu_goal', function ($query){
                return Carbon::parse($query->time_goal)->format('H:i');
            })
            ->addColumn('action', function ($action){

                $new_button_all = '
                <div class="dropdown dropup custom-dropdown">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink-1">
                        <a class="dropdown-item edit-item" href="'. route('admin.matchs.edit', $action->id) .'" data-pid="'. $action->id .'">
                            Edit
                        </a>
                        <a class="dropdown-item delete-item" href="javascript:void(0);" data-pid="'. $action->id .'">
                            Hapus
                        </a>
                    </div>
                </div>';
                return  $new_button_all;
            })
            ->rawColumns(['DT_RowIndex', 'nama_pemain', 'nomor_punggung_pemain', 'waktu_goal', 'action'])
            ->addIndexColumn()
            ->make(true);
        }

        $arrWin = explode(":", $this->match->total_skor);

        if ($arrWin[0] > $arrWin[1]) {
            $teamWin = $this->match->hometeam->nama;
        }
        elseif ($arrWin[0] < $arrWin[1]) {
            $teamWin = $this->match->guestteam->nama;
        }
        else {
            $teamWin = false;
        }

        $data = [
            'match' => $this->match,
            'teamWin' => $teamWin

        ];

        return view('dashboard.components.report.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->match->total_skor != null) {
            return redirect()->route('admin.matchs.report.index', $this->match->id);
        }


        $data = [
            'match' => $this->match,
            'players' => $this->players,
        ];

        return view('dashboard.components.report.create', $data);
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
            'skor_home_team' => 'required',
            'skor_guest_team' => 'required',
        ];

        $messages = [
            'skor_home_team.required' => 'Skor Team Tuan Rumah wajib di isi !',
            'skor_guest_team.required' => 'Skor Team Tamu wajib di isi !',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->errorvalidator($validator->errors()->first());
        }
        // End : Validation

        $data = $request->all();

        $skorpoin = $data['skor_home_team'] . ":" . $data['skor_guest_team'];
        $this->match->update([
            'total_skor' => $skorpoin
        ]);

        if (isset($data['player_id']) && isset($data['time_goal'])) {
            if ($data['player_id'] != null && $data['time_goal'] != null ) {
                for ($i=0; $i < count($data['player_id']); $i++) {
                    Report::create([
                        'id' => Uuid::uuid4()->toString(),
                        'match_id' => $this->match->id,
                        'player_id' => $data['player_id'][$i],
                        'time_goal' => $data['time_goal'][$i],
                    ]);
                }
            }
        }


        $function = "created";
        $route = route('admin.matchs.report.index', $this->match->id);
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
    public function edit()
    {
        $arrWin = explode(":", $this->match->total_skor);

        $skorHomeTeam = $arrWin[0];
        $skorGuestTeam = $arrWin[1];

        $data = [
            'match' => $this->match,
            'players' => $this->players,
            'skorHomeTeam' => $skorHomeTeam,
            'skorGuestTeam' => $skorGuestTeam,
            'report' => $this->report->get()
        ];

        return view('dashboard.components.report.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Start : Validation
        $rules = [
            'skor_home_team' => 'required',
            'skor_guest_team' => 'required',
        ];

        $messages = [
            'skor_home_team.required' => 'Skor Team Tuan Rumah wajib di isi !',
            'skor_guest_team.required' => 'Skor Team Tamu wajib di isi !',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->errorvalidator($validator->errors()->first());
        }
        // End : Validation

        $data = $request->all();

        $skorpoin = $data['skor_home_team'] . ":" . $data['skor_guest_team'];
        $this->match->update([
            'total_skor' => $skorpoin
        ]);

        if ($this->report->count() > 0) {
            $this->report->delete();
        }

        if (isset($data['player_id']) && isset($data['time_goal'])) {
            if ($data['player_id'] != null && $data['time_goal'] != null ) {

                for ($i=0; $i < count($data['player_id']); $i++) {
                    Report::create([
                        'id' => Uuid::uuid4()->toString(),
                        'match_id' => $this->match->id,
                        'player_id' => $data['player_id'][$i],
                        'time_goal' => $data['time_goal'][$i],
                    ]);
                }

            }
        }


        $function = "updated";
        $route = route('admin.matchs.report.index', $this->match->id);
        return $this->successroute($function, $route);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $this->report->delete();
        $this->match->update([
            'total_skor' => null
        ]);

        $function = "deleted";
        $route = route('admin.matchs.index');

        return $this->successroute($function, $route);
    }
}
