<?php

namespace App\Models\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

abstract class AbstractEloquentRepository
{

    /** @var Model|builder|QueryBuilder */
    public $model;

    /**
     * Get all of the models from the database.
     *
     * @param string $direction
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($direction = 'DESC')
    {
        return $this->model->orderBy('created_at', $direction)
                           ->orderBy('id', $direction)
                           ->get();
    }

    /**
     * Get all of the models from the database use paginate.
     *
     * @param int    $count
     *
     * @param string $direction
     *
     * @return mixed
     */
    public function allPaginated($count = 15, $direction = 'DESC')
    {
        return $this->model->orderBy('created_at', $direction)
                           ->orderBy('id', $direction)
                           ->paginate($count);
    }

    /**
     * 比較新資料與舊資料，把新資料取代舊資料，並把多餘的舊資料刪除。
     *
     * @param array            $newData
     * @param array|Collection $oldData
     * @param bool             $newDataHasId
     *
     * @return array
     */
    public function compareAndSave($newData, $oldData, $newDataHasId = true)
    {
        $models = [];

        if ($newDataHasId) {
            $oldDataId = is_array($oldData) ? array_column($oldData, 'id') : $oldData->pluck('id')
                                                                                     ->all();
            $newDataId = array_column($newData, 'id');
            $deleteIds = array_diff($oldDataId, $newDataId);
            $this->model->destroy($deleteIds);

            foreach ($newData as $key => $newDatum) {
                $models[$key] = $newData[$key]['id'] ? $this->model->find($newData[$key]['id']) : null;
                $models[$key] = self::createOrUpdate($newDatum, $models[$key]);
            }
        } else {
            $newDataCount = count($newData);
            $oldDataCount = count($oldData);

            if ($newDataCount > $oldDataCount) {
                foreach ($newData as $key => $newDatum) {
                    if ($key < $oldDataCount) {
                        $models[$key] = $this->model->find($oldData[$key]->id);
                    } else {
                        $models[$key] = null;
                    }

                    $models[$key] = self::createOrUpdate($newDatum, $models[$key]);
                }
            } else {
                foreach ($oldData as $key => $oldDatum) {
                    if ($key >= $newDataCount) {
                        $this->model->find($oldDatum->id)
                                    ->delete();
                        continue;
                    }

                    $models[$key] = $this->model->find($oldDatum->id);
                    $models[$key] = self::createOrUpdate($newData[$key], $models[$key]);
                }
            }
        }

        return $models;
    }

    /**
     * 新增或修改資料。
     *
     * @param array          $data
     * @param Model|int|null $model
     *
     * @return Model|null
     */
    public function createOrUpdate($data, $model = null)
    {
        if (!$model) {
            $model = $this->getNewInstance($data);
        } elseif ($model instanceof Model) {
            $model->fill($data);
        } else {
            /** @var Model $model */
            $model = $this->requireById($model);
            $model->fill($data);
        }

        $this->storeEloquentModel($model);

        return $model;
    }

    /**
     * 刪除資料。
     *
     * @param $ids
     *
     * @return int
     */
    public function destroy($ids)
    {
        return $this->model->destroy($ids);
    }

    /**
     * Find a model by its primary key.
     *
     * @param       $id
     * @param array $columns
     *
     * @return Collection|Model|null
     */
    public function find($id, $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    /**
     * Find  models by its primary key.
     *
     * @param array $ids
     * @param array $columns
     *
     * @return Collection|Model|null
     */
    public function findMany($ids ,  $columns = ['*'])
    {
        return $this->model->findMany($ids, $columns);
    }

    /**
     * Find a model by its primary key with relations.
     *
     * @param       $id
     * @param array $with
     * @param array $columns
     *
     * @return Collection|Model|null
     */
    public function findWith($id , $with = [], $columns = ['*'])
    {
        return $this->model->with($with)->find($id, $columns);
    }

    /**
     * Find a model by its primary key.
     *
     * @param array      $id
     * @param array $columns
     * @return Collection|null
     */
    public function findByIds($id = [] , $with = [], $columns = ['*'])
    {
        return $this->model->with($with)->whereIn('id' , $id)->get($columns);
    }

    /**
     * Get all id of the models from the database.
     *
     * @return array
     */
    public function getAllIds() :array
    {
        return $this->model->pluck('id')
                           ->all();
    }

    /**
     * 取得 model 實體。
     *
     * @return Builder|Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Create a new instance of the given model.
     *
     * @param array $attributes
     *
     * @return Model
     */
    public function getNewInstance($attributes = [])
    {
        return $this->model->newInstance($attributes);
    }

    /**
     * Get an array with the values of a given column.
     *
     * @param        $column
     * @param null   $key
     * @param string $direction
     *
     * @return mixed
     */
    public function pluck($column, $key = null, $direction = 'DESC')
    {
        return $this->model->orderBy('created_at', $direction)
                           ->orderBy('id', $direction)
                           ->pluck($column, $key);
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param       $id
     * @param array $columns
     *
     * @return Model
     */
    public function requireById($id, $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * Save the data to the database.
     *
     * @param $data
     *
     * @return mixed
     */
    public function save($data)
    {
        if (is_array($data)) {
            $data = $this->getNewInstance($data);
        }

        return $this->storeEloquentModel($data);
    }

    /**
     * 設定發布狀態。
     *
     * @param int|Model   $model
     * @param int|boolean $active
     *
     * @return Collection|Model|null
     */
    public function setActive($model, $active)
    {
        if (!$model instanceof Model) {
            $model = $this->requireById($model);
        }

        $model->active = $active;
        $model->save();

        return $model;
    }

    /**
     * 設定 model 實體。
     *
     * @param Model|integer $model
     */
    public function setModel($model)
    {
        if ($model instanceof Model) {
            $this->model = $model;
        } else {
            $this->model = $this->requireById($model);
        }
    }

    /**
     * Save the model to the database.
     *
     * @param Model $model
     *
     * @return mixed
     */
    public function storeEloquentModel($model)
    {
        if ($model->getDirty()) {
            return $model->save();
        } else {
            return $model->touch();
        }
    }
}
