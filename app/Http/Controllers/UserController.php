<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Jobs\SyncRowJob;
use App\Models\BranchUser;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Bago\BagoUser;
use App\Rules\MatchOldPassword;
use App\Models\Satsan\SatsanUser;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\Bago\BagoBranchUser;
use App\Models\Lanthit\LanthitUser;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Bago\BagoModelHasRole;
use App\Models\TheikPan\TheikPanUser;
use App\Models\EastDagon\EastDagonUser;
use App\Models\Satsan\SatsanBranchUser;
use App\Models\Tampawady\TampawadyUser;
use App\Models\TerminalM\TerminalMUser;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AyeTharyar\AyeTharyarUser;
use App\Models\Lanthit\LanthitBranchUser;
use App\Models\Mawlamyine\MawlamyineUser;
use App\Models\Satsan\SatsanModelHasRole;
use App\Models\SouthDagon\SouthDagonUser;
use App\Models\Lanthit\LanthitModelHasRole;
use App\Models\ShwePyiThar\ShwePyiTharUser;
use App\Models\TheikPan\TheikPanBranchUser;
use App\Models\EastDagon\EastDagonBranchUser;
use App\Models\Tampawady\TampawadyBranchUser;
use App\Models\TerminalM\TerminalMBranchUser;
use App\Models\TheikPan\TheikPanModelHasRole;
use App\Models\AyeTharyar\AyeTharyarBranchUser;
use App\Models\EastDagon\EastDagonModelHasRole;
use App\Models\HlaingTharyar\HlaingTharyarUser;
use App\Models\Mawlamyine\MawlamyineBranchUser;
use App\Models\SouthDagon\SouthDagonBranchUser;
use App\Models\Tampawady\TampawadyModelHasRole;
use App\Models\TerminalM\TerminalMModelHasRole;
use App\Models\AyeTharyar\AyeTharyarModelHasRole;
use App\Models\Mawlamyine\MawlamyineModelHasRole;
use App\Models\ShwePyiThar\ShwePyiTharBranchUser;
use App\Models\SouthDagon\SouthDagonModelHasRole;
use App\Models\ShwePyiThar\ShwePyiTharModelHasRole;
use App\Models\HlaingTharyar\HlaingTharyarBranchUser;
use App\Models\HlaingTharyar\HlaingTharyarModelHasRole;

