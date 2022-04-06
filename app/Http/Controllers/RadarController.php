<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Radar;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

class RadarController extends Controller
{
    /**
   * Login user
   *
   * @param  Request $request
   * @return \Illuminate\View\View
   */
  public function createRadar(Request $request)
  {

    $username = session()->get('user');
    $user = User::where('username', $username)->get()->first();
    $name = $request->name;
    $description = $request->description;

    if($user->isModerator()) {

      if(isset($request->name) && isset($request->description)) {
        if (Radar::where('name', $name)->count()==0) {

          $radar = Radar::create([
            'name' => $name,
            'moderator_id' => $user->id,
            'description' => $description,
          ]);

          // Make entry in working_on
          DB::table('working_on')->insert([
            'user_id' => $user->id,
            'radar_id' => $radar->id,
          ]);

          // Create Radar Entries (2 actors default)
          DB::table('radar_entry')->insert([
            [
              'radar_id' => $radar->id,
              'slice_position' => 0,
              'ring_position' => 0,
              'value' => 'Business Proposition'
            ],
            [
              'radar_id' => $radar->id,
              'slice_position' => 1,
              'ring_position' => 1,
              'value' => 'Actor Value Proposition 1'
            ],
            [
              'radar_id' => $radar->id,
              'slice_position' => 1,
              'ring_position' => 2,
              'value' => 'Actor co-production activity 1'
            ],
            [
              'radar_id' => $radar->id,
              'slice_position' => 1,
              'ring_position' => 31,
              'value' => 'Actor cost 1'
            ],
            [
              'radar_id' => $radar->id,
              'slice_position' => 1,
              'ring_position' => 32,
              'value' => 'Actor benefit 1'
            ],
            [
              'radar_id' => $radar->id,
              'slice_position' => 1,
              'ring_position' => 4,
              'value' => 'Actor 1'
            ],
            [
              'radar_id' => $radar->id,
              'slice_position' => 2,
              'ring_position' => 1,
              'value' => 'Actor Value Proposition 2'
            ],
            [
              'radar_id' => $radar->id,
              'slice_position' => 2,
              'ring_position' => 2,
              'value' => 'Actor co-production activity 2'
            ],
            [
              'radar_id' => $radar->id,
              'slice_position' => 2,
              'ring_position' => 31,
              'value' => 'Actor cost 2'
            ],
            [
              'radar_id' => $radar->id,
              'slice_position' => 2,
              'ring_position' => 32,
              'value' => 'Actor benefit 2'
            ],
            [
              'radar_id' => $radar->id,
              'slice_position' => 2,
              'ring_position' => 4,
              'value' => 'Actor 2'
            ],
          ]);

          return view('dashboard', [
            'message' => 'A blank project has been created and can now be edited. Project Name: '.$name,
          ]);

        } else {
          return view('dashboard', [
            'message' => 'Project could not be created. Make sure to enter an unique Project Name!',
          ]);
        }
      } else {
        return view('dashboard', [
          'message' => 'Please fill in a project name and a description',
        ]);
      }
    } else {
      return view('dashboard', [
        'message' => 'Only Moderators are allowed to create a new project.',
      ]);
    }
  }

    /**
   * Get Projects of user
   *
   * @param  Request $request
   * @return \Illuminate\View\View
   */
  public function getProjects(Request $request)
  {
    if(session()->has('loggedin')) {

      if($request->ajax()) {
        $username = session()->get('user');
        $user = User::where('username', $username)->get()->first();
        //echo($user->projects()->get());

        $projects = $user->projects()->get()->map(function ($project) {
                      return collect([
                          'name'        => $project->name,
                          'moderator'   => User::find($project->moderator_id)->username,
                          'description' =>  $project->description,
                          'link'        =>  route('showRadar', ['radarId' => $project->id]),
                          'link2'       => route('addUser', ['radarId' => $project->id]),
                      ]);
                  });;

        return Datatables::of($projects)->toJson();
      }
      return view('dashboard');

    } else {
      return view('dashboard', [
        'message' => 'You have to be logged in in order to see your projects',
      ]);
    }
  }

  /**
  * Displays a certain radar
  *
  * @param  Request $request
  * @return \Illuminate\View\View
  */
  public function showRadar(Request $request)
  {

    if(session()->has('loggedin')) {
      $radar = Radar::find($request->radarId);
      $entries = $radar->entries()->get();

      $json = $entries->map(function ($entry) {

        $value = $entry->value;
        $slice = $entry->slice_position;
        $ring = $entry->ring_position;
        $parent = $ring-1;

        if ($ring == 0) {
          $array = array("name" => $value, "id" => $slice.".".$ring, "parent" => null);
        } else if ($ring == 1) {
          $array = array("name" => $value, "id" => $slice.".".$ring, "parent" => "0.0");
        } else if ($ring == 2) {
          $array = array("name" => $value, "id" => $slice.".".$ring, "parent" => $slice.".".$parent);
        } else if ($ring == 31 || $ring == 32) {
          $array = array("name" => $value, "id" => $slice.".".$ring, "parent" => $slice.".2");
        }else if ($ring == 4) {
          $array = array("name" => $value, "id" => $slice.".".$ring, "parent" => $slice.".31",
            "normal" => json_decode(json_encode(array("fill" => "white"))));
        }
        return json_decode(json_encode($array));
      });

        return view('radar', [
          'data' => $json,
          'title' => $radar->name,
        ]);
      } else {
        return view('dashboard', [
          'message' => 'You have to be logged in in order to see your projects',
        ]);
      }
  }

