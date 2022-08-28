<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Player;
use App\Models\Stage;
use Carbon\Carbon;
use App\Http\Requests\Schedule\CreateRequest;


class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = Schedule::with('stage')->whereDate('date', '>=', Carbon::now())->orderByRaw("date ASC, stage_id ASC, venue_time ASC")->get();
        $players = Player::all();

        return view('schedules.index', compact('schedules', 'players'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stages = Stage::all();
        $players = Player::all();

        return view('schedules.create', compact('players', 'stages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $schedule = Schedule::create([
            'title' => $request->title,
            'venue_time' => $request->venue_time,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
            'stage_id' => $request->stage_id,
            'date' => $request->date,
        ]);

        $schedule->player()->sync($request->player_id);

        return redirect()->route('schedules.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedules = Player::find($id)->schedules()->whereDate('date', '>=', Carbon::now())->orderByRaw("date ASC, stage_id ASC, venue_time ASC")->get();
        $players = Player::all();
        
        return view('schedules.index', compact('schedules', 'players'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $schedule = Schedule::with('player')->find($id);
        $stages = Stage::all();
        $players = Player::all();

        return view('schedules.edit', compact('players', 'stages', 'schedule'));
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
        $schedule = Schedule::find($id);
        $schedule->fill($request->except('player_id'))->save();
        $schedule->player()->sync($request->player_id);

        return redirect()->route('schedules.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 選択された記事データを取得
        $schedule = Schedule::find($id);
        $schedule->player()->detach();
        $schedule->delete();

        return redirect()->route('schedules.index');
    }
}
