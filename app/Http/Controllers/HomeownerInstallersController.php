<?php

namespace App\Http\Controllers;

use App\Models\HomeOwner;
use Illuminate\Http\Request;
use App\Models\InstallerCard;
use App\Models\HomeownerInstaller;
use Illuminate\Support\Facades\Auth;
use App\Models\HomeownerInstallerHistory;

class HomeownerInstallersController extends Controller
{
    public function store(Request $request){

        $request->validate([
                "home_owners" => "required|array",
        ]);

        $installercard = InstallerCard::where('card_number',$request->card_number)->first();
        $home_owners = $request->home_owners;
        // foreach($home_owners as $home_owner){
        //     $homeowner = HomeOwner::where('uuid',$home_owner)->first();
        //     $installercard->homeowners()->save($homeowner);
        // }
        $installercard->homeowners()->attach($home_owners);

        // dd($installercard);
        $homeownerinstallerhistory = $this->recordHistory($installercard);

        return redirect()->back()->with('success',"Home Owner Added Successfully");
    }


    public function destroy($id){
        // InstallerCard::destroy($id);
        $homeownerinstaller = HomeownerInstaller::where('id',$id)->orderBy('id','asc')->first();

        // Delete the installer card
        $homeownerinstaller->delete();

        $installercard = InstallerCard::where('card_number',$homeownerinstaller->installer_card_card_number)->first();
        $homeownerinstallerhistory = $this->recordHistory($installercard);

        // Dispatch sync job with only the necessary data
        return redirect()->back()->with('success','Home Owner Removed Successfully');
    }

    public function recordHistory($installercard){
        $user = Auth::user();
        $user_uuid = $user->uuid;

        $homeownerinstaller = HomeownerInstaller::where('installer_card_card_number',$installercard->card_number)->orderBy('id','asc')->pluck('home_owner_uuid');


        $homeownerinstallerhistory = new HomeownerInstallerHistory();
        $homeownerinstallerhistory->installer_card_card_number = $installercard->card_number;
        $homeownerinstallerhistory->home_owner_uuids = json_encode($homeownerinstaller);
        $homeownerinstallerhistory->user_uuid = $user_uuid;
        $homeownerinstallerhistory->save();
        return $homeownerinstallerhistory;
    }

    public function bulkdeletes(Request $request)
    {
        try{
            $getselectedids = $request->selectedids;

            $homeownerinstallers =  HomeownerInstaller::whereIn("id",$getselectedids)->get();
            $installercard = InstallerCard::where('card_number',$homeownerinstallers[0]->installer_card_card_number)->first();

            HomeownerInstaller::whereIn("id",$getselectedids)->delete();
            $homeownerinstallerhistory = $this->recordHistory($installercard);

            return response()->json(["success"=>"Selected data have been deleted successfully"]);
        }catch(Exception $e){
                Log::error($e->getMEssage());
                return response()->json(["status"=>"failed","message"=>$e->getMessage()]);
        }
    }

}
