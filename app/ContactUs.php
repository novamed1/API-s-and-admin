<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Input;
use Carbon\Carbon;

class ContactUs extends Model
{
    protected $table = 'tbl_contact_us';
    /**
     * Get All ContactUs Data from DataBase
     */
    public function AllContacts($limit = 0, $offset = 0, $order_by = 'tcu.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_contact_us as tcu');

        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }

        if (isset($cond['search']) && $cond['search'] != '') {

            if (isset($likeArray) && $likeArray != '') {
                $likeArrayFields = $likeArray;
            } else {
                $likeArrayFields = array('te.id');
            }
            if (isset($cond['search']['keyword']) && $cond['search']['keyword'] != '') {
                if (!empty($likeArray)) {

                    $flag = true;
                    $like = '';
                    foreach ($likeArray as $value) {
                        if ($flag) {

                            $like .= $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
                        } else {
                            $like .= " OR " . $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";

                        }
                        $flag = false;
                    }
                    $query->whereRaw('(' . $like . ')');
                }
            }
        }
        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }

        return $result;
    }
    /**
     *Display All ContactUs Data To view
     */
    public function AllContactsGrid($limit = 0, $offset = 0, $order_by = 'tcu.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_contact_us as tcu');

        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }

        if (isset($cond['search']) && $cond['search'] != '') {


            if (!empty($cond['search'])) {

                $flag = true;
                $like = '';
                foreach ($cond['search'] as $key=>$value) {
                    if($value) {
                        if ($flag) {

                            $like .= $key . " LIKE '%" . trim($value) . "%' ";
                        } else {
                            $like .= " OR " . $key . " LIKE '%" . trim($value) . "%' ";
                        }
                        $flag = false;
                    }
                }
                if(array_filter($cond['search'])) {
                    $query->whereRaw('(' . $like . ')');
                }
            }
        }

        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }

        return $result;
    }
    /**
     * Check Specified ContactUs Id exist or Not In Database
     *
     * @param  int  $id
     */
    public function getContactDetails($contactId){
        $result = DB::table('tbl_contact_us')->where('id','=',$contactId)->first();

        return $result;
    }
    /**
     * Remove the specified ConatctUs from storage.
     *
     * @param  int  $id
     */
    public function deleteContact($id)
    {

        $delete = DB::table('tbl_contact_us')->where('id', $id)->delete();

        if ($delete == 1) {
            return true;
        } else {
            return false;
        }

    }

}
