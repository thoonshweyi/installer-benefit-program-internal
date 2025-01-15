<?php

namespace App\Models;

use App\Models\POS101\Pos101Province;
use App\Models\POS102\Pos102Province;
use App\Models\POS103\Pos103Province;
use App\Models\POS104\Pos104Province;
use App\Models\POS106\Pos106Province;
use App\Models\POS107\Pos107Province;
use App\Models\POS108\Pos108Province;
use App\Models\POS110\Pos110Province;
use App\Models\POS112\Pos112Province;
use App\Models\POS113\Pos113Province;
use App\Models\POS114\Pos114Province;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use App\Models\{Amphur,Province};


class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'customer_id',
        'titlename',
        'firstname',
        'lastname',
        'phone_no',
        'nrc_no',
        'nrc_name',
        'nrc_short',
        'nrc_number',
        'passport',
        'email',
        'phone_no_2',
        'national_id',
        'member_no',
        'amphur_id',
        'province_id',
        'address',
        'customer_no',
        'customer_type',
    ];

    public function NRCNumbers()
    {
        return $this->belongsTo('App\Models\NRCNumber', 'nrc_no', 'id');
    }

    public function NRCNames()
    {
        return $this->belongsTo('App\Models\NRCName', 'nrc_name', 'id');
    }

    public function NRCNaings()
    {
        return $this->belongsTo('App\Models\NRCNaing', 'nrc_short', 'id');
    }

    public function amphurs()
    {

        return $this->belongsTo(Amphur::class,'amphur_id','amphur_id');

    }

    public function provinces()
    {

        return $this->belongsTo(Province::class,'province_id','province_id');

    }

    public function provinces()
    {
        $currentURL = URL::current();
        // dd($currentURL);
        //Lanthit
        if (str_contains($currentURL, '192.168.3.242'))

        {
            return $this->belongsTo(Pos101Province::class, 'province_id', 'province_id');
        }
        // Theik pan
        if (str_contains($currentURL, '192.168.21.242'))

        {
            return $this->belongsTo(Pos102Province::class, 'province_id', 'province_id');
        }
        //Sat San
        if (str_contains($currentURL, '192.168.11.242'))

        {
            return $this->belongsTo(Pos103Province::class, 'province_id', 'province_id');
        }
        //East Dagon
        if (str_contains($currentURL, '192.168.16.242') || str_contains($currentURL, '192.168.2.23'))

        {
            return $this->belongsTo(Pos104Province::class, 'province_id', 'province_id');
        }
         // Mawlamyine
         if (str_contains($currentURL, '192.168.31.242'))

         {
             return $this->belongsTo(POS105Province::class, 'province_id', 'province_id');
         }

          // Tampawady
          if (str_contains($currentURL, '192.168.25.242'))

          {
              return $this->belongsTo(Pos106Province::class, 'province_id', 'province_id');
          }

           // Hlaingtharyar
           if (str_contains($currentURL, '192.168.36.242'))

           {
               return $this->belongsTo(Pos107Province::class, 'province_id', 'province_id');
           }

            // Ayetharyar
            if (str_contains($currentURL, '192.168.41.242'))

            {
                return $this->belongsTo(Pos108Province::class, 'province_id', 'province_id');
            }

             // Bago
             if (str_contains($currentURL, '192.168.61.242'))

             {
                 return $this->belongsTo(Pos110Province::class, 'province_id', 'province_id');
             }
             //PTMN
             if (str_contains($currentURL, '192.168.46.242'))

             {
                 return $this->belongsTo(Pos112Province::class, 'province_id', 'province_id');
             }
             //South Dagon
             if (str_contains($currentURL, '192.168.51.243'))

             {
                 return $this->belongsTo(Pos113Province::class, 'province_id', 'province_id');
             }
             //Shwepyithar
             if (str_contains($currentURL, '192.168.56.242'))

             {
                 return $this->belongsTo(Pos114Province::class, 'province_id', 'province_id');
             }

    }

    public function POS101amphurs()
    {
        return $this->belongsTo('App\Models\POS101\Pos101Amphur', 'amphur_id', 'amphur_id');
    }

    public function POS101provinces()
    {
        return $this->belongsTo('App\Models\POS101\Pos101Province', 'province_id', 'province_id');
    }

    public function POS102amphurs()
    {
        return $this->belongsTo('App\Models\POS102\Pos102Amphur', 'amphur_id', 'amphur_id');
    }

    public function POS102provinces()
    {
        return $this->belongsTo('App\Models\POS102\Pos102Province', 'province_id', 'province_id');
    }

    public function POS103amphurs()
    {
        return $this->belongsTo('App\Models\POS103\Pos103Amphur', 'amphur_id', 'amphur_id');
    }

    public function POS103provinces()
    {
        return $this->belongsTo('App\Models\POS103\Pos103Province', 'province_id', 'province_id');
    }

    public function POS104amphurs()
    {
        return $this->belongsTo('App\Models\POS104\Pos104Amphur', 'amphur_id', 'amphur_id');
    }

    public function POS104provinces()
    {
        return $this->belongsTo('App\Models\POS104\Pos104Province', 'province_id', 'province_id');
    }

    public function POS105amphurs()
    {
        return $this->belongsTo('App\Models\POS105\Pos105Amphur', 'amphur_id', 'amphur_id');
    }

    public function POS105provinces()
    {
        return $this->belongsTo('App\Models\POS105\Pos105Province', 'province_id', 'province_id');
    }

    public function POS106amphurs()
    {
        return $this->belongsTo('App\Models\POS106\Pos106Amphur', 'amphur_id', 'amphur_id');
    }

    public function POS106provinces()
    {
        return $this->belongsTo('App\Models\POS106\Pos106Province', 'province_id', 'province_id');
    }

    public function POS107amphurs()
    {
        return $this->belongsTo('App\Models\POS107\Pos107Amphur', 'amphur_id', 'amphur_id');
    }

    public function POS107provinces()
    {
        return $this->belongsTo('App\Models\POS107\Pos107Province', 'province_id', 'province_id');
    }

    public function POS108amphurs()
    {
        return $this->belongsTo('App\Models\POS108\Pos108Amphur', 'amphur_id', 'amphur_id');
    }

    public function POS108provinces()
    {
        return $this->belongsTo('App\Models\POS108\Pos108Province', 'province_id', 'province_id');
    }

    public function POS112amphurs()
    {
        return $this->belongsTo('App\Models\POS112\Pos112Amphur', 'amphur_id', 'amphur_id');
    }

    public function POS112provinces()
    {
        return $this->belongsTo('App\Models\POS112\Pos112Province', 'province_id', 'province_id');
    }

    public function POS113provinces()
    {
        return $this->belongsTo('App\Models\POS113\Pos113Province', 'province_id', 'province_id');
    }
    public function POS110provinces()
    {
        return $this->belongsTo('App\Models\POS110\Pos110Province', 'province_id', 'province_id');
    }
    public function POS110amphurs()
    {
        return $this->belongsTo('App\Models\POS110\Pos110Amphur', 'amphur_id', 'amphur_id');
    }
}
