<?php

namespace App\Http\Controllers;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index() 
    {

        $schedules = Schedule::all();
        $events = [];
        foreach ($schedules as $schedule) {
            $events[] = [
                'id'          => $schedule->id,
                'title'       => $schedule->title,
                'description' => $schedule->description,
                'start'       => $schedule->start_date,
                'end'         => Carbon::parse($schedule->end_date)->addDay()->format('Y-m-d'),
                'backgroundColor' => 'red',
                'textColor' => 'yellow',
                'borderColor' => 'black',
                'url' => route('schedules.edit', $schedule->id)
            ];
        }
        return view('schedules.index');
    }
    public function create() 
    {
        return view('schedules.create');
    }
    public function store(Request $request) 
    {
        $request->merge(['user_id' => auth()->id()]);
        Schedule::create($request->all());
        return back()->with('status','登録しました');
    }
    public function edit(Schedule $schedule)
    {
        return view('schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $schedule->update($request->all());
        return redirect()->route('schedules.index')->with('status', '更新しました。');
    }

    /**
     * カレンダーからの更新
     */
    public function updateByCalendar(Request $request, Schedule $schedule)
    {
        $request->merge([
            'start_date' => Carbon::parse($request->start_date),
            'end_date' => Carbon::parse($request->end_date)->subDay()
        ]);
        $schedule->update($request->all());
        return response()->json(['success' => true]);
    }
}