  public function addUser(Request $request) {
    if(session()->has('loggedin')) {
      // get all users except the logged in user
      $users = User::where('username', '!=', session()->get('user'))->get()->map(function ($user) {
        return [
          'username' => $user->username
        ];
      });
      return view('addUser', [
        'project' => Radar::find($request->radarId)->name,
        'users' => $users,
      ]);
    } else {
      return view('dashboard', [
        'message' => 'You have to be logged in in order to add other users to your project',
      ]);
    }
  }

  public function add(Request $request) {
    if(session()->has('loggedin')) {

      $user = User::where('username', $request->user)->first();
      // Make entry in working_on
      DB::table('working_on')->updateOrInsert([
        'user_id' => $user->id,
        'radar_id' => $request->radarId,
      ]);
      return view('dashboard', [
        'message' => 'The user '.$request->user.' has successfully been added to the project!',
      ]);
    } else {
      return view('dashboard', [
        'message' => 'You have to be logged in in order to add other users to your project',
      ]);
    }
  }

  public function editRadar(Request $request) {

    if(session()->has('loggedin')) {

      // TODO: Data has already been requested to show the radar
      // => pass data through instead of requesting it again?
      $radar = Radar::find($request->radarId);
      $entries = $radar->entries()->get();

      $json = $entries->map(function ($entry) {

        $value = $entry->value;
        $slice = $entry->slice_position;
        $ring = $entry->ring_position;
        $parent = $ring-1;

        if ($ring == 0) {
          $array = array("name" => $value, "id" => $slice.".".$ring, "parent" => null);
        } else if ($ring == 1) {
          $array = array("name" => $value, "id" => $slice.".".$ring, "parent" => "0.0");
        } else if ($ring == 2) {
          $array = array("name" => $value, "id" => $slice.".".$ring, "parent" => $slice.".".$parent);
        } else if ($ring == 31 || $ring == 32) {
          $array = array("name" => $value, "id" => $slice.".".$ring, "parent" => $slice.".2");
        } else if ($ring == 4) {
          $array = array("name" => $value, "id" => $slice.".".$ring, "parent" => $slice.".31",
            "normal" => json_decode(json_encode(array("fill" => "white"))));
        }
        return json_decode(json_encode($array));
      });

      return view('editRadar', [
        'data' => $json,
        'title' => $radar->name,
      ]);
    } else {
      return view('dashboard', [
        'message' => 'You have to be logged in in order to edit a project',
      ]);
    }
  }

  public function saveChanges(Request $request) {
    // retrieve data
    $radarId = $request->radar;
    $dataObject = json_decode(json_encode($request->data));
    //echo($dataObject[0]->name);
    $slicesBefore = $request->slicesBefore;
    $slicesAfter = $request->slicesAfter;
    $changedEntries = $request->changedEntries;
    //echo(json_encode($changedEntries));

    // if new slices have been added, create database entries
    if ($slicesAfter > $slicesBefore) {
      // new db entry for every element in data where first part of id (slice number) bigger than $slicesBefore
      foreach ($dataObject as $entry) {
        $array = explode(".", $entry->id);
        $slice = $array[0];
        if ($slice > $slicesBefore) {
          // Create Radar Entry (if somehow entry for this radar with this slice and ring position exists it only updates value instead of adding another record)
          DB::table('radar_entry')->updateOrInsert(
            [
              'radar_id' => $radarId,
              'slice_position' => $slice,
              'ring_position' => $array[1],
            ],
            [ 'value' => $entry->name ]
          );
        }
      }
    // if slices have been deleted, delete database entries
    } else if ($slicesAfter < $slicesBefore) {
      // delete every element where slice number bigger than $slicesAfter
      DB::table('radar_entry')->where('slice_position', '>', $slicesAfter)->delete();
    }

    // Slices that already existed need to be updated when entries have been edited
    if($changedEntries!==null) {
      foreach ($changedEntries as $id) {
        $array = explode(".", $id);
        $slice = $array[0];
        $ring = $array[1];
        if ($slice <= $slicesBefore) {
          // search for entry with specific id
          $value=null;
          foreach ($dataObject as $entry) {
            if ($entry->id === $id) {
              $value = $entry->name;
            }
          }
          if ($value!==null) {
            DB::table('radar_entry')->where('radar_id', $radarId)->where('slice_position', $slice)
              ->where('ring_position', $ring)->update(['value' => $value ]);
          }
        }
      }
    }
  }
}
