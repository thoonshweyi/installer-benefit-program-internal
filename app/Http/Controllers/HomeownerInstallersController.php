<?php

namespace App\Http\Controllers;

use App\Models\HomeOwner;
use Illuminate\Http\Request;
use App\Models\InstallerCard;
use App\Models\HomeownerInstaller;

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

        return redirect()->back()->with('success',"Home Owner Added Successfully");
    }


    public function destroy($id){
        // InstallerCard::destroy($id);
        $homeownerinstaller = HomeownerInstaller::where('id',$id)->orderBy('id','asc')->first();

        // Delete the installer card
        $homeownerinstaller->delete();

        // Dispatch sync job with only the necessary data
        return redirect()->back()->with('success','Home Owner Removed Successfully');
    }

}
