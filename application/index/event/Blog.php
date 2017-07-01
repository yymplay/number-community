<?php
namespace app\index\event;

class Blog 
{
    public function insert()
    {
        return 'insert';
    }

    public function update($id)
    {
        return 'update:'.$id;
    }

    public function delete($id)
    {
        return 'delete:'.$id;
    }
}