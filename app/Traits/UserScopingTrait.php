<?php

namespace App\Traits;

use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

trait UserScopingTrait
{
    protected function getAuthUser(mixed $user = null)
    {
        return $user ?: Auth::user();
    }

    protected function isSuperUser(mixed $user = null): bool
    {
        $authUser = $this->getAuthUser($user);
        if (!$authUser || !$authUser->role) {
            return false;
        }

        $roleName = strtolower($authUser->role->name ?? '');
        return in_array($roleName, ['superadmin', 'developer', 'administrator'], true);
    }

    protected function isHrga(mixed $user = null): bool
    {
        $authUser = $this->getAuthUser($user);
        if (!$authUser || !$authUser->role) {
            return false;
        }

        $roleName = strtolower($authUser->role->name ?? '');
        return $roleName === 'hrga';
    }

    protected function getUserCompanyId(mixed $user = null): ?int
    {
        $authUser = $this->getAuthUser($user);
        if (!$authUser) {
            return null;
        }

        $companyId = $authUser->employee?->company_id 
            ?? $authUser->organization?->company_id 
            ?? $authUser->company_id;

        return $companyId ? (int) $companyId : null;
    }

    protected function getUserOrgId(mixed $user = null): ?int
    {
        $authUser = $this->getAuthUser($user);
        if (!$authUser) {
            return null;
        }

        $orgId = $authUser->employee?->org_id ?? $authUser->organization_id;
        return $orgId ? (int) $orgId : null;
    }

    protected function getUserDescendantOrgIds(mixed $user = null): array
    {
        $userOrgId = $this->getUserOrgId($user);
        if (!$userOrgId) {
            return [];
        }

        return $this->resolveDescendantOrgIds($userOrgId);
    }

    protected function resolveDescendantOrgIds(int|string|null $orgId): array
    {
        if (!$orgId) {
            return [];
        }

        $ids = [(int) $orgId];
        $children = Organization::where('parent_id', $orgId)->pluck('id')->toArray();

        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->resolveDescendantOrgIds($childId));
        }

        return array_values(array_unique($ids));
    }

    protected function getCompanyOrgIds(?int $companyId): array
    {
        if (!$companyId) {
            return [];
        }

        return Organization::where('company_id', $companyId)->pluck('id')->toArray();
    }
}
