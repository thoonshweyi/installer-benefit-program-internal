<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServerStatusController extends Controller
{
    function __construct()
    {
        //note to install sudo apt install netcat to server
    }
    public function checkServer($address)
    {
        if (strtolower(PHP_OS) == 'winnt') {
            $command = "nc -vz $address 7777";
            exec($command, $output, $status);
        } else {
            $command = "nc -vz $address 7777";
            exec($command, $output, $status);
        }
        // if (strtolower(PHP_OS) == 'winnt') {
        //     $command = "ping -n 1 $address";
        //     exec($command, $output, $status);
        // } else {
        //     $command = "ping -c 1 $address";
        //     exec($command, $output, $status);
        // }
        if ($status === 0) {
            return true;
        } else {
            return false;
        }
    }
    public function getLanthitServerStatus()
    {
        try {
            $address = '192.168.3.242';
            return $this->checkServer($address);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to show Lanthit Server Status!');
        }
    }
    public function getTheikPanServerStatus(Request $request)
    {
        try {
            $address = '192.168.21.242';
            return $this->checkServer($address);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to show Theik Pan Server Status!');
        }
    }
    public function getSatsanServerStatus(Request $request)
    {
        try {
            $address = '192.168.11.242';
            return $this->checkServer($address);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to show Theik Pan Server Status!');
        }
    }
    public function getEastDagonServerStatus(Request $request)
    {
        try {
            $address = '192.168.16.242';
            return $this->checkServer($address);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to show Theik Pan Server Status!');
        }
    }
    public function getMawlamyineServerStatus(Request $request)
    {
        try {
            $address = '192.168.31.242';
            return $this->checkServer($address);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to show Theik Pan Server Status!');
        }
    }
    public function getTampawadyServerStatus(Request $request)
    {
        try {
            $address = '192.168.25.242';
            return $this->checkServer($address);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to show Theik Pan Server Status!');
        }
    }
    public function getHlaingTharyarServerStatus(Request $request)
    {
        try {
            $address = '192.168.36.242';
            return $this->checkServer($address);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to show Theik Pan Server Status!');
        }
    }
    public function getAyeTharyarServerStatus(Request $request)
    {
        try {
            $address = '192.168.41.242';
            return $this->checkServer($address);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to show Theik Pan Server Status!');
        }
    }
    public function getTerminalMServerStatus(Request $request)
    {
        try {
            $address = '192.168.46.242';
            return $this->checkServer($address);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to show Theik Pan Server Status!');
        }
    }
    public function getSouthDagonServerStatus(Request $request)
    {
        try {
            $address = '192.168.51.243';
            return $this->checkServer($address);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to show Theik Pan Server Status!');
        }
    }
    public function getShwePyiTharServerStatus(Request $request)
    {
        try {
            $address = '192.168.56.242';
            return $this->checkServer($address);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()
                ->intended(route("home"))
                ->with('error', 'Fail to show Theik Pan Server Status!');
        }
    }
}
