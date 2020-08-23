<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{    
    /**
     * collection
     *
     * @param  \Illuminate\Support\Collection $rows
     * @return App\User
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            switch (strtolower($row[0])) {
                case 'create':
                    User::create([
                        'email' => $row[1],
                        'name' => $row[2],
                        'password' => bcrypt($row[3]),
                    ]);

                    break;
                
                case 'update':
                    $user = User::where('email', $row[1])->first();
                    
                    $user->update([
                        'email' => $row[1],
                        'name' => $row[2],
                        'password' => bcrypt($row[3]),
                    ]);

                    break;
                
                case 'delete':
                    $user = User::where('email', $row[1])->first();

                    $user->delete();

                    break;
                
                default:
                    break;
            }
        }
    }
}
