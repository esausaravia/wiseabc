<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class WiseabcController extends Controller
{
    /**
     * Convierte horas
     *
     * @param  array  $horarios [9,10,11]
     * @param  string  $timezone '-0400'
     * @param  string  $fromTz '-0600'
     * @return array [11,12,13]
     */
    public static function transformHorariosTimezone(array $horarios, string $timezone, string $fromTz = '-0600')
    {
        if (empty($horarios) || ! is_array($horarios)) {
            return [];
        }

        if (empty($timezone) || ! is_string($timezone)) {
            $user = Auth::user();
            if (! is_object($user)) {
                return [];
            }

            $user = User::find($user->id);
            if (! is_object($user)) {
                return [];
            }
            $timezone = $user->getMeta('timezone');
        }

        if (empty($fromTz) || ! is_string($fromTz)) {
            $fromTz = '-0600';
        }

        $dt1 = Carbon::createMidnightDate(2023, 1, 1, $fromTz);
        $dt2 = Carbon::createMidnightDate(2023, 1, 1, $timezone);
        $diff = $dt2->diffInHours($dt1, false);

        $return = [];
        foreach ($horarios as $dia => $arrHrs) {
            if (! is_array($arrHrs)) {
                if (preg_match('/^\d+$/', $arrHrs)) {
                    $return[($dia)] = (int) $arrHrs + $diff;
                }

                continue;
            }

            if (empty($return[($dia)]) || ! is_array($return[($dia)])) {
                $return[($dia)] = [];
            }

            foreach ($arrHrs as $hr) {
                $return[($dia)][] = (int) $hr + $diff;
            }
        }

        return $return;
    }
}
