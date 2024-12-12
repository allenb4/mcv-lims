<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\BulkActionRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Branch;
use App\Models\UserBranch;
use DataTables;

class UsersController extends Controller
{

    /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_user',     ['only' => ['index', 'show','ajax']]);
        $this->middleware('can:create_user',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_user',     ['only' => ['edit', 'updae']]);
        $this->middleware('can:delete_user',   ['only' => ['destroy','bulk_delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('admin.users.index');
    }

    /**
    * get users datatable
    *
    * @access public
    * @var  @Request $request
    */
    public function ajax(Request $request)
    {
        $model=User::query()->with('roles','branches');

        return DataTables::eloquent($model)
        ->addColumn('roles',function($user){
            return view('admin.users._roles',compact('user'));
        })
        ->addColumn('branches',function($user){
            return view('admin.users._branches',compact('user'));
        })
        ->addColumn('action',function($user){
            return view('admin.users._action',compact('user'));
        })
        ->addColumn('bulk_checkbox',function($item){
            return view('partials._bulk_checkbox',compact('item'));
        })
        ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles=Role::all();
        $branches=Branch::all();
        return view('admin.users.create',compact('roles','branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        //create new user
        $user=new User;
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);
        $user->save();

        //assign roles to user
        if($request->has('roles'))
        {
            foreach($request['roles'] as $role)
            {
                UserRole::firstOrCreate([
                    'user_id'=>$user['id'],
                    'role_id'=>$role
                ]);
                
            }
        }

        if($request->has('branches'))
        {
            foreach($request['branches'] as $branch)
            {
                UserBranch::firstOrCreate([
                    'user_id'=>$user['id'],
                    'branch_id'=>$branch
                ]);
                
            }
        }

        session()->flash('success',__('User created successfully'));

        return redirect()->route('admin.users.index');
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
        $user=User::findOrFail($id);
        $roles=Role::all();
        $branches=Branch::all();
        
        return view('admin.users.edit',compact('user','roles','branches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        //update user
        $user=User::findOrFail($id);
        $user->name=$request->name;
        $user->email=$request->email;
       
        //optional updating password
        if(!empty($request['password']))
        {
            $user->password=bcrypt($request->password);
        }
        
        $user->save();

        if($user['id']!=1)
        {
            //assign roles to user
            $user->roles()->delete();

            if($request->has('roles'))
            {
                foreach($request['roles'] as $role)
                {
                    $user->roles()->create([
                        'user_id'=>$id,
                        'role_id'=>$role
                    ]);
                }
            }

            //assign branches for user
            $user->branches()->delete();

            if($request->has('branches'))
            {
                foreach($request['branches'] as $branch)
                {
                    $user->branches()->create([
                        'user_id'=>$user['id'],
                        'branch_id'=>$branch
                    ]);
                }
            }
        }
       
        session()->flash('success',__('User updated successfully'));

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($id==1)
        {
            session()->flash('failed',__('You can\'t delete super admin user'));
            
            return redirect()->back();
        }

        $user=User::findorFail($id);
        
        //delete assigned roles
        UserRole::where('user_id',$id)->delete();

        //delete user finally
        $user->branches()->delete();
        $user->delete();

        session()->flash('success',__('User deleted successfully'));

        return redirect()->route('admin.users.index');
    }

    /**
     * Bulk delete
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulk_delete(BulkActionRequest $request)
    {
        foreach($request['ids'] as $id)
        {
            if($id!=1)
            {
                $user=User::find($id);
                //delete assigned roles
                UserRole::where('user_id',$id)->delete();
                //delete user finally
                $user->branches()->delete();
                $user->delete();
            } 
        }

        session()->flash('success',__('Bulk deleted successfully'));

        return redirect()->route('admin.users.index');
    }
}
