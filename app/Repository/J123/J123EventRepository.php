<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

namespace App\Repository\J123;

use App\Model\Enums\User\Type;
use App\Model\J123\J123Event;
use App\Model\Permission\User;
use App\Repository\IRepository;
use Hyperf\Collection\Arr;
use Hyperf\Collection\Collection;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\ModelNotFoundException;
use Hyperf\Paginator\AbstractPaginator;

/**
 * Class UserRepository.
 * @extends IRepository<J123Event>
 */
final class J123EventRepository extends IRepository
{
    public function __construct(protected readonly J123Event $model) {}

//    public function findByUnameType(string $username, Type $userType = Type::SYSTEM): User
//    {
//        // @phpstan-ignore-next-line
//        return $this->model->newQuery()
//            ->where('username', $username)
//            ->where('user_type', $userType)
//            ->firstOrFail();
//    }
//
//    protected function enablePageOrderBy(): bool
//    {
//        return false;
//    }


//    public function handleOrderBy(Builder $query, $params): Builder
//    {
//        $query->orderBy('accident_number', 'desc');
//
//        // 调用父类的 handleOrderBy 方法，保留父类的排序逻辑
//        $query = parent::handleOrderBy($query, $params);
//
//
////        // 自定义的排序逻辑
////        if (isset($params['custom_sort_field'])) {
////            // 根据 custom_sort_field 参数进行额外排序
////            $customSortField = $params['custom_sort_field'];
////            $customSortDirection = $params['custom_sort_direction'] ?? 'asc';  // 默认为升序
////            $query->orderBy($customSortField, $customSortDirection);
////        }
//
//        return $query;
//    }

//    public function page(array $params = [], ?int $page = null, ?int $pageSize = null): array
//    {
//        $result = $this->perQuery($this->getQuery($params), $params)->paginate(
//            perPage: $pageSize,
//            pageName: static::PER_PAGE_PARAM_NAME,
//            page: $page,
//        );
//        if ($result instanceof AbstractPaginator) {
//            $items = $result->getCollection();
//        } else {
//            $items = Collection::make($result->items());
//        }
//        $items = $this->handleItems($items);
//        return $this->handlePage([
//            'list' => $items->toArray(),
//            'total' => $result->total(),
//        ]);
//    }


    /**
     * 根据事故编号查找事故记录
     *
     * @param string $accidentNumber 事故编号
     * @return J123Event|null 事故记录或 null
     */
    public function findOneByAccidentNumber(string $accidentNumber): ?J123Event
    {
        return $this->model->newQuery()->where('accident_number', $accidentNumber)->first();
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
         //如果参数中包含身份证号和姓名，则通过身份证号和姓名过滤相关的事故记录asdf
        if (array_key_exists('id_card_number',$params) && array_key_exists('id_card_name',$params)) {
            $query->whereIn('accident_number', function ($query) use ($params) {
                $query->select('accident_number')
                    ->from('j123_people')
                    ->where('id_number', '=', $params['id_card_number'])
                    ->where('name', '=', $params['id_card_name']);
            });
        }

//        if (array_key_exists('id_card',$params)) {
//            $query->whereIn('accident_number', function ($query) use ($params) {
//                $query->select('accident_number')
//                    ->from('j123_people')
//                    ->where('id_number', '=', $params['id_card']);
////                    ->where('name', '=', $params['name']);
//            });
//        }

        $query
            ->when(Arr::get($params, 'dsr_name'), static function (Builder $query, $dsr_name) use ($params) {
                $query->whereIn('accident_number', function ($query) use ($params) {
                    $query->select('accident_number')
                        ->from('j123_people')
                        ->where('name', '=', $params['dsr_name']);
                });
            })
            ->when(Arr::get($params, 'location'), static function (Builder $query, $location) {
                $query->where('location', 'like', '%' . $location . '%');
            })
            ->when(Arr::get($params, 'accident_number'), static function (Builder $query, $accident_number) {
                $query->where('accident_number', $accident_number);
            })
            ->when(Arr::get($params, 'accident_status_1'), static function (Builder $query, $accident_status) {
                if ($accident_status === '已完成') {
                    $query->where('accident_status', '=', '已完成');
                } elseif ($accident_status === '未完成') {
                    $query->where('accident_status', '!=', '已完成');
                }
            })
            ->where('location','<>','')
        ;

        return $query;
    }

}
