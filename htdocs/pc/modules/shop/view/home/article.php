<style>
/*文章新闻内容*/
.nch-article-con { background: #FFF; display: block; padding: 19px; border: 1px solid #E6E6E6; margin-bottom: 10px; overflow: hidden; }
.nch-article-con h1 { font: 600 16px/32px "microsoft yahei"; color: #3d3f3e; text-align: center; }
.nch-article-con h2 { color: #9a9a9a; font-size: 12px; padding: 5px 0 20px; margin-bottom: 20px; font-weight: normal; text-align: center; border-bottom: 1px solid #d2d2d2; }
.nch-article-con .title-bar { border-bottom: solid 1px #E6E6E6; padding-bottom: 15px; margin-bottom: 15px;}
.nch-article-con .title-bar h3 { font: normal 18px/20px "microsoft yahei";}
.nch-article-con .default p { display: block; clear: both; padding: 5px;}
.nch-article-con img { max-width: 930px;}
.nch-article-list {}
.nch-article-list li { line-height: 20px; display: block; height: 20px; padding: 5px 0;}
.nch-article-list li i { background: url(../images/2014shop_background_img.png) no-repeat -80px 0; vertical-align: middle; display: inline-block; width: 3px; height: 3px; margin-right: 10px;}
.nch-article-list li a { color: #333;}
.nch-article-list li time { font-size: 11px; color: #AAA; float: right; -webkit-text-size-adjust:none;}

.more_article { border-top: 1px solid #d2d2d2; padding: 10px 0 0 20px; margin-top: 10px; overflow: hidden; }
.more_article span { color: #3f3f3f; font-weight: normal; margin-bottom: 10px; }
.more_article span a { color: #006bcd; text-decoration: none;}
.more_article span a:hover { color: #f60; text-decoration: underline; }
.more_article time { font-size: 11px; color: #999; padding-left: 20px; }
.fl {
    display: inline;
    float: left;
}
.fr {
    display: inline;
    float: right !important;
}
</style>
<div class="comtent">
	<?php include(__DIR__ . '/_menu.php');?>
</div>
<div class="breadcrumb">
	<?php if(!empty($output['Bread'])){?>
	<?php foreach($output['Bread'] as $link => $name){?>
	<span><a href="<?php echo $link;?>"><?php echo $name;?></a></span> &nbsp;>&nbsp;
	<?php }?>
	<?php }?>
</div>
<div class="product-intro">
	<div class="wzw-wrapper" style="width:1200px; margin:0 auto;">
		<div class="nch-article-con">
		    <h1><?php echo $output['rsArticles']['Article_Title'];?></h1>
		    <h2><?php echo date('Y-m-d H:i:s', $output['rsArticles']['Article_CreateTime']);?></h2>
		    <div class="default">
			    <p><?php echo html_entity_decode($output['rsArticles']['Article_Content']);?></p>
		    </div>
		    <div class="more_article"> 
			    <span class="fl">上一篇：<?php echo empty($output['rsArticles_prev']['Article_Title']) ? '没有符合条件的文章' : '<a href="'.url('article/index', array('id'=>$output['rsArticles_prev']['Article_ID'])).'">'.$output['rsArticles_prev']['Article_Title'].'</a><time>'.date('Y-m-d H:i:s', $output['rsArticles_prev']['Article_CreateTime']).'</time>';?>
				</span>
				<span class="fr">下一篇：
					<?php echo empty($output['rsArticles_next']['Article_Title']) ? '没有符合条件的文章' : '<a href="'.url('article/index', array('id'=>$output['rsArticles_next']['Article_ID'])).'">'.$output['rsArticles_next']['Article_Title'].'</a><time>'.date('Y-m-d H:i:s', $output['rsArticles_next']['Article_CreateTime']).'</time>';?>
				</span> 
			</div>
		</div>
	</div>
</div>