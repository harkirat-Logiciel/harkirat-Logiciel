<?php
namespace Traits;
// use Carbon\Carbon;
// use DB;
trait SortableTrait
{
    public function sort($add)
    {
        $sort_by = \input::get('sort_by') ?  :'id';
        $sort_order = \input::get('sort_order') ? :'asc';
        $add = $add->orderBy($sort_by, $sort_order);
        return $add;
    }
}
?>