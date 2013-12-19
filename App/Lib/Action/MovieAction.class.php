<?php
 include_once( 'config.php' );
 include_once( 'saetv2.ex.class.php' );
	class MovieAction extends Action{
		public function index(){
			$id=$_REQUEST['id'];
			$Point = M("Point");
			$value = $Point ->where("m_id=$id")->select();
			$this->assign('list',$value);
			$User = M("Movie"); // 实例化User对象
			// 把查询条件传入查询方法
			$love=$User->where("tag=1")->select(); 
			$fun=$User->where("tag=2")->select();
			$other=$User->where("tag=3")->select();
			$this->assign('li_love',$love);
			$this->assign('li_fun',$fun);
			$this->assign('li_other',$other);
			$this->assign('id',$id);
			$this->display();
		}
	
		public function add(){
			$User = M("Movie"); // 实例化User对象
			// 把查询条件传入查询方法
			$this -> display();
		}
		
		public function addmovie(){
			$Movie = D('Movie');
			$data['name'] = $_POST['name'];
			$data['tag'] = $_POST['optionsRadios'];
			$data['content'] = $_POST['detail'];
			$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
			//$ms  = $c->home_timeline(); // done
			$uid_get = $c->get_uid();
			$uid = $uid_get['uid'];
			//$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
			$data['owner'] = ''.$uid;
			//var_dump($data);
			$m_id=$Movie->data($data)->add(); // 写入用户数据到数据库
			// 把创建的数据对象写入数据库
			//$result=$User->add();
			
			$this->assign('movie_id',$m_id);
			$this -> display();
		}
		
		public function addpoint(){
			$m_id=$_REQUEST['mid'];
			$Point = D("Point");
			if($_FILES) {
				import("@.ORG.UploadFile");
				$upload = new UploadFile();// 实例化上传类
				$upload->maxSize  = 3145728 ;// 设置附件上传大小
				$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
				$upload->savePath =  './Public/Uploads/';// 设置附件上传目录
				$upload->thumb = true;
				 // 设置引用图片类库包路径
				$upload->imageClassPath = '@.ORG.Image';
				 //设置需要生成缩略图的文件后缀
				$upload->thumbPrefix = 's_';  //生产1张缩略图
				 //设置缩略图最大宽度
				$upload->thumbMaxWidth = '100';
				 //设置缩略图最大高度
				$upload->thumbMaxHeight = '100';
				 //设置上传文件规则
				 $upload->saveRule = 'uniqid';
				//删除原图
				//$upload->thumbRemoveOrigin = true;
				 if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
				 }else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				 }
				
				// 根据表单提交的POST数据创建数据对象
				$data['m_id'] = $m_id;
				$data['point_x'] = $_POST['px'];
				$data['point_y'] = $_POST['py'];
				$data['content'] = $_POST['con'];
				$data['photo'] = $info[0]['savename']; // 保存上传的照片根据需要自行组装
				$pid=$Point->data($data)->add();
				$place = D("Place");
				$pdata['m_id'] = $m_id;
				$pdata['p_id'] = $pid;
				$pdata['photo1'] = $info[1]['savename'];
				$pdata['photo2'] = $info[2]['savename'];
				$pdata['photo3'] = $info[3]['savename'];
				$pdata['photo4'] = $info[4]['savename'];
				$pdata['photo5'] = $info[5]['savename'];
				$pdata['photo6'] = $info[6]['savename'];
				$result=$place->data($pdata)->add();
			}
			//echo $result;
			
			// 把创建的数据对象写入数据库
			//$result=$User->add();
			$Point = M("Point");
			$value = $Point ->where("m_id=$m_id")->select();
			$this->assign('list',$value);
			$this->assign('movie_id',$m_id);
			$this -> display();
			/*if($result){
				$this->success('success!!!',0,'index');
			}
			else{
				$this->error('fail!!!');
			}*/
			//var_dump($data);
		}
		
		public function introduce(){
			$id=$_REQUEST['id'];
			$movie = D("Movie");
			$list = $movie->where("id=$id")->select();
			if($list[0]['tag']==1)
				$list[0]['tg'] = "爱情" ;
			else if($list[0]['tag']==2)
				$list[0]['tg'] = "喜剧" ;
			else
				$list[0]['tg'] = "其他" ;
			//var_dump($list);
			$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
			//$ms  = $c->home_timeline(); // done
			//$uid_get = $c->get_uid();
			//$uid = $uid_get['uid'];
			$uid = intval($list[0]['owner']);
			$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
			$list[0]['owname'] = $user_message['name'];
			//$list[0]['wid'] = $uid;
			
			//var_dump($list);
			$this->assign('list',$list);
			$this -> display();
		}
		
		public function delmovie(){
			$id = $_REQUEST['id'];
			$movie = D("Movie");
			$place = D("Place");
			$point = D("Point");
			$travel = D("Travel");
			$del_po = $point->where("m_id=$id")->select();
			$del_pl = $place->where("m_id=$id")->select();
			if($del_po){
				foreach($del_po as $k=>$v){
				  sae_unlink('./Public/Uploads/'.$v['photo']);
				   sae_unlink('./Public/Uploads/s_'.$v['photo']);
				}
			}
			if($del_pl){
				foreach($del_pl as $k=>$v){
					if($v['photo1']){
					  sae_unlink('./Public/Uploads/'.$v['photo1']);
					   sae_unlink('./Public/Uploads/s_'.$v['photo1']);
					}
					if($v['photo2']){
					  sae_unlink('./Public/Uploads/'.$v['photo2']);
					   sae_unlink('./Public/Uploads/s_'.$v['photo2']);
					}
					if($v['photo3']){
					  sae_unlink('./Public/Uploads/'.$v['photo3']);
					   sae_unlink('./Public/Uploads/s_'.$v['photo3']);
					}
					if($v['photo4']){
					  sae_unlink('./Public/Uploads/'.$v['photo4']);
					   sae_unlink('./Public/Uploads/s_'.$v['photo4']);
					}
					if($v['photo5']){
					  sae_unlink('./Public/Uploads/'.$v['photo5']);
					   sae_unlink('./Public/Uploads/s_'.$v['photo5']);
					}
					if($v['photo6']){
					  sae_unlink('./Public/Uploads/'.$v['photo6']);
					   sae_unlink('./Public/Uploads/s_'.$v['photo6']);
					}
				}
			}
			$r1 = $movie->where("id=$id")->delete();
			$r2 = $place->where("m_id=$id")->delete();
			$r3 = $point->where("m_id=$id")->delete();
			$r4 = $travel->where("m_id=$id")->delete();
			$result = $r1&&$r2&&$r3;
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
	}