<?php
 include_once( 'config.php' );
 include_once( 'saetv2.ex.class.php' );
	class MapAction extends Action{
		public function index(){
			$User = M("Movie"); // 实例化User对象
			// 把查询条件传入查询方法
			$love=$User->where("tag=1")->select(); 
			$fun=$User->where("tag=2")->select();
			$other=$User->where("tag=3")->select();
			$this->assign('li_love',$love);
			$this->assign('li_fun',$fun);
			$this->assign('li_other',$other);
			$this->display();
		}
		
		public function travel(){
			$model=new Model();
			$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
			//$ms  = $c->home_timeline(); // done
			$uid_get = $c->get_uid();
			$uid = $uid_get['uid'];
			$oid = ''.$uid;
			$mems=$model->table('think_point p,think_travel t')
				  ->where("t.pid=p.id AND t.owner=$oid")
				  ->field('p.*,t.time')->order('t.time')
				  ->select();
			/*$Travel = D("Travel");
			$Point = D("Point");
			$data = $Travel->where()->select();
			for($i=0;$i<count($data);$i++)
			{
				$re['id'][$i] = $data[$i]['pid'];
			}
			var_dump($re);
			$tt['id'] = array(1,2,3);
			$pdata = $Point->where($tt)->select();*/
			$User = D("Movie"); // 实例化User对象
			// 把查询条件传入查询方法
			$love=$User->where("tag=1")->select(); 
			$fun=$User->where("tag=2")->select();
			$other=$User->where("tag=3")->select();
			$this->assign('li_love',$love);
			$this->assign('li_fun',$fun);
			$this->assign('li_other',$other);
			for($i=0;$i<count($mems);$i++){
				$mems[$i]['time'] = date("Y-m-d",$mems[$i]['time']);
			}
			$this->assign('list',$mems);
			//var_dump($mems);
			$this->display();
		}
		
		public function around(){
			$px = $_REQUEST['px'];
			$py = $_REQUEST['py'];
			$Point = M("Point");
			$map['point_x'] = array(array('gt',$px-1),array('lt',$px+1)) ;
			$map['point_y'] = array(array('gt',$py-1),array('lt',$py+1)) ;
			//var_dump($map);
			$value = $Point ->where($map)->select();
			$this->assign('list',$value);
			$User = M("Movie"); // 实例化User对象
			// 把查询条件传入查询方法
			$love=$User->where("tag=1")->select(); 
			$fun=$User->where("tag=2")->select();
			$other=$User->where("tag=3")->select();
			$this->assign('li_love',$love);
			$this->assign('li_fun',$fun);
			$this->assign('li_other',$other);
			$this->display();
		}
		
		public function schedule(){
			$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
			$uid_get = $c->get_uid();
			$uid = $uid_get['uid'];
			$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
			$name = $user_message['name'];
			$this->assign('nm',$name);
			$this->display();
		}
		
		public function delsche(){
			$travel = D("Travel");
			$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
			//$ms  = $c->home_timeline(); // done
			$uid_get = $c->get_uid();
			$uid = $uid_get['uid'];
			$oid = ''.$uid;
			$result = $travel->where("owner=$oid")->delete();
			if(false===$result) {
				$info['status'] = 0;
				$info['info'] = '删除失败！！';
				$this->ajaxReturn($info,'JSON');
			}else{
				$info['status'] = 1;
				$info['info'] = '删除成功！！';
				$this->ajaxReturn($info,'JSON');
			}
		}
		
		public function send(){
			$model=new Model();
			$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
			$ms  = $c->home_timeline(); // done
			$uid_get = $c->get_uid();
			$uid = $uid_get['uid'];
			$oid = ''.$uid;
			$mv = $model->table('think_movie m,think_travel t')
				  ->where("m.id=t.m_id AND t.owner=$oid")
				  ->field('m.*')
				  ->select();
			foreach($mv as $k=>$v){
				$mname = $mname.'《'.$v['name'].'》'.'、';
			}
			$text = '#'.$_REQUEST['weibo'].'#'.' 举头看电影，低头去旅游。我正要去电影'.$mname.'里的景点旅游咯。一起来玩跟着电影去旅游吧！http://6.travelbymovie.sinaapp.com/';
			$ret = $c->update($text);
			if(false===$ret) {
				$info['status'] = 0;
				$info['info'] = '删除失败！！';
				$this->ajaxReturn($info,'JSON');
			}else{
				$info['status'] = 1;
				$info['info'] = '删除成功！！';
				$this->ajaxReturn($info,'JSON');
			}
		}
	}