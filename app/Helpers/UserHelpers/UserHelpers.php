<?php

namespace App\Helpers\UserHelpers;

use Throwable;
use App\Models\User;
use App\Models\UserRolesModel;

/**
 * Helper untuk manajemen user dan roles
 * Mengambil data, menambah, mengubah, & menghapus ke tabel users dan user_roles
 *
 * @author  Muhammad Naufal Gibran <naufalgibran961@gmail.com>
 */
class UserHelpers
{
    private $UserModel;
    private $RolesModel;

    public function __construct()
    {
        $this->UserModel = new User();
        $this->RolesModel = new UserRolesModel();
    }

    /**
     * Mengambil data Users dari tabel Users
     *
     * @author Muhammad Naufal Gibran naufalgibran961@gmail.com
     *
     * @param  array $filter
     * $filter['nrp'] = string
     * $filter['nama'] = string
     * @param integer $itemPerPage jumlah data yang tampil dalam 1 halaman, kosongi jika ingin menampilkan semua data
     * @param string $sort nama kolom untuk melakukan sorting mysql beserta tipenya DESC / ASC
     *
     * @return object
     */
    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        return $this->UserModel->getAll($filter, $itemPerPage, $sort);
    }

     /**
     * method untuk menginput data baru ke tabel Users
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@gmail.com>
     *
     * @param array 
     *
     * @return array
     */
    public function create(array $payload): array
    {
        try {

            $user = $this->UserModel->store($payload);

            return [
                'status' => true,
                'data' => $user
            ];
        } catch (Throwable $th) {

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * Mengambil spesifik roles dari tabel users
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@gmail.com>
     *
     * @param int $id id dari tabel users
     *
     * @return array
     */
    public function getById(int $id): array
    {
        $user = $this->UserModel->getById($id);
        if (empty($user)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $user
        ];
    }

      /**
     * Delete data users
     *
     * @param integer $cpmkId
     * @return void
     */
    public function delete(int $id)
    {
        try {

            $this->UserModel->drop($id);

            return [
                'status' => true,
                'data' => $id
            ];
        } catch (Throwable $th) {

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * method untuk mengubah data user di table users
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@email.com>
     *
     * @param array $payload
     *                       $payload['name'] = string
     *
     * @return array
     */
    public function update(array $payload): array
    {
        try {

            $this->UserModel->edit($payload, $payload['id']);

            $user = $this->getById($payload['id']);
            
            return [
                'status' => true,
                'data' => $user['data']
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    // roles

    /**
     * Mengambil data roles dari tabel Users_roles
     *
     * @author Muhammad Naufal Gibran naufalgibran961@gmail.com
     *
     * @param  array $filter
     * $filter['nrp'] = string
     * $filter['nama'] = string
     * @param integer $itemPerPage jumlah data yang tampil dalam 1 halaman, kosongi jika ingin menampilkan semua data
     * @param string $sort nama kolom untuk melakukan sorting mysql beserta tipenya DESC / ASC
     *
     * @return object
     */
    public function getAllRoles(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        return $this->RolesModel->getAll($filter, $itemPerPage, $sort);
    }

     /**
     * method untuk menginput data baru ke tabel Users
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@gmail.com>
     *
     * @param array 
     *
     * @return array
     */
    public function createRoles(array $payload): array
    {
        try {

            $roles = $this->RolesModel->store($payload);

            return [
                'status' => true,
                'data' => $roles
            ];
        } catch (Throwable $th) {

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * Mengambil spesifik roles dari tabel users
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@gmail.com>
     *
     * @param int $id id dari tabel users
     *
     * @return array
     */
    public function getByIdRoles(int $id): array
    {
        $roles = $this->RolesModel->getById($id);
        
        if (empty($roles)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $roles
        ];
    }

      /**
     * Delete data users
     *
     * @param integer $cpmkId
     * @return void
     */
    public function deleteRoles(int $id)
    {
        try {

            $this->RolesModel->drop($id);

            $payload = [
                'user_roles_id' => 2
            ];
    
            $user = $this->UserModel->editRoles($payload, $id);

            return [
                'status' => true,
                'data' => $id
            ];
        } catch (Throwable $th) {

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * method untuk mengubah data user di table users
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@email.com>
     *
     * @param array $payload
     *                       $payload['name'] = string
     *
     * @return array
     */
    public function updateRoles(array $payload): array
    {
        try {

            $this->RolesModel->edit($payload, $payload['id']);

            $roles = $this->getByIdRoles($payload['id']);
            
            return [
                'status' => true,
                'data' => $roles['data']
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

} 