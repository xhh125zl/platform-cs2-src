<?php
namespace shop\controller;
class articleController extends controllController {
	public function __construct() {
		parent::_initialize();
	}
	//单页
	public function contentOp() {
	    if(!empty($_GET['id'])) {
			$id = $_GET['id'];
		}else {
		    $this->error('缺少必要参数');
		}
		$category_model = model('shop_articles_category');
		$cateInfo = $category_model->field('*')->where(array('Users_ID'=>$this->UsersID,'Category_Type'=>'单页','Category_ID'=>$id))->find();
		$Bread = array(
			url('index/index') => '首页',
			url('article/list', array('id'=>$cateInfo['Category_ID'])) => $cateInfo['Category_Name'],
		);
		$this->assign('Bread', $Bread);
		$this->assign('title', $cateInfo['Category_Name']);
		$this->assign('cateInfo', $cateInfo);
		$this->display('article_content.php', 'home', 'home_layout');
	}
	//文章列表页
	public function listOp() {
	    if(!empty($_GET['id'])) {
			$id = $_GET['id'];
		}else {
		    $this->error('缺少必要参数');
		}
	    $category_model = model('shop_articles_category');
		$article_model = model('shop_articles');
		$cateInfo = $category_model->field('*')->where(array('Users_ID'=>$this->UsersID,'Category_ID'=>$id))->find();
		$Bread = array(
			url('index/index') => '首页',
			url('article/list', array('id'=>$cateInfo['Category_ID'])) => $cateInfo['Category_Name'],
		);
		$this->assign('Bread', $Bread);
		$this->assign('title', $cateInfo['Category_Name']);
		$articles = $article_model->field('*')->where(array('Users_ID'=>$this->UsersID,'Category_ID'=>$id))->select();
		$this->assign('list_arc', $articles);
		$this->display('article_list.php', 'home', 'home_layout');
	}
	//文章详情页
	public function indexOp() {
	    if(!empty($_GET['id'])) {
			$id = $_GET['id'];
		}else {
		     $this->error('缺少必要参数');
		}
		$category_model = model('shop_articles_category');
		$article_model = model('shop_articles');
		$rsArticles = $article_model->field('*')->where(array('Users_ID'=>$this->UsersID, 'Article_ID'=>$id, 'Article_Status'=>1))->find();
		if(!$rsArticles){
			$this->error('文章已被删除！');
		}
		$this->assign('title', $rsArticles['Article_Title']);

		$cateInfo = $category_model->field('*')->where(array('Users_ID'=>$this->UsersID,'Category_ID'=>$rsArticles['Category_ID']))->find();
		$Bread = array(
			url('index/index') => '首页',
			url('article/list', array('id'=>$cateInfo['Category_ID'])) => $cateInfo['Category_Name'],
			url('article/index', array('id'=>$id)) => $rsArticles['Article_Title']
		);
		$this->assign('Bread', $Bread);//面包
		//上一页
		$rsArticles_prev = $article_model->field('*')->where(array('Users_ID'=>$this->UsersID, 'Article_ID'=>($id-1), 'Article_Status'=>1))->find();
		//下一页
		$rsArticles_next = $article_model->field('*')->where(array('Users_ID'=>$this->UsersID, 'Article_ID'=>($id+1), 'Article_Status'=>1))->find();
		$this->assign('rsArticles_prev', $rsArticles_prev);
		$this->assign('rsArticles', $rsArticles);
		$this->assign('rsArticles_next', $rsArticles_next);
		$this->display('article.php', 'home', 'home_layout');
	}
}
?>