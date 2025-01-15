<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Models\Customer;
use App\Models\HOCustomer;
use App\Models\TicketHeader;
use App\Models\Bago\BagoTicket;
use Illuminate\Console\Command;
use App\Models\Bago\BagoCustomer;
use App\Models\Satsan\SatsanTicket;
use App\Models\TicketHeaderInvoice;
use Illuminate\Support\Facades\Log;
use App\Models\TicketHeaderStepSale;
use App\Models\Bago\BagoTicketHeader;
use App\Models\Lanthit\LanthitTicket;
use App\Models\Satsan\SatsanCustomer;
use App\Models\Lanthit\LanthitCustomer;
use App\Models\TheikPan\TheikPanTicket;
use App\Models\EastDagon\EastDagonTicket;
use App\Models\Satsan\SatsanTicketHeader;
use App\Models\Tampawady\TampawadyTicket;
use App\Models\TerminalM\TerminalMTicket;
use App\Models\TheikPan\TheikPanCustomer;
use App\Models\AyeTharyar\AyeTharyarTicket;
use App\Models\EastDagon\EastDagonCustomer;
use App\Models\Lanthit\LanthitTicketHeader;
use App\Models\Mawlamyine\MawlamyineTicket;
use App\Models\SouthDagon\SouthDagonTicket;
use App\Models\Tampawady\TampawadyCustomer;
use App\Models\TerminalM\TerminalMCustomer;
use App\Models\Bago\BagoTicketHeaderInvoice;
use App\Models\AyeTharyar\AyeTharyarCustomer;
use App\Models\Bago\BagoTicketHeaderStepSale;
use App\Models\Mawlamyine\MawlamyineCustomer;
use App\Models\ShwePyiThar\ShwePyiTharTicket;
use App\Models\SouthDagon\SouthDagonCustomer;
use App\Models\TheikPan\TheikPanTicketHeader;
use App\Models\EastDagon\EastDagonTicketHeader;
use App\Models\ShwePyiThar\ShwePyiTharCustomer;
use App\Models\Tampawady\TampawadyTicketHeader;
use App\Models\TerminalM\TerminalMTicketHeader;
use App\Models\Satsan\SatsanTicketHeaderInvoice;
use App\Models\AyeTharyar\AyeTharyarTicketHeader;
use App\Models\HlaingTharyar\HlaingTharyarTicket;
use App\Models\Mawlamyine\MawlamyineTicketHeader;
use App\Models\Satsan\SatsanTicketHeaderStepSale;
use App\Models\SouthDagon\SouthDagonTicketHeader;
use App\Models\Lanthit\LanthitTicketHeaderInvoice;
use App\Models\HlaingTharyar\HlaingTharyarCustomer;
use App\Models\Lanthit\LanthitTicketHeaderStepSale;
use App\Models\ShwePyiThar\ShwePyiTharTicketHeader;
use App\Models\TheikPan\TheikPanTicketHeaderInvoice;
use App\Models\TheikPan\TheikPanTicketHeaderStepSale;
use App\Models\EastDagon\EastDagonTicketHeaderInvoice;
use App\Models\Tampawady\TampawadyTicketHeaderInvoice;
use App\Models\TerminalM\TerminalMTicketHeaderInvoice;
use App\Models\EastDagon\EastDagonTicketHeaderStepSale;
use App\Models\HlaingTharyar\HlaingTharyarTicketHeader;
use App\Models\Tampawady\TampawadyTicketHeaderStepSale;
use App\Models\TerminalM\TerminalMTicketHeaderStepSale;
use App\Models\AyeTharyar\AyeTharyarTicketHeaderInvoice;
use App\Models\Mawlamyine\MawlamyineTicketHeaderInvoice;
use App\Models\SouthDagon\SouthDagonTicketHeaderInvoice;
use App\Models\AyeTharyar\AyeTharyarTicketHeaderStepSale;
use App\Models\Mawlamyine\MawlamyineTicketHeaderStepSale;
use App\Models\SouthDagon\SouthDagonTicketHeaderStepSale;
use App\Models\ShwePyiThar\ShwePyiTharTicketHeaderInvoice;
use App\Models\ShwePyiThar\ShwePyiTharTicketHeaderStepSale;
use App\Models\HlaingTharyar\HlaingTharyarTicketHeaderInvoice;
use App\Models\HlaingTharyar\HlaingTharyarTicketHeaderStepSale;

class LoadDataFromBranchesCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loadData:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info("Cron is starting !");
        //Get Customer Data
        $ayetharyarCustomer = AyeTharyarCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $eastdagonCustomer = EastDagonCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $hlaingtharyarCustomer = HlaingTharyarCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $lanthitCustomer = LanthitCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $mawlamyineCustomer = MawlamyineCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $satsanCustomer = SatsanCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $tampawadyCustomer = TampawadyCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $terminalCustomer = TerminalMCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $theikpanCustomer = TheikPanCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $southdagonCustomer = SouthDagonCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $shwepyitharCustomer = ShwePyiTharCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $bagoCustomer = BagoCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();

        // $branchCustomers = array_merge_recursive($ayetharyarCustomer, $eastdagonCustomer, $hlaingtharyarCustomer, $lanthitCustomer, $mawlamyineCustomer, $satsanCustomer, $tampawadyCustomer, $terminalCustomer, $theikpanCustomer,$southdagonCustomer);
        // foreach($branchCustomers as $bCustomer){
        //     //Add or Update
        //     $customer = Customer::where('uuid',$bCustomer['uuid'])->first();

        //     if($customer){
        //         $customer->update($bCustomer);
        //     }else{
        //         Customer::create($bCustomer);
        //     }
        // }

        //Aye Tharyar
        foreach ($ayetharyarCustomer as $bCustomer) {
            //Add or Update
            $customer = Customer::where('uuid', $bCustomer['uuid'])->first();

            if ($customer) {
                $customer->update($bCustomer);
            } else {
                Customer::create($bCustomer);
            }
        }
        // Log::info("Finish Getting Customer Data From ATY Branch!");

        //East Dagon
        foreach ($eastdagonCustomer as $bCustomer) {
            //Add or Update
            $customer = Customer::where('uuid', $bCustomer['uuid'])->first();

            if ($customer) {
                $customer->update($bCustomer);
            } else {
                Customer::create($bCustomer);
            }
        }
        // Log::info("Finish Getting Customer Data From EDG Branch!");

        //HTY
        foreach ($hlaingtharyarCustomer as $bCustomer) {
            //Add or Update
            $customer = Customer::where('uuid', $bCustomer['uuid'])->first();

            if ($customer) {
                $customer->update($bCustomer);
            } else {
                Customer::create($bCustomer);
            }
        }
        // Log::info("Finish Getting Customer Data From HTY Branch!");

        //LTN
        foreach ($lanthitCustomer as $bCustomer) {
            //Add or Update
            $customer = Customer::where('uuid', $bCustomer['uuid'])->first();

            if ($customer) {
                $customer->update($bCustomer);
            } else {
                Customer::create($bCustomer);
            }
        }
        // Log::info("Finish Getting Customer Data From LTN Branch!");

        //MLM
        foreach ($mawlamyineCustomer as $bCustomer) {
            //Add or Update
            $customer = Customer::where('uuid', $bCustomer['uuid'])->first();

            if ($customer) {
                $customer->update($bCustomer);
            } else {
                Customer::create($bCustomer);
            }
        }
        // Log::info("Finish Getting Customer Data From MLM Branch!");

        //SAT
        foreach ($satsanCustomer as $bCustomer) {
            //Add or Update
            $customer = Customer::where('uuid', $bCustomer['uuid'])->first();

            if ($customer) {
                $customer->update($bCustomer);
            } else {
                Customer::create($bCustomer);
            }
        }
        // Log::info("Finish Getting Customer Data From SAT Branch!");

        //TPW
        foreach ($tampawadyCustomer as $bCustomer) {
            //Add or Update
            $customer = Customer::where('uuid', $bCustomer['uuid'])->first();

            if ($customer) {
                $customer->update($bCustomer);
            } else {
                Customer::create($bCustomer);
            }
        }
        // Log::info("Finish Getting Customer Data From TPW Branch!");

        //TMN
        foreach ($terminalCustomer as $bCustomer) {
            //Add or Update
            $customer = Customer::where('uuid', $bCustomer['uuid'])->first();

            if ($customer) {
                $customer->update($bCustomer);
            } else {
                Customer::create($bCustomer);
            }
        }
        // Log::info("Finish Getting Customer Data From TMN Branch!");

        //TKP
        foreach ($theikpanCustomer as $bCustomer) {
            //Add or Update
            $customer = Customer::where('uuid', $bCustomer['uuid'])->first();

            if ($customer) {
                $customer->update($bCustomer);
            } else {
                Customer::create($bCustomer);
            }
        }
        // Log::info("Finish Getting Customer Data From TKP Branch!");

        //SDG
        foreach ($southdagonCustomer as $bCustomer) {
            //Add or Update
            $customer = Customer::where('uuid', $bCustomer['uuid'])->first();

            if ($customer) {
                $customer->update($bCustomer);
            } else {
                Customer::create($bCustomer);
            }
        }
        //Shwe Pyi Thar
        foreach ($shwepyitharCustomer as $bCustomer) {
            //Add or Update
            $customer = Customer::where('uuid', $bCustomer['uuid'])->first();

            if ($customer) {
                $customer->update($bCustomer);
            } else {
                Customer::create($bCustomer);
            }
        }
           //Bago
           foreach ($bagoCustomer as $bCustomer) {
            //Add or Update
            $customer = Customer::where('uuid', $bCustomer['uuid'])->first();

            if ($customer) {
                $customer->update($bCustomer);
            } else {
                Customer::create($bCustomer);
            }
        }
        // Log::info("Finish Getting Customer Data From SDG Branch!");

        // Log::info("Finish Getting Customer Data From Branch!");

        //Get Ticket Header
        $ayetharyarTicketHeader = AyeTharyarTicketHeader::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $eastdagonTicketHeader = EastDagonTicketHeader::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $hlaingtharyarTicketHeader = HlaingTharyarTicketHeader::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $lanthitTicketHeader = LanthitTicketHeader::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $mawlamyineTicketHeader = MawlamyineTicketHeader::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $satsanTicketHeader = SatsanTicketHeader::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $tampawadyTicketHeader = TampawadyTicketHeader::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $terminalTicketHeader = TerminalMTicketHeader::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $theikpanTicketHeader = TheikPanTicketHeader::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $southdagonTicketHeader = SouthDagonTicketHeader::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $shwepyitharTicketHeader = ShwePyiTharTicketHeader::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $bagoTicketHeader = BagoTicketHeader::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();

        $branchTicketHeaders = array_merge(
            $ayetharyarTicketHeader,
            $eastdagonTicketHeader,
            $hlaingtharyarTicketHeader,
            $lanthitTicketHeader,
            $mawlamyineTicketHeader,
            $satsanTicketHeader,
            $tampawadyTicketHeader,
            $terminalTicketHeader,
            $theikpanTicketHeader,
            $southdagonTicketHeader,
            $shwepyitharTicketHeader,
            $bagoTicketHeader
        );

        foreach ($branchTicketHeaders as $bTicketHeader) {
            //Add or Update
            $ticketheader = TicketHeader::where('uuid', $bTicketHeader['uuid'])->first();
            if ($ticketheader) {
                $ticketheader->update($bTicketHeader);
            } else {
                TicketHeader::create($bTicketHeader);
            }
        }
        // Log::info("Finish Getting Ticket Header Data From Branch!");

        //Get Ticket Header Invoice
        $ayetharyarTicketHeaderInvoice = AyeTharyarTicketHeaderInvoice::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $eastdagonTicketHeaderInvoice = EastDagonTicketHeaderInvoice::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $hlaingtharyarTicketHeaderInvoice = HlaingTharyarTicketHeaderInvoice::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $lanthitTicketHeaderInvoice = LanthitTicketHeaderInvoice::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $mawlamyineTicketHeaderInvoice = MawlamyineTicketHeaderInvoice::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $satsanTicketHeaderInvoice = SatsanTicketHeaderInvoice::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $tampawadyTicketHeaderInvoice = TampawadyTicketHeaderInvoice::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $terminalTicketHeaderInvoice = TerminalMTicketHeaderInvoice::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $theikpanTicketHeaderInvoice = TheikPanTicketHeaderInvoice::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $southdagonTicketHeaderInvoice = SouthDagonTicketHeaderInvoice::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $shwepyitharTicketHeaderInvoice = ShwePyiTharTicketHeaderInvoice::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $bagoTicketHeaderInvoice = BagoTicketHeaderInvoice::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();

        $branchTicketHeaderInvoices = array_merge(
            $ayetharyarTicketHeaderInvoice,
            $eastdagonTicketHeaderInvoice,
            $hlaingtharyarTicketHeaderInvoice,
            $lanthitTicketHeaderInvoice,
            $mawlamyineTicketHeaderInvoice,
            $satsanTicketHeaderInvoice,
            $tampawadyTicketHeaderInvoice,
            $terminalTicketHeaderInvoice,
            $theikpanTicketHeaderInvoice,
            $southdagonTicketHeaderInvoice,
            $shwepyitharTicketHeaderInvoice,
            $bagoTicketHeaderInvoice
        );

        foreach ($branchTicketHeaderInvoices as $bTicketHeaderInvoice) {
            //Add or Update
            $ticketheaderInvoice = TicketHeaderInvoice::where('uuid', $bTicketHeaderInvoice['uuid'])->where('ticket_header_uuid', $bTicketHeaderInvoice['ticket_header_uuid'])->first();
            if ($ticketheaderInvoice) {
                $ticketheaderInvoice->update($bTicketHeaderInvoice);
            } else {
                TicketHeaderInvoice::create($bTicketHeaderInvoice);
            }
        }
        // Log::info("Finish Getting Ticket Header Invoice Data From Branch!");

        //Get Ticket Header Step Sale
        $ayetharyarTicketHeaderStepSale = AyeTharyarTicketHeaderStepSale::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $eastdagonTicketHeaderStepSale = EastDagonTicketHeaderStepSale::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $hlaingtharyarTicketHeaderStepSale = HlaingTharyarTicketHeaderStepSale::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $lanthitTicketHeaderStepSale = LanthitTicketHeaderStepSale::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $mawlamyineTicketHeaderStepSale = MawlamyineTicketHeaderStepSale::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $satsanTicketHeaderStepSale = SatsanTicketHeaderStepSale::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $tampawadyTicketHeaderStepSale = TampawadyTicketHeaderStepSale::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $terminalTicketHeaderStepSale = TerminalMTicketHeaderStepSale::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $theikpanTicketHeaderStepSale = TheikPanTicketHeaderStepSale::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $southdagonTicketHeaderStepSale = SouthDagonTicketHeaderStepSale::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $shwepyitharTicketHeaderStepSale = ShwePyiTharTicketHeaderStepSale::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $bagoTicketHeaderStepSale = BagoTicketHeaderStepSale::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();

        $branchTicketHeaderStepSales = array_merge(
            $ayetharyarTicketHeaderStepSale,
            $eastdagonTicketHeaderStepSale,
            $hlaingtharyarTicketHeaderStepSale,
            $lanthitTicketHeaderStepSale,
            $mawlamyineTicketHeaderStepSale,
            $satsanTicketHeaderStepSale,
            $tampawadyTicketHeaderStepSale,
            $terminalTicketHeaderStepSale,
            $theikpanTicketHeaderStepSale,
            $southdagonTicketHeaderStepSale,
            $shwepyitharTicketHeaderStepSale,
            $bagoTicketHeaderStepSale
        );

        foreach ($branchTicketHeaderStepSales as $bTicketHeaderStepSale) {
            //Add or Update
            $ticketheaderStepSale = TicketHeaderStepSale::where('uuid', $bTicketHeaderStepSale['uuid'])->where('ticket_header_uuid', $bTicketHeaderStepSale['ticket_header_uuid'])->first();
            if ($ticketheaderStepSale) {
                $ticketheaderStepSale->update($bTicketHeaderStepSale);
            } else {
                TicketHeaderStepSale::create($bTicketHeaderStepSale);
            }
        }
        // Log::info("Finish Getting Ticket Header Step Sale Data From Branch!");

        //Get Ticket
        $ayetharyarTicket = AyeTharyarTicket::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $eastdagonTicket = EastDagonTicket::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $hlaingtharyarTicket = HlaingTharyarTicket::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $lanthitTicket = LanthitTicket::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $mawlamyineTicket = MawlamyineTicket::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $satsanTicket = SatsanTicket::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $tampawadyTicket = TampawadyTicket::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $terminalTicket = TerminalMTicket::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $theikpanTicket = TheikPanTicket::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $southdagonTicket = SouthDagonTicket::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $shwepyitharTicket = ShwePyiTharTicket::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();
        $bagoTicket = BagoTicket::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();

        $branchTickets = array_merge(
            $ayetharyarTicket,
            $eastdagonTicket,
            $hlaingtharyarTicket,
            $lanthitTicket,
            $mawlamyineTicket,
            $satsanTicket,
            $tampawadyTicket,
            $terminalTicket,
            $theikpanTicket,
            $southdagonTicket,
            $shwepyitharTicket,
            $bagoTicket
        );

        foreach ($branchTickets as $branchTicket) {
            //Add or Update
            $ticketheaderInvoice = Ticket::where('uuid', $branchTicket['uuid'])->first();
            if ($ticketheaderInvoice) {
                $ticketheaderInvoice->update($branchTicket);
            } else {
                Ticket::create($branchTicket);
            }
        }
        // Log::info("Finish Getting Ticket Data From Branch!");

        //Get Customer Data
        $customers = HOCustomer::whereDate('created_at', '=', date('Y-m-d'))->get()->toarray();

        foreach ($customers as $bCustomer) {
            //Add or Update
            $customer = AyeTharyarCustomer::where('uuid', $bCustomer['uuid'])->first();
            if ($customer) {
                $customer->update($bCustomer);
            } else {
                AyeTharyarCustomer::create($bCustomer);
            }
            // Log::info("Finish Sending Customer Data To ATY Branch!");

            $customer = EastDagonCustomer::where('uuid', $bCustomer['uuid'])->first();
            if ($customer) {
                $customer->update($bCustomer);
            } else {
                EastDagonCustomer::create($bCustomer);
            }
            // Log::info("Finish Sending Customer Data To EDG Branch!");

            $customer = HlaingTharyarCustomer::where('uuid', $bCustomer['uuid'])->first();
            if ($customer) {
                $customer->update($bCustomer);
            } else {
                HlaingTharyarCustomer::create($bCustomer);
            }
            // Log::info("Finish Sending Customer Data To HTY Branch!");

            $customer = LanthitCustomer::where('uuid', $bCustomer['uuid'])->first();
            if ($customer) {
                $customer->update($bCustomer);
            } else {
                LanthitCustomer::create($bCustomer);
            }
            // Log::info("Finish Sending Customer Data To LTN Branch!");

            $customer = MawlamyineCustomer::where('uuid', $bCustomer['uuid'])->first();
            if ($customer) {
                $customer->update($bCustomer);
            } else {
                MawlamyineCustomer::create($bCustomer);
            }
            // Log::info("Finish Sending Customer Data To MLM Branch!");

            $customer = SatsanCustomer::where('uuid', $bCustomer['uuid'])->first();
            if ($customer) {
                $customer->update($bCustomer);
            } else {
                SatsanCustomer::create($bCustomer);
            }
            // Log::info("Finish Sending Customer Data To SAT Branch!");

            $customer = TampawadyCustomer::where('uuid', $bCustomer['uuid'])->first();
            if ($customer) {
                $customer->update($bCustomer);
            } else {
                TampawadyCustomer::create($bCustomer);
            }
            // Log::info("Finish Sending Customer Data To TPW Branch!");

            $customer = TerminalMCustomer::where('uuid', $bCustomer['uuid'])->first();
            if ($customer) {
                $customer->update($bCustomer);
            } else {
                TerminalMCustomer::create($bCustomer);
            }
            // Log::info("Finish Sending Customer Data To TMN Branch!");

            $customer = TheikPanCustomer::where('uuid', $bCustomer['uuid'])->first();
            if ($customer) {
                $customer->update($bCustomer);
            } else {
                TheikPanCustomer::create($bCustomer);
            }
            // Log::info("Finish Sending Customer Data To TPK Branch!");

            $customer = SouthDagonCustomer::where('uuid', $bCustomer['uuid'])->first();
            if ($customer) {
                $customer->update($bCustomer);
            } else {
                SouthDagonCustomer::create($bCustomer);
            }

            $customer = ShwePyiTharCustomer::where('uuid', $bCustomer['uuid'])->first();
            if ($customer) {
                $customer->update($bCustomer);
            } else {
                ShwePyiTharCustomer::create($bCustomer);
            }

            $customer = BagoCustomer::where('uuid', $bCustomer['uuid'])->first();
            if ($customer) {
                $customer->update($bCustomer);
            } else {
                BagoCustomer::create($bCustomer);
            }
            // Log::info("Finish Sending Customer Data To SDG Branch!");
        }
        // Log::info("Finish Sending Customer Data To Branch!");

        Log::info("Cron is working fine !");
        return Command::SUCCESS;
    }
}
