<?php

/*
 * This file is part of CidarBot Application.
 * (c) The Cidar Development Team <dev@grupocidarsegurosyfianzas.com>
 */

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];

        // Implement eager loading
        if ($this->relationLoaded('roles')) {
            $data['roles'] = RoleResource::collection($this->roles)->pluck('name');
        }

        // Implement eager loading, use getAllPermissions because actually dont return permissions when eager loading
        if ($this->relationLoaded('permissions')) {
            if ($request->user()->hasRole('super_admin')) {
                $data['permissions'] = PermissionResource::collection($this->permissions)->pluck('name');
            } else {
                $data['permissions'] = $this->getAllPermissions()->pluck('name');
            }
        }

        return $data;
    }
}
