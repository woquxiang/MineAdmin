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
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\ModelNotFoundException;

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
         //如果参数中包含身份证号和姓名，则通过身份证号和姓名过滤相关的事故记录
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
            });

        return $query;
    }

}
