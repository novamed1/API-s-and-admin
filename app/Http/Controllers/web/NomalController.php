<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;

use App\Models\Sentinel\User;

use Illuminate\Http\Request;
use Session;
use DB;
use Input;
use Response;
use Validator;
use View;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;


//use Request;

class NomalController extends Controller
{
     public function __construct()
    {
       

    }
}
