<?php

use Illuminate\Http\Request;
interface CrudController{
    public function getList(Request $request);

    public function getById(Request $request);

    public function create(Request $request);

    public function update(Request $request);

    public function delete(Request $request);
}
