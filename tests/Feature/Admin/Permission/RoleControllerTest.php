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

namespace HyperfTests\Feature\Admin\Permission;

use App\Http\Common\ResultCode;
use App\Model\Permission\Role;
use Hyperf\Stringable\Str;
use HyperfTests\Feature\Admin\ControllerCase;

/**
 * @internal
 * @coversNothing
 */
class RoleControllerTest extends ControllerCase
{
    public function testPageList(): void
    {
        $token = $this->token;
        $result = $this->get('/admin/role/list');
        $this->assertSame($result['code'], ResultCode::UNAUTHORIZED->value);
        $result = $this->get('/admin/role/list', ['token' => $token]);
        $this->assertSame($result['code'], ResultCode::FORBIDDEN->value);
        $enforce = $this->getEnforce();
        $this->assertFalse($enforce->hasPermissionForUser($this->user->username, 'role:list'));
        $this->assertTrue($enforce->addPermissionForUser($this->user->username, 'role:list'));
        $this->assertTrue($enforce->hasPermissionForUser($this->user->username, 'role:list'));
        $result = $this->get('/admin/role/list', ['token' => $token]);
        $this->assertSame($result['code'], ResultCode::SUCCESS->value);
        $this->assertTrue($enforce->deletePermissionForUser($this->user->username, 'role:list'));
        $result = $this->get('/admin/role/list', ['token' => $token]);
        $this->assertSame($result['code'], ResultCode::FORBIDDEN->value);
    }

    public function testCreate(): void
    {
        $token = $this->token;
        $attribute = [
            'name',
            'code',
            'sort',
            'status',
            'remark',
        ];
        $result = $this->post('/admin/role');
        $this->assertSame($result['code'], ResultCode::UNPROCESSABLE_ENTITY->value);
        $result = $this->post('/admin/role', [], ['Authorization' => 'Bearer ' . $token]);
        $this->assertSame($result['code'], ResultCode::UNPROCESSABLE_ENTITY->value);
        $fill = [
            'name' => Str::random(10),
            'code' => Str::random(10),
            'sort' => rand(1, 100),
            'status' => rand(0, 1),
            'remark' => Str::random(),
        ];
        $result = $this->post('/admin/role', $fill, ['Authorization' => 'Bearer ' . $token]);
        $this->assertSame($result['code'], ResultCode::FORBIDDEN->value);
        $enforce = $this->getEnforce();
        $this->assertFalse($enforce->hasPermissionForUser($this->user->username, 'role:create'));
        $this->assertTrue($enforce->addPermissionForUser($this->user->username, 'role:create'));
        $this->assertTrue($enforce->hasPermissionForUser($this->user->username, 'role:create'));
        $result = $this->post('/admin/role', $fill, ['Authorization' => 'Bearer ' . $token]);
        $this->assertSame($result['code'], ResultCode::SUCCESS->value);
        $this->assertTrue($enforce->deletePermissionForUser($this->user->username, 'role:create'));
        $result = $this->post('/admin/role', $fill, ['Authorization' => 'Bearer ' . $token]);
        $this->assertSame($result['code'], ResultCode::FORBIDDEN->value);
        $entity = Role::query()->where('code', $fill['code'])->first();
        $this->assertNotNull($entity);
        $this->assertSame($entity->name, $fill['name']);
        $this->assertSame($entity->sort, $fill['sort']);
        $this->assertSame($entity->status, $fill['status']);
        $this->assertSame($entity->remark, $fill['remark']);
        $entity->forceDelete();
    }

    public function testSave(): void
    {
        $token = $this->token;
        $entity = Role::create([
            'name' => Str::random(10),
            'code' => Str::random(10),
            'sort' => rand(1, 100),
            'status' => rand(0, 1),
            'remark' => Str::random(),
        ]);
        $result = $this->put('/admin/role/' . $entity->id);
        $this->assertSame($result['code'], ResultCode::UNPROCESSABLE_ENTITY->value);
        $result = $this->put('/admin/role/' . $entity->id, [], ['Authorization' => 'Bearer ' . $token]);
        $this->assertSame($result['code'], ResultCode::UNPROCESSABLE_ENTITY->value);
        $fill = [
            'name' => Str::random(10),
            'code' => Str::random(10),
            'sort' => rand(1, 100),
            'status' => rand(0, 1),
            'remark' => Str::random(),
        ];
        $result = $this->put('/admin/role/' . $entity->id, $fill, ['Authorization' => 'Bearer ' . $token]);
        $this->assertSame($result['code'], ResultCode::FORBIDDEN->value);
        $enforce = $this->getEnforce();
        $this->assertFalse($enforce->hasPermissionForUser($this->user->username, 'role:save'));
        $this->assertTrue($enforce->addPermissionForUser($this->user->username, 'role:save'));
        $this->assertTrue($enforce->hasPermissionForUser($this->user->username, 'role:save'));
        $result = $this->put('/admin/role/' . $entity->id, $fill, ['Authorization' => 'Bearer ' . $token]);
        $this->assertSame($result['code'], ResultCode::SUCCESS->value);
        $this->assertTrue($enforce->deletePermissionForUser($this->user->username, 'role:save'));
        $result = $this->put('/admin/role/' . $entity->id, $fill, ['Authorization' => 'Bearer ' . $token]);
        $this->assertSame($result['code'], ResultCode::FORBIDDEN->value);
        $entity->refresh();
        $this->assertSame($entity->name, $fill['name']);
        $this->assertSame($entity->sort, $fill['sort']);
        $this->assertSame($entity->status, $fill['status']);
        $this->assertSame($entity->remark, $fill['remark']);
        $entity->forceDelete();
    }

    public function testDelete(): void
    {
        $token = $this->token;
        $entity = Role::create([
            'name' => Str::random(10),
            'code' => Str::random(10),
            'sort' => rand(1, 100),
            'status' => rand(0, 1),
            'remark' => Str::random(),
        ]);
        $result = $this->delete('/admin/role/' . $entity->id);
        $this->assertSame($result['code'], ResultCode::UNAUTHORIZED->value);
        $result = $this->delete('/admin/role/' . $entity->id, [], ['Authorization' => 'Bearer ' . $token]);
        $this->assertSame($result['code'], ResultCode::FORBIDDEN->value);
        $enforce = $this->getEnforce();
        $this->assertFalse($enforce->hasPermissionForUser($this->user->username, 'role:delete'));
        $this->assertTrue($enforce->addPermissionForUser($this->user->username, 'role:delete'));
        $this->assertTrue($enforce->hasPermissionForUser($this->user->username, 'role:delete'));
        $result = $this->delete('/admin/role/' . $entity->id, [], ['Authorization' => 'Bearer ' . $token]);
        $this->assertSame($result['code'], ResultCode::SUCCESS->value);
        $this->assertTrue($enforce->deletePermissionForUser($this->user->username, 'role:delete'));
        $result = $this->delete('/admin/role/' . $entity->id, [], ['Authorization' => 'Bearer ' . $token]);
        $this->assertSame($result['code'], ResultCode::FORBIDDEN->value);
        $entity->refresh();
        $this->assertTrue($entity->trashed());
        $entity->forceDelete();
    }
}