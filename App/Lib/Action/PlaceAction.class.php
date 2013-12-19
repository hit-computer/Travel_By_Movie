<?php
 include_once( 'config.php' );
 include_once( 'saetv2.ex.class.php' );
	class PlaceAction extends Action{
		public function index(){
			$id=$_REQUEST['id'];
			$Point = M("Point"); // 实例化User对象
			// 把查询条件传入查询方法
			$data=$Point->where("id=$id")->select();
			$mid = $data[0]['m_id'];
			$movie = D("Movie");
			$list = $movie->where("id=$mid")->select();
			//var_dump($data);
			//var_dump($mid);
			$this->assign('data',$data);
			$this->assign('list',$list);
			$this->display();
		}
		
		public function viewpoint(){
			$pid=$_REQUEST['id'];
			$Place = D("Place");
			$data = $Place->where("p_id=$pid")->select();
			$this->assign('data',$data);
			$this->display();
		}
		
		public function addplace(){
			$pid=$_REQUEST['id'];
			$mid=$_REQUEST['mid'];
			$Travel = D("Travel");
			$data["pid"] = $pid;
			$data["m_id"] = $mid;
			$data["time"] = date("U",strtotime($_REQUEST['control_date']));
			$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
			//$ms  = $c->home_timeline(); // done
			$uid_get = $c->get_uid();
			$uid = $uid_get['uid'];
			$data['owner'] = ''.$uid;
			$o = $data['owner'];
			$fre = $Travel->where("owner=$o AND pid=$pid")->select();
			//var_dump($fre);
			if($fre)
			{
				$up_id = $fre[0]['id'];
				$result = $Travel->where("id=$up_id")->save($data);
			}
			else
			{
				$result = $Travel->data($data)->add();
			}
			if(false===$result) {
				$info['status'] = 0;
				$info['info'] = '添加失败！！';
				$this->ajaxReturn($info,'JSON');
			}else{
				$info['status'] = 1;
				$info['info'] = '添加成功！！';
				$this->ajaxReturn($info,'JSON');
			}
		}
		
		public function showlist(){
			$pid=$_REQUEST['id'];
			$travel=D("Travel");
			$data = $travel->where("pid=$pid")->select();
			$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
			//$ms  = $c->home_timeline(); // done
			//$uid_get = $c->get_uid();
			//$uid = $uid_get['uid'];
			for($i=0;$i<count($data);$i++)
			{
				$re[$i]['time']=date("Y-m-d",$data[$i]['time']);
				$uid = intval($data[$i]['owner']);
				$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
				$re[$i]['name']=$user_message['name'];
				$re[$i]['url']="http://weibo.com/".$data[$i]['owner'];
				$re[$i]['pic']=$user_message['profile_image_url'];
				$re[$i]['loc']=$user_message['location'];
				if($user_message['gender']=='m'){
					$re[$i]['gen'] = '男';
				}
				else if($user_message['gender']=='f'){
					$re[$i]['gen'] = '女';
				}
				else{
					$re[$i]['gen'] = '未知';
				}
				$re[$i]['des']=$user_message['description'];
				if($user_message['online_status']){
					$re[$i]['online']='在线';
				}
				else{
					$re[$i]['online']='不在线';
				}
			}
			$this->assign('data',$data);
			$this->assign('list',$re);
			//var_dump($re);
			$this->display();
		}
	}