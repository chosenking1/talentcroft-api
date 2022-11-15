<?php

namespace App\Repositories;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BaseRepository{

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function create(array $data):object
    {
        return $this->model->create($data);
    }

    /**
     * Update  Model
     * @param object $modelObject
     * @param array $data
     * @return Model object
     */
    public function update(object $modelObject, array $data)
    {
        $modelObject->update($data);
        return $modelObject;
    }

    /**
     * Returns object by id
     * @return Model object
     */
    public function get(int $id){
        return $this->model->findOrFail($id);
    }


    /**
     * Returns all object data
     * @return Collection
     */
    public function all()
    {
        return $this->model::get();
    }


    /**
     * Deletes object from collection
     * @return null
     */

     public function delete(int $id){
         return $this->get($id)->delete();
     }

     /**
      * returns paginated value
      * @return Collection
      */
     public function paginate(int $count){
        return $this->model::paginate($count);
     }

     public function firstOrCreate(array $unique, array $other = [])
     {
        return $this->model->firstOrCreate($unique, $other);
     }

     public function count()
     {
         return $this->model->count();
     }
}
