<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class WiseabcController extends Controller
{
    /**
     * Convierte horas
     * @param array $horarios [9,10,11]
     * @param string $timezone '-0400'
     * @param string $fromTz '-0600'
     * @return array [11,12,13]
     */
    public static function transformHorariosTimezone($horarios=[], $timezone, $fromTz='-0600')
    {
        if ( empty($horarios) || !is_array($horarios) )
        {
            return array();
        }

        if ( empty($timezone) || !is_string($timezone) )
        {
            $user = Auth::user();
            if ( !is_object($user) )
            {
                return array();
            }

            $user = User::find( $user->id );
            if ( !is_object($user) )
            {
                return array();
            }
            $timezone = $user->getMeta('timezone');
        }

        if ( empty($fromTz) || !is_string($fromTz) )
        {
            $fromTz='-0600';
        }

        $dt1 = Carbon::createMidnightDate(2023, 1, 1, $fromTz);
        $dt2 = Carbon::createMidnightDate(2023, 1, 1, $timezone);
        $diff = $dt2->diffInHours( $dt1, false );

        $return = array();
        foreach( $horarios AS $dia=>$arrHrs )
        {
            if ( !is_array($arrHrs) )
            {
                if ( preg_match('/^\d+$/', $arrHrs) )
                {
                    $return[($dia)] = (int)$arrHrs + $diff;
                }

                continue;
            }

            if ( empty($return[($dia)]) || !is_array($return[($dia)]) )
            {
                $return[($dia)] = array();
            }

            foreach( $arrHrs AS $hr ) {
                $return[($dia)][] = (int)$hr + $diff;
            }
        }
        return $return;
    }
}
