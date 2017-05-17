<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 2016/6/8
 * Time: 20:13
 **/


/**
 * 先获得图片的大小，类型
 *水印：就是把指定的图片复制的目标图片上，并加透明效果
 * 缩略图：就是把大图片复制到小尺寸画布上
 */
class ImageTool{
    //专门分析图片的信息
    public static function imageInfo($image){
        //判断图片是否存在
        if(!file_exists($image)){
            return false;
        }
        $info = getimagesize($image);
        if($info == false){
            return false;
        }
        //此时info获取数组
        $img['width'] = $info[0];
        $img['height'] = $info[1];
        $img['ext'] = substr($info['mime'],strpos($info['mime'],'/')+1);
        return $img;
    }
    /**
     * 加水印
     * $dst,string,原始图
     * $water,水印小图
     * $save,保存路径,不填则默认替换原始图
     * $alpha,透明度
     *
     */
    public static function water($dst,$water,$save=NULL,$pos=2,$alpha=50){
        //保证2个图片都存在
        if(!file_exists($dst)||!file_exists($water)){

            return false;
        }

        //首先保证水印不能比原始图大
        $dinfo = self::imageInfo($dst);
        $winfo = self::imageInfo($water);


        if($winfo['height']>$dinfo['height'] || $winfo['width']>$dinfo['width']){

            return false;
        };

        //用哪个函数读
        $dfunc = 'imagecreatefrom'.$dinfo['ext'];
        $wfunc = 'imagecreatefrom'.$winfo['ext'];

//        echo '<pre>';
//        print_r($dfunc);
//        echo '<pre>';
//        print_r($wfunc);die;


        //如果后缀奇怪，不存在的函数名
        if(!function_exists($dfunc) || !function_exists($wfunc)){
            return false;
        };

        //动态加载函数来创建画布
        $dim = $dfunc($dst);
        $wim = $wfunc($water);

        //根据水印的位置，计算粘贴的坐标
        switch ($pos) {
            case 0://左上角
                $posx = 0;
                $posy = 0;
                break;
            case 1://右上角
//                echo 111;die;
                $posx = $dinfo['width']-$winfo['width'];
//                $posx = 818;
                $posy = 0;
                break;
            case 3://左下角
                $posx = 0;
                $posy = $dinfo['height']-$winfo['height'];
//                $posy = 630;
            default://默认右下角
                $posx = $dinfo['width']-$winfo['width'];
                $posy = $dinfo['height']-$winfo['height'];
        }

//        echo $dinfo['width']-$winfo['width'];
//        echo $dinfo['height']-$winfo['height'];
//        die;

        //加水印
        imagecopymerge ($dim,$wim,$posx,$posy,0,0,$winfo['width'],$winfo['height'],$alpha);

        //保存
        if(!$save){
            $save = $dst;
            unlink($dst);//删除原图
        }

        //生成水印
        $createfunc = 'image'.$dinfo['ext'];

//        echo '<pre>';
//        print_r($createfunc);die;

        $createfunc($dim,$save);

        //销毁图片资源
        imagedestroy($dim);
        imagedestroy($wim);

        return true;

    }

    /**
     * 生成缩略图
     * 等比例缩放，两边留白
     * bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
     */
    public static function thumb($dst,$save=NULL,$width=200,$height=200){
        //首先判断目标图是否存在
        if(!file_exists($dst)){
            return false;
        }

        //获取图片的相关信息
        $dinfo = self::imageInfo($dst);
        if($dinfo == false){
            return false;
        };

        //计算机缩放比例,选较小值
        $calc = min($width/$dinfo['width'],$height/$dinfo['height']);

        //创建原生图的画布
        $dfunc = 'imagecreatefrom' . $dinfo['ext'];
        $dim = $dfunc($dst);

        //创建缩略画布
        $tim = imagecreatetruecolor($width,$height);

        //创建白色
        $white = imagecolorallocate($tim,255,255,255);

        //填充缩略画布
        imagefill($tim,0,0,$white);

        //复制并缩略
        //imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
        $dwidth = (int)$dinfo['width']*$calc;
        $dheight = (int)$dinfo['height']*$calc;

        $paddingx = (int)($width-$dwidth)/2;
        $paddingy = (int)($height-$dheight)/2;

//        echo '<pre>';
//        print_r($dwidth);
//        echo '<pre>';
//        print_r($dheight);
//        echo '<pre>';
//        print_r($paddingx);
//        echo '<pre>';
//        print_r($paddingy);
//
//        die;

//imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
        imagecopyresampled($tim,$dim,$paddingx,$paddingy,0,0,$dwidth,$dheight,$dinfo['width'],$dinfo['height']);

        //保存图片
        if(!$save){
            $save = $dst;
            unlink($dst);//删除原图
        }

        $createfunc = 'image'.$dinfo['ext'];
        $createfunc($tim,$save);

        imagedestroy($dim);
        imagedestroy($tim);

        return true;

    }




    /**
     *
     *
     */
    //写验证码
    /*
        author: dabao
    */
    public static function captcha($width=50,$height=25) {
        //造画布
        $image = imagecreatetruecolor($width,$height) ;

        //造背影色
        $gray = imagecolorallocate($image, 200, 200, 200);

        //填充背景
        imagefill($image, 0, 0, $gray);

        //造随机字体颜色
        $color = imagecolorallocate($image, mt_rand(0, 125), mt_rand(0, 125), mt_rand(0, 125)) ;
        //造随机线条颜色
        $color1 =imagecolorallocate($image, mt_rand(100, 125), mt_rand(100, 125), mt_rand(100, 125));
        $color2 =imagecolorallocate($image, mt_rand(100, 125), mt_rand(100, 125), mt_rand(100, 125));
        $color3 =imagecolorallocate($image, mt_rand(100, 125), mt_rand(100, 125), mt_rand(100, 125));

        //在画布上画线
        imageline($image, mt_rand(0, 50), mt_rand(0, 25), mt_rand(0, 50), mt_rand(0, 25), $color1) ;
        imageline($image, mt_rand(0, 50), mt_rand(0, 20), mt_rand(0, 50), mt_rand(0, 20), $color2) ;
        imageline($image, mt_rand(0, 50), mt_rand(0, 20), mt_rand(0, 50), mt_rand(0, 20), $color3) ;

        //在画布上写字
        $text = substr(str_shuffle('ABCDEFGHIJKMNPRSTUVWXYZabcdefghijkmnprstuvwxyz23456789'), 0,4) ;
        imagestring($image, 5, 7, 5, $text, $color) ;

        //显示、销毁
        header('content-type: image/jpeg');
        imagejpeg($image);
        imagedestroy($image);
    }


}


?>