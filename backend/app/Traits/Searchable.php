<?php
namespace App\Traits;

trait Searchable{

    public function scopeSearchable($query, $columns=[], $search=null)
    {
        $query->when($search, function($query) use($columns,$search){
            foreach($columns as $idx=>$column){
                $query->{$idx==0 ? 'where' : 'orWhere'}($column, "LIKE", "%$search%");
            }
        });
    }

    public function scopeOrderable($query, $orders='')
    {
        $orders = !empty($orders)? explode(',',$orders) : [];
 
        $query->when(count($orders)>0, function($query) use($orders){
            foreach($orders as $value){
                $value = explode(':', $value);
                $column= $value[0];
                $type  = $value[1];
                $query->orderBy($column, $type);
            }
        });
    }
}