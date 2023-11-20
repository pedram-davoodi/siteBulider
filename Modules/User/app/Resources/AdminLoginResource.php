<?php

namespace Modules\User\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\App\Models\Admin;
use Modules\User\app\Models\User;
use Laravel\Passport\PersonalAccessTokenResult;

class AdminLoginResource extends JsonResource
{
    public function __construct(public Admin $admin ,public PersonalAccessTokenResult $token)
    {
        parent::__construct($this);
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->admin->name,
            'token' => $this->token->accessToken,
        ];
    }
}
