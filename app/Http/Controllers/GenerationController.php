<?php

namespace App\Http\Controllers;

use App\Models\Checkpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GenerationController extends Controller
{
    public function index(Request $request) {

        // $checkpoints = Checkpoint::all();
        // return view('generate', ['checkpoints' => $checkpoints, 'runtime' => $request['runtime-url']]);

        if (!$request->all()) {
            return view('error', ['msg' => "No runtime connected.<br>Please connect runtime <a href=\"/\">here</a>"]);
        } else {
            if ($request['runtime-url']) {
                if (strpos('http', $request['runtime-url']) !== null) {
                    try {
                        $response = Http::post($request['runtime-url'] . "/connect");
                        
                        if ($response->json()['msg'] === "Connected") {
                            $checkpoints = Checkpoint::all();
                            return view('generate', ['checkpoints' => $checkpoints, 'runtime' => $request['runtime-url']]);
                        } else {
                            return view('error', ['msg' => "Unable to connect to <a href='". $request['runtime-url'] ."'>". $request['runtime-url'] ."</a><br>Please <a href='/'>try again</a>"]);
                        }
                    } catch (\Exception $e) {
                        return view('error', ['msg' => "Unable to connect to <a href='". $request['runtime-url'] ."'>". $request['runtime-url'] ."</a><br>Please <a href='/'>try again</a>"]);
                    }
                } else {
                    return view('error', ['msg' => "No runtime connected.<br>Please connect runtime <a href=\"/\">here</a>"]);
                }
            } else {
                return view('error', ['msg' => "No runtime connected.<br>Please connect runtime <a href=\"/\">here</a>"]);
            }
        }
    }

    public function getCheckpoint() {
        $checkpoints = Checkpoint::all();
        
        return response()->json($checkpoints);
    }

    public function loadCheckpoint(Request $request) {
        set_time_limit(300);
        try {
            $response = Http::timeout(300)->post($request['runtimeUrl'] . "/loadcheckpoint", [
                "checkpoint"=>$request['checkpoint']
            ]);

            if (isset($response->json()['checkpoint'])) {
                return response()->json(["checkpoint" => $response->json()['checkpoint']]);
            }
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function addCheckpoint(Request $request) {
        set_time_limit(400);
        try {
            $response = Http::timeout(400)->post($request['runtimeUrl'] . "/loadcheckpoint", [
                "checkpoint"=>$request['checkpoint']
            ]);

            if (isset($response->json()['checkpoint'])) {
                Checkpoint::create([
                    'checkpoint' => $request["checkpoint"],
                    'checkpoint_type' => "SD 1.5"
                ]);
                return response()->json(["checkpoint" => $response->json()['checkpoint']]);
            } else {
                return response()->json(["msg"=>"URL is not HuggingFace Diffusers Checkpoint", "error" => 1]);
            }
        } catch (\Exception $e) {
            return response()->json(["msg"=>"URL is not HuggingFace Diffusers Checkpoint", "error" => 1]);
        }
    }
}
