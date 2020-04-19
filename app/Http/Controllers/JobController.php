<?php

namespace App\Http\Controllers;

use App\Job;
use App\Http\Requests\JobRequest;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->checkAuthorization($request);
        $jobs = Job::all();
        return view('job.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->checkAuthorization($request);
        return view('job.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JobRequest $request)//fix the filter
    {
        $this->checkAuthorization($request);
        $job = new Job();
        $job->name = $request->input('name');
        $job->description = $request->input('description');
        $job->rate = floatval($request->input('rate'));
        $job->push();
        return redirect()->route('job.index')->with('status', 'The job has been created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job, Request $request)
    {
        $this->checkAuthorization($request);
        return view('job.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job, Request $request)
    {
        $this->checkAuthorization($request);
        return view('job.edit', compact('job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(JobRequest $request, Job $job)
    {
        $this->checkAuthorization($request);
        $job->update($request->all());
        return redirect()->route('job.index')->with('status', 'The job has been updated successfully.');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job, Request $request)
    {
        $this->checkAuthorization($request);
        $job->delete();
        return redirect()->route('job.index');
    }

    public function checkAuthorization(Request $request){
        if($request->user() != null){
            $request->user()->authorizeRoles(['Admin']);
        }else{
            abort(401);
        }
    }
}
