<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\index\model\Banners;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
        //轮播图片
        $banners = Banners::all(array('status'=>1));

        //文章、图片文章、视频文章
        /*
         * @params type int 文章类型：1-文章带视频, 2-文章带图片, 3-纯文章
         */
        $allContent = Banners::table('fa_article')->where(array('status'=>1))->limit(100)->order('weigh', 'desc')->select();

        $articles            = array();
        $articlesWithPicture = array();
        $articlesWithVideo   = array();

        if (!empty($allContent)) {
            foreach ($allContent as $items){
                switch ($items['types']){
                    case 1:
                        array_push($articlesWithVideo,$items);
                        break;
                    case 2:
                        array_push($articlesWithPicture,$items);
                        break;
                    default:
                        array_push($articles,$items);
                        break;
                }
            }
        }

        $this->assign('banners',!empty($banners) ? $banners : []);
        $this->assign('articles',!empty($articles) ? $articles : []);
        $this->assign('articlesWithVideo',!empty($articlesWithVideo) ? array_slice($articlesWithVideo,0,4) : []);
        //$this->assign('articlesWithPicture',!empty($articlesWithPicture) ? array_slice($articlesWithPicture,0,4) : []);

        return $this->view->fetch();
    }

    /*
     * 文章页面
     */
    public function article (){
        $id = $this->request->get('id');

        if (empty($id)) {
            $this->error('参数错误','/index/index/index');
        }

        $articles = Banners::table('fa_article')->where(array('status'=>1,'id'=>$id))->find();

        if (empty($articles)) {
            $this->error('数据错误，请联系管理员','/index/index/index');
        }

        //文章、图片文章、视频文章
        /*
         * @params type int 文章类型：1-文章带视频, 2-文章带图片, 3-纯文章
         */
        $articlesWithVideo = Banners::table('fa_article')->where(array('status'=>1,'types'=>1))->limit(100)->order('weigh', 'desc')->select();

        $this->assign('articlesWithVideo',!empty($articlesWithVideo) ? array_slice($articlesWithVideo,0,4) : []);
        $this->assign('articles',!empty($articles) ? $articles : []);
        return $this->view->fetch();
    }

}
