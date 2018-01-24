<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('contentimg_delete'))
{
    function contentimg_delete($article_content)
    {
        preg_match_all('/[^"]*.jpg|.gif|.jpeg|.png|.bmp/', $article_content, $pic_array);

        $article_array = $pic_array[0];

        for ($i = 0; $i < count($article_array); $i++)
        {
            $pic_url = '.' . $article_array[$i];
            if (file_exists($pic_url))
            {
                unlink($pic_url);
            }
        }
    }
}

if ( ! function_exists('contentimg_array'))
{
    function contentimg_array($article_content)
    {
        preg_match_all('/[^"]*.jpg|.gif|.jpeg|.png|.bmp/' , $article_content , $pic_array);

        $article_array = $pic_array[0];

        for($i = 0 ; $i < count($article_array) ; $i++)
        {
            $article_array[$i] = '.' . $article_array[$i];
        }

        return $article_array;
    }
}

if( ! function_exists('contentimg_modify'))
{
    function contentimg_modify($contentimg_array , $time)
    {
        for($i = 0 ; $i < count($contentimg_array) ; $i++)
        {
            rename('./kindeditor/attached/image/' . date('Ymd' , $time) . '/temp_' .
                substr($contentimg_array[$i], strrpos($contentimg_array[$i] , '/') + 1) , $contentimg_array[$i]);
        }

        $file_of_attach = scandir('./kindeditor/attached/image' , 1);

        for($j = 0 ; $j < count($file_of_attach) - 2 ; $j++)
        {
            $before_date_images = scandir('./kindeditor/attached/image/' . $file_of_attach[$j] , 1);

            for($p = 0 ; $p < count($before_date_images) - 2 ; $p++)
            {
                if(substr($before_date_images[$p] , 0 , 5) == 'temp_')
                {
                    unlink('./kindeditor/attached/image/' . $file_of_attach[$j] . '/' . $before_date_images[$p]);
                }
            }

            $after_date_images = scandir('./kindeditor/attached/image/' . $file_of_attach[$j] , 1);

            if(count($after_date_images) == 2)
            {
                rmdir('./kindeditor/attached/image/' . $file_of_attach[$j]);
            }
        }
    }
}