<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\UserHelpers\UserHelpers;
use App\Http\Resources\Roles\RolesCollection;
use App\Http\Resources\Roles\RolesResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;

class UserController extends Controller
{
    private $user;
    
    public function __construct()
    {
        $this->user = new UserHelpers();
    }


    /**
     * Mengambil semua user
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $request)
    {
        $filter = [
            'nrp' => $request->nrp ?? '',
            'nama' => $request->nama ?? ''
        ];
        $listUser = $this->user->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');

        return response()->success(new UserCollection($listUser));
    }

     /**
     * Store a newly created resource in storage.
     * author naufalgibran971@gmail.com 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required',
            'nrp' => 'required',
            'nip' => 'required',
            'user_roles_id' => 'required',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'prodi' => 'required ',
        ], [
            'name.required' => 'Nama harus di isi.',
            'email.required' => 'Email harus di isi.',
            'nrp.required' => 'NRP harus di isi.',
            'user_roles_id.required' => 'User roles harus di isi.',
            'alamat.required' => 'alamat harus di isi.',
            'jenis_kelamin.integer' => 'jenis kelamin harus berbentuk angka.',
            'prodi.required' => 'prodi harus di isi.',
        ]);
        
        $payload = $request->only([
            'name',
            'email',
            'nrp',
            'nip',
            'user_roles_id',
            'alamat',
            'jenis_kelamin',
            'password',
            'prodi',
        ]);

        $password = $request->input('password');

        // Menggunakan operasi ternary untuk memeriksa apakah password kosong atau tidak
        $payload['password'] = empty($password) ? Hash::make('cpl123') : Hash::make($password);
        
        $user = $this->user->create($payload);

        if (!$user['status']) {
            return response()->failed($user['error']);
        }

        return response()->success(new UserResource($user['data']), 'user berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->user->getById($id);

        if (!($user['status'])) {
            return response()->failed(['Data Roles tidak ditemukan'], 404);
        }

        return response()->success(new UserResource($user['data']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $payload = $request->only([
            'name',
            'email',
            'nrp',
            'nip',
            'user_roles_id',
            'alamat',
            'jenis_kelamin',
            'password',
            'prodi',
            'id'
        ]);

        $password = $request->input('password');

        // Menggunakan operasi ternary untuk memeriksa apakah password kosong atau tidak
        $payload['password'] = empty($password) ? Hash::make('cpl123') : Hash::make($password);


        $user = $this->user->update($payload, $payload['id'] ?? 0);

        if (!$user['status']) {
            return response()->failed($user['error']);
        }

        return response()->success(new UserResource($user['data']), 'User berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->user->delete($id);
        
        if (!$user['status']) {
            return response()->failed(['Mohon maaf user tidak ditemukan']);
        }

        return response()->success($user, 'user berhasil dihapus');
    }

    // roles 

      /**
     * Mengambil semua roless
     *
     * @return \Illuminate\Http\Response
     */
    public function getRolesAll(Request $request)
    {
        $filter = [
            'name' => $request->name ?? ''
        ];
        $listRoles = $this->user->getAllRoles($filter, $request->itemperpage ?? 0, $request->sort ?? '');

        return response()->success(new RolesCollection($listRoles));
    }

     /**
     * Store a newly created resource in storage.
     * author naufalgibran971@gmail.com 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRoles(Request $request)
    {
        $request->validate([
            'name' => 'required|string'

        ], [
            'name.required' => 'Nama harus di isi.',
            'name.string' => 'Nama harus string.',
        ]);
        
        $payload = $request->only([
            'name'
        ]);

        
        $roles = $this->user->createRoles($payload);

        if (!$roles['status']) {
            return response()->failed($roles['error']);
        }

        return response()->success(new RolesResource($roles['data']), 'roles berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showRoles($id)
    {
        $roles = $this->user->getByIdRoles($id);

        if (!($roles['status'])) {
            return response()->failed(['Data roles tidak ditemukan'], 404);
        }

        return response()->success(new RolesResource($roles['data']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRoles(Request $request)
    {
        $payload = $request->only([
            'name',
            'id'
        ]);

        $roles = $this->user->updateRoles($payload, $payload['id'] ?? 0);

        if (!$roles['status']) {
            return response()->failed($roles['error']);
        }

        return response()->success(new RolesResource($roles['data']), 'Roles berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyRoles($id)
    {
        $roles = $this->user->deleteRoles($id);
        
        if (!$roles['status']) {
            return response()->failed(['Mohon maaf roles tidak ditemukan']);
        }

        return response()->success($roles, 'roles berhasil dihapus');
    }
}
