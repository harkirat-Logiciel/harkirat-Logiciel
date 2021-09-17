<?php
namespace Traits;
// use Carbon\Carbon;
// use DB;
use Comment;
trait SortableTrait
{
    public function sort($add ,$limit)
    {
        $sort_by = \input::get('sort_by') ?  :'post.id';
        $sort_order = \input::get('sort_order') ? :'desc';
        $add = Comment::orderBy($sort_by, $sort_order)->Paginate($limit);
        return $add;
    }
}
?>