<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
interface _CrudControllerInterface{
    public function getList(Request $request);

    public function getById(Request $request, $id);

    public function create(Request $request);

    public function update(Request $request, $id);

    public function delete(Request $request, $id);
}
