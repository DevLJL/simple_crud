<?php

namespace App\Shared\Repository\Eloquent;

use App\Shared\Entity\BaseEntity;
use App\Shared\Repository\BaseRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

abstract class BaseRepositoryEloquent implements BaseRepositoryInterface
{
  const FILTER_FIELDS_DEFAULT = ['page', 'limit', 'columns', 'order_by'];
  const REMOVE_THIS_COLUMNS_ON_UPDATE = ['created_at', 'created_by_user_id', 'updated_at',];
  const REMOVE_THIS_FROM_INDEX_RESULT = ['first_page_url', 'last_page_url', 'links', 'next_page_url', 'path', 'prev_page_url'];
  protected bool $inTransaction = true;
  public function __construct(private Model $model){}  
  
  abstract public function defaultQuery(?bool $defaultRelations = true): Builder;
  abstract public function modelToEntity(Model $model): BaseEntity;

  public function getIndex(Builder $query, ?string $columns = '', string $order_by = '', ?int $limit = 0, ?int $page = 0): array 
  {
    // Limite por página
    if ($limit <= 0) $limit = 10;
    if ($page <= 0) $page = 1;

    // Colunas
    if (strlen(trim($columns)) > 0){
      $columns = explode(",", $columns);
      $columns = array_map('trim', $columns);
      $query->select($columns);
    }
    
    // order_by
    if (strlen(trim($order_by)) > 0){
      if (strpos($order_by, "desc") !== false) {
        $order_by = explode("desc", $order_by);      
        $query->orderBy(trim($order_by[0]), 'desc');
      } else {
        $query->orderBy(trim($order_by));
      }
    }

    // Paginar e retornar array
    $result = $query->paginate($limit, ['*'], 'page', $page)->toArray();
    foreach (self::REMOVE_THIS_FROM_INDEX_RESULT as $item) {
      unset($result[$item]);
    }    
    return $result;
  }

  public function store(BaseEntity $entity): int
  {
    $data = objectToArray($entity);    
    $executeStore = function ($data) {
      $modelStored = $this->model->create($data);
      return $modelStored->id;
    };

    return match ($this->inTransaction) {
      true => DB::transaction(fn () => $executeStore($data)),
      false => $executeStore($data),
    };
  }

  public function show(int $id): ?BaseEntity
  {
    return ($modelFound = $this->findById($id))
      ? $this->modelToEntity($modelFound)
      : null;        
  }

  public function update(int $id, BaseEntity $entity): bool
  {
    $data = Arr::except(objectToArray($entity), self::REMOVE_THIS_COLUMNS_ON_UPDATE);
    $data['id'] = $id;

    $executeUpdate = function ($data) use ($id) {
      $modelFound = $this->model->find($id);
      throw_if(!$modelFound, new Exception(trans('validation.record_not_found').' ID: '.$id));
      $modelFound->update($data);

      return true;
    };

    return match ($this->inTransaction) {
        true => DB::transaction(fn () => $executeUpdate($data)),
        false => $executeUpdate($data),
    };
  }

  protected function findById(int $id): ?Model
  {
    return $this->defaultQuery()
      ->where($this->model->getTable().'.id', $id)
      ->first();
  }

  public function destroy(int $id): bool
  {
    $this->model->destroy($id);
    return true;
  }

  /**
   * Habilitar/Desabilitar Transação de Dados
   *
   * @param boolean
   * @return self
   */
  public function setTransaction(bool $value): void {
    $this->inTransaction = $value;
  }

  public function startTransaction(): void
  {
    DB::beginTransaction();
  }

  public function commitTransaction(): void
  {
    DB::commit();
  }

  public function rollBackTransaction(): void
  {
    DB::rollBack();
  }
}