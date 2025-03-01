<?php

namespace App\Controllers;

use App\Models\User;
use App\Validations\UserStore;
use App\Validations\UserUpdate;

class Users extends Controller
{
    /**
     * Verify if user is logged.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('Auth');
    }

    /**
     * Show home page.
     *
     * @return view
     */
    public function index()
    {
        $users = User::get();
        return view('users/index', compact('users'));
    }

    /**
     * Show create user page.
     *
     * @return view
     */
    public function create()
    {
        return view('users/create');
    }

    /**
     * Store user in database.
     *
     * @return redirect
     */
    public function store()
    {
        UserStore::validate();

        $file = files()->input('photo')->upload('resources/assets/img/users');

        $user = User::create([
            'name'          => post('name'),
            'email'         => post('email'),
            'password'      => md5(post('password')),
            'photo'         => $file->filename,
            'date_create'   => now('Y-m-d H:i:s'),
            'date_update'   => now('Y-m-d H:i:s')
        ]);

        $user->update(['hash' => md5($user->id)]);

        return redirect('/dashboard/users')->with('info', 'Usuario registrado satisfactoriamente.');
    }

    /**
     * Show edit user page.
     *
     * @return view
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('users/edit', compact('user'));
    }

    /**
     * Update user in database.
     *
     * @return redirect
     */
    public function update()
    {
        UserUpdate::validate();

        $file = files()->input('photo')->upload('resources/assets/img/users');

        $user = User::find(post('id'));
        $user->update([
            'name'  => post('name'),
            'email' => post('email'),
            'date_update'   => now('Y-m-d H:i:s')
        ]);

        if (post('password')) {
            $user->update([
                'password' => md5(post('password')),
            ]);
        }

        if ($file->filename != '') {
            $user->update([
                'photo' => $file->filename,
            ]);
        }

        if ($user->id == session('id')) {
            session('name', $user->name);
            session('photo', $user->photo);
        }

        return redirect('/dashboard/users')->with('info', 'Usuario actualizado satisfactoriamente.');
    }

    /**
     * Delete user in database.
     *
     * @return void
     */
    public function delete($id)
    {
        if ($id == session('id')) {
            return redirect('/dashboard/users')->with('error', 'No se puede borrar, el usuario está en uso.');
        }

        User::find($id)->delete();

        return redirect('/dashboard/users')->with('info', 'Usuario eliminado satisfactoriamente.');
    }
}
