<?php

namespace App\View\Components;

use Illuminate\View\Component;

class img extends Component
{
    public $_src="";
    public $src = "";
    public $src_url = "";
    public $width = 0;
    public $height = 0;
    public $srcset = "";

    protected $except = ['_src'];
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($src="", $width=1, $height=1, $srcset="")
    {
        $this->_src = $src;
        $this->width = $width;
        $this->height = $height;
        $this->srcset = $srcset;

        if ( filter_var($src, FILTER_VALIDATE_URL)!==FALSE ) {

            $this->src_url = $src;

        } else {
            $this->src_url = asset( 'img/'.$src );

            if ($width===1 || $height===1 ) {

                $public_path = public_path('img/'.$src);

                if ( file_exists( $public_path ) ) {

                    $info = getimagesize($public_path);

                    if ($info && is_array($info) ) {
                        $this->width = !empty($info[0]) ? $info[0] : 1;
                        $this->height = !empty($info[1]) ? $info[1] : 1;
                    }
                }
            }
        }

        $this->src = 'data:image/svg+xml;charset=UTF-8,'.rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$this->width.' '.$this->height.'"></svg>');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.img');
    }
}
