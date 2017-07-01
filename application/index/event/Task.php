<?php
namespace app\index\event;
use think\Controller;
use think\Db;
use app\index\model\Task as TaskModel;
use app\index\model\TaskType as TaskTypeModel;
use app\index\model\MemberTask as MemberTaskModel;
class Task extends Controller
{   
    //任务列表
    public function lists($data)
    {
        $where=[];
        $type = isset($data['type']) ? $data['type'] : 1;//$type = $data['type'];
        $where['type_id']=$type;
        $member_id = $data['member_id'];
        $Task = new TaskModel();
        $TaskType = new TaskTypeModel();
        $TaskMember = new MemberTaskModel();

        $taskTitle = $TaskType->select(); //获取task类型(导航)

        if(!$taskTitle)
            return getJson(0,'Task/lists','获取任务类型失败',0);
        $taskList = $Task->where($where)->select();
        if(!$taskList)
            return getJson(0,'Task/lists','获取任务列表失败',0);

        $hasFinish = $TaskMember->where('member_id',$member_id)->select(); //查询已经完成的任务类型

        //获取任务列表

        $taskIds  = array();
        if($hasFinish){
            $taskIds = array_column($hasFinish,'task_id'); //取出所有的taskid
        }

        

        foreach($taskList as $k => $task){
            if(in_array($task['id'], $taskIds)==true){
                $taskList[$k]['finish'] = 1; //已完成
            }else{
                $taskList[$k]['finish'] = 0; //未完成
            }
        }
    
        return getJson(1,'Task/lists','获取成功',0,['type'=>$taskTitle,'lists'=>$taskList]);
    

    }
}
