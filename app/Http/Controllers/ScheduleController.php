<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = Schedule::all();
        $events = [];
        foreach ($schedules as $schedule) {
            $events[] = [
                'id' => $schedule->id,
                'title' => $schedule->title,
                'description' => $schedule->description,
                'start' => $schedule->start_date,
                'end' => Carbon::parse($schedule->end_date)->addDay()->format('Y-m-d'),
                'textColor' => 'white',
                'backgroundColor' => 'red',
                'borderColor' => 'black',
                'url' => route('schedules.edit', $schedule->id)
            ];
        }

        return view('schedules.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('schedules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScheduleRequest $request)
    {
        $request->merge(['user_id' => $request->user()->id]);
        // 保存処理
        Schedule::create($request->all());
        return redirect()->route('schedules.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        return view('schedules.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($request->all());
        return redirect()->route('schedules.index');
    }

    /**
     * カレンダーからの更新
     */
    public function updateByCalendar(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $request->merge([
            'start_date' => Carbon::parse($request->start_date),
            'end_date' => Carbon::parse($request->end_date)->subDay()
        ]);
        $schedule->update($request->all());
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        //
    }
}