class UserController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('permission:view-users', ['only' => ['index']]);
        // $this->middleware('permission:create-user', ['only' => ['create', 'store']]);
        // $this->middleware('permission:edit-user', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        // try {
            if ($request->ajax()) {
                $user_name = (!empty($_GET["user_name"])) ? ($_GET["user_name"]) : ('');
                $user_employee_id = (!empty($_GET["user_employee_id"])) ? ($_GET["user_employee_id"]) : ('');
                $user_email = (!empty($_GET["user_email"])) ? ($_GET["user_email"]) : ('');
                $user_role = (!empty($_GET["user_role"])) ? ($_GET["user_role"]) : ('');
                $branch_id = (!empty($_GET["branch_id"])) ? ($_GET["branch_id"]) : ('');
                $result = User::with('roles');
                if ($branch_id != "") {
                    $user_uuids = BranchUser::where('branch_id', $branch_id)->pluck('user_uuid')->toarray();
                    $result = $result->whereIn('uuid', $user_uuids);
                }
                if ($user_name != "") {
                    $result = $result->where('name', 'ilike', '%' . $user_name . '%');
                }
                if ($user_employee_id != "") {
                    $result = $result->where('employee_id', 'ilike', '%' . $user_employee_id . '%');
                }
                if ($user_email != "") {
                    $result = $result->where('email', 'ilike', '%' . $user_email . '%');
                }
                if ($user_role != "") {
                    $result = $result->whereHas(
                        'roles',
                        function ($q) use ($user_role) {
                            $q->where('name', $user_role);
                        }
                    );
                }
                $result = $result->get();
                return DataTables::of($result)
                    ->addColumn('branch_name', function ($data) {
                        $branches = BranchUser::where('user_uuid', $data->uuid)->with('branches')->get();
                        $branch_array = [];
                        foreach ($branches as $branch) {
                            $branch_array[] = $branch->branches->branch_name_eng;
                        }
                        return $branch_array;
                    })
                    ->addColumn('role', function ($data) {
                        if (isset($data->roles)) {
                            $data = $data->roles;
                            $role_array = [];
                            foreach ($data as $d) {
                                $role_array[] = $d->name;
                            }
                            return $role_array;
                        } else {
                            return '';
                        }

                    })
                    ->addColumn('action', function ($data) {
                        return 'action';
                    })
                    ->rawColumns(['action', 'role', 'branch_name'])
                    ->make(true);
            }
            $branches = Branch::select('branch_id', 'branch_name_eng')
                // ->wherein('branch_id', [2, 11, 1, 3, 9, 19, 10, 21, 27, 28,30])
                ->get();
            $roles = Role::get();
            return view('users.index', compact('branches','roles'));
        // } catch (\Exception$e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to load Data!');
        // }
    }

    public function create()
    {
        try {
            $branches = Branch::wherein('branch_id', [2, 11, 1, 3, 9, 19, 10, 21, 27, 28, 30])->get();
            $roles = Role::pluck('name', 'name')->all();
            return view('users.create', compact('roles', 'branches'));
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("users.index"))
                ->with('error', 'Fail to load Create Form!');
        }
    }

    public function store(Request $request)
    {
        // try {
            $this->validate($request, [
                'name' => 'required',
                'branch_id' => 'required',
                'employee_id' => 'required|unique:users,employee_id',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|same:confirm-password',
                'roles' => 'required',
            ]);
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            unset($input['branch_id']);
            $input['uuid'] = (string) Str::uuid();
            $user = User::create($input);
            // dispatch(new SyncRowJob("users","insert",$user));


            $user_uuid = $user->uuid;
            $user_id = $user->id;
            $branch_ids = $request->branch_id;
            $roles = $request->roles;
            foreach ($branch_ids as $branch_id) {
                $userBranch['user_uuid'] = $user_uuid;
                $userBranch['branch_id'] = $branch_id;
                BranchUser::create($userBranch);
                $input['id'] = $user->id;
            }
            $user->assignRole($request->input('roles'));

            return redirect()->route('users.index')
                ->with('success', 'User created successfully');
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("users.index"))
        //         ->with('error', 'Fail to Store User!');
        // }
    }

    public function show($id)
    {
        // dd('hay');
        try {
            $user = User::find($id);
            $branches = BranchUser::where('user_uuid', $user->uuid)->with('branches')->get();
            return view('users.show', compact('user', 'branches'));
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("users.index"))
                ->with('error', 'Fail to Load User!');
        }
    }

    public function edit($id)
    {
        try {
            $branches = Branch::get();
            $user = User::find($id);
            $roles = Role::pluck('name', 'name')->all();
            $userRole = $user->roles->pluck('name', 'name')->all();
            $userBranches = BranchUser::where('user_uuid', $user->uuid)->pluck('branch_id')->toArray();
            return view('users.edit', compact('user', 'roles', 'userRole', 'branches', 'userBranches'));
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("users.index"))
                ->with('error', 'Fail to Load Edit Form!');
        }
    }

    public function update(Request $request, $id)
    {
        // try {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|min:6|email|unique:users,email,' . $id,
                'password' => 'same:confirm-password',
                'roles' => 'required',
            ]);

            $input = $request->all();
            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, array('password'));
            }

            unset($input['branch_id']);
            $user = User::find($id);
            $user->update($input);
            // dispatch(new SyncRowJob("users","update",$user));

            $user_uuid = $user->uuid;
            $user_id = $user->id;
            $roles = $request->roles;
            $input['uuid'] = $user_uuid;
            $input['password'] = $user->password;

            DB::table('branch_users')->where('user_uuid', $user_uuid)->delete();
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $branch_ids = $request->branch_id;

            foreach ($branch_ids as $branch_id) {
                $userBranch['user_uuid'] = $user_uuid;
                $userBranch['branch_id'] = $branch_id;
                BranchUser::create($userBranch);
            }
            $user->assignRole($request->input('roles'));

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully');
        // } catch (\Exception$e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("user.index"))
        //         ->with('error', 'Fail to update User!');
        // }
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);
            if($user->roles[0]->name == "Admin" || $user->roles[0]->name == "Super Admin"){
                return response()->json(["error"=>"Cannot Delete Admin!"]);
            }
            // $userId = $user->id;
            $user->delete();
            // dispatch(new SyncRowJob("users", "delete", ['id' => $userId]));

            return response()->json(["success"=>"User deleted successfully"]);
        } catch (\Exception$e) {
            Log::debug($e->getMessage());

            return response()->json(["error"=>"Fail to delete User!"]);

        }
    }

    public function profile()
    {
        try {
            $user = User::where('id', auth()->user()->id)->first();
            return view('users.profile', compact('user'));
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("users.index"))
                ->with('error', 'Fail to Load User Profile!');
        }
    }

    public function update_profile(Request $request)
    {
        try {
            $request->validate([
                'cpass' => ['required', new MatchOldPassword],
                'npass' => ['required'],
                'vpass' => ['same:npass'],
            ],
                [
                    'npass.required' => 'New Password is required!',
                    'vpass.required' => 'Verfiy Password is required!',
                    'vpass.same' => 'Verfiy Password is not same with New Password!',
                ]);
            User::find(auth()->user()->id)->update(['password' => Hash::make($request->npass)]);
            return redirect()->route('user.profile')->with('success', 'Password Changed successfully');
        } catch (\Exception$e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("users.index"))
                ->with('error', 'Fail to update User Profile!');
        }
    }

    public function check_user(Request $request)
    {
        $employee_id = $request->employee_id;
        $password = $request->password;
        $pattern_1 = '/^([0-9\s\-\+\(\)]*)$/';
        $case_1 = preg_match($pattern_1, $employee_id);
        $pattern_2 = filter_var($employee_id, FILTER_VALIDATE_EMAIL);
        $case_2 = $pattern_2 ? 1 : 0;
        $pattern_3 = '/^\d{3}(-\d{6})?$/';
        $case_3 = preg_match($pattern_3, $employee_id);
        if ($case_3 === 1) {
            $user = User::where('employee_id', $employee_id)->first();
        } else if ($case_2 === 1) {
            $user = User::where('email', $employee_id)->first();
        }
        if (isset($user)) {
            // Set Auth Details
            if (Hash::check($password, $user->password)) {
                $user_uuid = [
                    'user_uuid' => $user->uuid
                ];
                if($user->can('reprint-ticket')){
                    return response()->json(['data' => $user_uuid], 200);
                }else{
                    return response()->json(['error' => 'do_not_have_permission_to_reprint'], 200);
                }
            } else {
                return response()->json(['error' => 'password_is_not_correct'], 200);
            }
        } else {
            return response()->json(['error' => 'user_not_found'], 200);
        }
    }
}
