<?php

namespace App\Notifications\Customer;

use App\Models\File;
use App\Models\User;
use App\Notifications\BaseNotification;

class FileLockedNotification extends BaseNotification
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        /** @var File $file */
        $file = $data['file'];
        /** @var User $user */
        $user = $data['user'];
        $this->setData([
            'file_id' => $file->id,
            'group_id' => $file->group_id,
        ]);

        $this->setMessage("{$user->first_name} {$user->last_name} has locked the file [{$file->name}] for update in your group named {$file->group->name}");
        $this->setType(FileLockedNotification::class);
        $this->setTitle("New File Updated");
    }
}
