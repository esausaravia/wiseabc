<?php
return [
  'edad_labels' => [
    3 => '3-5',
    6 => '6-11',
    12 => '12-17',
    18 => '18+'
  ],

  'edad_cats' => [
    5 => 'Niños',
    11 => 'Niños2',
    15 => 'Adolecentes',
    18 => 'Adultos'
  ],

  'nivel_labels' => [
    1=>'A1',
    2=>'A2',
    3=>'B1',
    4=>'B2',
    5=>'C1',
    6=>'C2',
    7=>'D1',
    8=>'D2',
    9=>'D3'
  ],

  'ritmo_labels' => [
    1 => 'Relax',
    2 => 'Medio',
    3 => 'Intenso',
    4 => 'Intenso+'
  ],

  'suscripcion_labels' => [
    1 => 'Grupal Relax',
    2 => 'Grupal Medio',
    3 => 'Grupal Intenso',
    4 => 'Grupal Intenso+',
    5 => 'Individual Relax',
    6 => 'Individual Medio',
    7 => 'Individual Intenso',
    8 => 'Individual Intenso+'
  ],

  'suscripciones' => [
    0 => [
      'name'=>'Prueba',
      'tipo'=>1,
      'ritmo'=>1
    ],
    1 => [
      'name'=>'Grupal Relax',
      'tipo'=>1,
      'ritmo'=>1
    ],
    2 => [
      'name'=>'Grupal Medio',
      'tipo'=>1,
      'ritmo'=>2
    ],
    3 => [
      'name'=>'Grupal Intenso',
      'tipo'=>1,
      'ritmo'=>3
    ],
    4 => [
      'name'=>'Grupal Intenso+',
      'tipo'=>1,
      'ritmo'=>4
    ],
    5 => [
      'name'=>'Individual Relax',
      'tipo'=>2,
      'ritmo'=>1
    ],
    6 => [
      'name'=>'Individual Medio',
      'tipo'=>2,
      'ritmo'=>2
    ],
    7 => [
      'name'=>'Individual Intenso',
      'tipo'=>2,
      'ritmo'=>3
    ],
    8 => [
      'name'=>'Individual Intenso+',
      'tipo'=>2,
      'ritmo'=>4
    ],
  ],

  'weekdays' => [
    '','lun','mar','mie','jue','vie','sab','dom'
  ],

  'en_weekdays' => [
    '','monday','tuesday','wednesday','thursday','friday','saturday','sunday'
  ],

  'horarios_labels' => [
    6=>'6:00',
    7=>'7:00',
    8=>'8:00',
    9=>'9:00',
    10=>'10:00',
    11=>'11:00',
    12=>'12:00',
    13=>'13:00',
    14=>'14:00',
    15=>'15:00',
    16=>'16:00',
    17=>'17:00',
    18=>'18:00',
    19=>'19:00',
    20=>'20:00',
    21=>'21:00'
  ],

  'estatus' =>[
    'disabled'=>'Desactivado',
    'active'=>'Activo',
    'suscribed'=>'Suscrito',
  ],
  'classrooms_exist' => [
    '2' => 'Sin clases',
    '1' => 'Con clases',
  ],
  'type_classroom' => [
    '1' => 'Grupal',
    '2' => 'Individual',
  ],
  'rhythm' => [
    '1' => 'Relax',
    '2' => 'Medio',
    '3' => 'Intenso',
    '4' => 'Intenso+',
  ],

];
